<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Schedulemessage;
use App\Models\Schedulecontact;
use App\Models\Smstransaction;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use App\Models\Template;
use App\Models\Contact;
use App\Models\CloudApi;
use App\Models\Group;
use App\Traits\Cloud;
use Carbon\Carbon;
use DateTimeZone;
use Storage;
use Http;
use Auth;
use DB;
class ScheduleController extends Controller
{
    use Cloud;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts=Schedulemessage::where('user_id',Auth::id())->with('cloudapi')->latest()->paginate(20);
        $totalSchedule=Schedulemessage::where('user_id',Auth::id())->count();
        $pendingSchedule=Schedulemessage::where('user_id',Auth::id())->where('status','pending')->count();
        $deliveredSchedule=Schedulemessage::where('user_id',Auth::id())->where('status','delivered')->count();
        $failedSchedule=Schedulemessage::where('user_id',Auth::id())->where('status','rejected')->count();

        return view('user.schedule.index',compact('posts','totalSchedule','pendingSchedule','deliveredSchedule','failedSchedule'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       
        $cloudapis=CloudApi::where('user_id',Auth::id())->where('status',1)->latest()->get();
        $templates=Template::where('user_id',Auth::id())->where('status',1)->latest()->get();
        $groups=Group::where('user_id',Auth::id())->whereHas('contacts')->withCount('contacts')->latest()->get();

        return view('user.schedule.create',compact('cloudapis','templates','groups'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (getUserPlanData('run_campaign') == false) {
            return response()->json([
                 'message'=>__('Campaign modules is not available your plan')
            ],401);  
        }
        
        

          $date = Carbon::parse($request->date);
         // $time= $date->format('g:i A');
         // $date= $date->toDate();
         $dateTime = Carbon::createFromFormat('Y-m-d H:i:s', $date, $request->timezone);
         $dateTime = $dateTime->copy()->tz(env('TIME_ZONE','UTC'));
        $validated = $request->validate([
            'cloudapi' => 'required|integer',
            'date' => 'required',
            'timezone' => 'required',
            'title' => 'required|max:100',
            'group' => 'required',
            'message_type' => 'required'
        ]);
        if ($request->message_type != 'template') {
           $validated = $request->validate([
                'message' => 'required|max:1000',
            ]);
        }

       $receivers = Group::where('user_id',Auth::id())->with('groupcontacts')->whereHas('groupcontacts')->findorFail($request->group);

        if ($request->message_type == 'template') {
            $validated = $request->validate([
                'template' => 'required',
            ]);
            $template=Template::where('user_id',Auth::id())->where('status',1)->findorFail($request->template);
        }
        
        if($request->header_image){
        $headerImage = $this->saveFile($request, 'header_image');
        }

        $cloudapi=CloudApi::where('user_id',Auth::id())->where('status',1)->findorFail($request->cloudapi);

        if ($cloudapi->user_id == Auth::id()) {

           
           $receivers_arr=[];

           foreach ($receivers->groupcontacts ?? [] as $key => $receiver) {
               
               array_push($receivers_arr, $receiver->contact_id);
           }
           abort_if(count($receivers_arr) == 0,404);
           
           

            DB::beginTransaction();
            try {
           $schedulemessage=new Schedulemessage;
           $schedulemessage->user_id=Auth::id();
           $schedulemessage->cloudapi_id=$request->cloudapi;
           $schedulemessage->title=$request->title;
           $schedulemessage->schedule_at=$dateTime;
           
           if ($request->header_param || $request->header_image || $request->body_param) {
    $parameters = [
        'header_parameters' => $request->header_param !== null ? $request->header_param : $headerImage,
        'message_parameters' => $request->body_param ?? null,
    ];
    $parametersJson = json_encode($parameters);
    $schedulemessage->body = $parametersJson;
    }else{
        $schedulemessage->body = $request->message_type != 'template' ? $request->message : null;
    }
           
           
           $schedulemessage->zone=$request->timezone;
           $schedulemessage->template_id= $request->message_type == 'template' ? $request->template : null;
           $schedulemessage->save();

           $receivers_arrs=[];
           foreach ($receivers_arr as $key => $receiverr) {
              $arr['contact_id']=$receiverr;
              $arr['schedulemessage_id']=$schedulemessage->id;
              array_push($receivers_arrs, $arr);
           }

           $schedulemessage->schedulecontacts()->insert($receivers_arrs);
            DB::commit();
         
           return response()->json(__('Campaign message created successfully...!!'),200);

           } catch (\Throwable $th) {
                DB::rollback();

                return throw($th);
    
                return response()->json([
                    'message' =>  __('Something was wrong, Please contact with Support.'),
                ], 404);
            }
           
        }
        abort(404);

        
        

    }

    public function filter_body($context)
    {
        $data=str_replace("\\r",'\r',$context);
        $data=str_replace("\\n",'\n',$data);

        return $data;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $info=Schedulemessage::with('cloudapi')->where('user_id',Auth::id())->withCount('schedulecontacts')->findorFail($id);
        $contacts=Schedulecontact::where('schedulemessage_id',$id)->whereHas('contact')->with('contact')->paginate(50);
        
        $statuses = Smstransaction::where('user_id', Auth::id())
    ->where('type', 'campaign')
    ->where('campaign_id', $id)
    ->get()->keyBy('to');
    
        return view('user.schedule.show',compact('info','contacts', 'statuses'));
    }
    
    
    public function downloadReport($id)
{
    // Fetch the necessary data
    $info = Schedulemessage::with('cloudapi')
        ->where('user_id', Auth::id())
        ->withCount('schedulecontacts')
        ->findOrFail($id);
        
        if($info->template_id != NULL){
            $template=Template::where('user_id',Auth::id())->where('status',1)->findOrFail($info->template_id);
        }
        
    
    
     $contacts=Schedulecontact::where('schedulemessage_id',$id)->whereHas('contact')->with('contact')->get();
        
        $statuses = Smstransaction::where('user_id', Auth::id())
    ->where('type', 'campaign')
    ->where('campaign_id', $id)
    ->get()->keyBy('to');

    // Create a new Spreadsheet object
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    
    // Styling variables
    $titleStyleArray = [
        'font' => ['name' => 'Tahoma', 'bold' => true, 'size' => 40],
        'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_CENTER,
            'vertical' => Alignment::VERTICAL_CENTER,
        ],
        'fill' => [
            'fillType' => Fill::FILL_SOLID,
            'startColor' => ['argb' => 'FFbada99']
        ],
        'borders' => [
            'outline' => [
                'borderStyle' => Border::BORDER_THICK,
                'color' => ['argb' => 'FFFFFFFF'],
            ],
        ],
    ];

    $headerStyleArray = [
        'font' => ['name' => 'Tahoma', 'bold' => true, 'size' => 11],
        'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
        'fill' => [
            'fillType' => Fill::FILL_SOLID,
            'startColor' => ['argb' => 'FFbada99'],
        ],
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
                'color' => ['argb' => 'FF000000'],
            ],
        ],
    ];

    $cellStyleArray = [
        'font' => ['name' => 'Tahoma', 'size' => 11],
        'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_LEFT,
            'vertical' => Alignment::VERTICAL_CENTER,
        ],
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
                'color' => ['argb' => 'FF000000'],
            ],
        ],
    ];

    // Set up the report title
    $sheet->mergeCells('A1:F1');
    $sheet->setCellValue('A1', 'Campaign Report');
    $sheet->getStyle('A1:F1')->applyFromArray($titleStyleArray);

    // Set up the campaign info
    $sheet->setCellValue('A2', 'Campaign Name');
    $sheet->setCellValue('B2', $info->title);
    $sheet->setCellValue('D2', 'Total Contacts');
    $sheet->setCellValue('E2', $info->schedulecontacts_count);
    
    // Apply styling to the campaign info row
    $sheet->getStyle('A2:B2')->applyFromArray($headerStyleArray);
    $sheet->getStyle('D2:E2')->applyFromArray($headerStyleArray);
    $sheet->mergeCells('B2:C2'); // Merge for campaign title
    $sheet->mergeCells('E2:F2'); // Merge for total contacts

    // Add empty row for spacing
    $sheet->setCellValue('A3', ''); // Creates an empty row for spacing

    // Set column headers for the report
    $sheet->setCellValue('A4', 'Campaign Name');
    $sheet->setCellValue('B4', 'Contact Name');
    $sheet->setCellValue('C4', 'Template Name');
    $sheet->setCellValue('D4', 'Delivery Date');
    $sheet->setCellValue('E4', 'Phone Number');
    $sheet->setCellValue('F4', 'Status');

    // Apply header styling
    $sheet->getStyle('A4:F4')->applyFromArray($headerStyleArray);

    // Fill in contact data
    $row = 5; // Start filling data from row 5
    foreach ($contacts as $contact) {
        $sheet->setCellValue('A' . $row, $info->title);
        $sheet->setCellValue('B' . $row, $contact->contact->name); // Contact name
        $sheet->setCellValue('C' . $row, $template->title ?? 'Not Available');
        $sheet->setCellValue('D' . $row, Carbon::parse($info->date)->format('d-F-Y')); // 
        $sheet->setCellValue('E' . $row, $contact->contact->phone); // Phone number
        
        // Status handling
        $status = $statuses->get($contact->contact->phone)->status ?? 'No Status';
        $sheet->setCellValue('F' . $row, $status);
        
        // Set status color based on success/failure
        $statusColor = $contact->status ? '009f19' : 'f00';
        $sheet->getStyle('F' . $row)->getFont()->getColor()->setARGB($statusColor);
        
        // Apply cell styling
        $sheet->getStyle('A' . $row . ':F' . $row)->applyFromArray($cellStyleArray);
        
        $row++;
    }

    // Auto size the columns
    foreach (range('A', 'F') as $col) {
        $sheet->getColumnDimension($col)->setWidth(30); // Set a fixed width for columns A to F
    }

    // Hide gridlines
    $sheet->setShowGridlines(false);

    // Set the print area to limit the visible cells
    $lastRow = $row - 1; // Get the last row used
    $sheet->getPageSetup()->setPrintArea('A1:F' . $lastRow);

    // Save the file and send it to the browser for download
    $writer = new Xls($spreadsheet);
    $fileName = 'campaign_report_' . $id . '.xls';

    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="' . $fileName . '"');
    header('Cache-Control: max-age=0');

    $writer->save('php://output');
    exit;
}

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $schedulemessage=Schedulemessage::where('user_id',Auth::id())->findorFail($id);
        
        
        $schedulemessage->delete();
        return response()->json([
            'message' => __('Schedule Deleted Successfully'),
            'redirect' => route('user.schedule-message.index')
        ]);

    }
}

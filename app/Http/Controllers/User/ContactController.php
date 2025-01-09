<?php
//new addons 1.1
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;
use App\Models\Template;
use App\Models\CloudApi;
use App\Models\User;
use App\Rules\Phone;
use App\Traits\Cloud;
use App\Models\Group;
use App\Models\Groupcontact;
use Auth;
use DB;
use Netflie\WhatsAppCloudApi\WhatsAppCloudApi;
use Netflie\WhatsAppCloudApi\Message\OptionsList\Row;
use Netflie\WhatsAppCloudApi\Message\OptionsList\Section;
use Netflie\WhatsAppCloudApi\Message\OptionsList\Action;
use Netflie\WhatsAppCloudApi\Message\Media\LinkID;
use Netflie\WhatsAppCloudApi\Message\Template\Component;

class ContactController extends Controller
{
    use Cloud;
    
    public function index()
    {
        $total_contacts=Contact::where('user_id',Auth::id())->count();
        $contacts=Contact::where('user_id',Auth::id())->with('groupcontact')->latest()->paginate(20);
        $templates=Template::where('user_id',Auth::id())->where('status',1)->latest()->get();
        $cloudapis = CloudApi::where('user_id', Auth::id())->where('status', 1)->latest()->get();
        $limit  = json_decode(Auth::user()->plan);
        $limit = $limit->contact_limit ?? 0;

        if ($limit == '-1') {
            $limit = number_format($total_contacts);
        }
        else{
            $limit = $total_contacts.' / '.$limit;
        }
        
        $groups = Group::where('user_id',Auth::id())->latest()->get();

        return view('user.contact.index', compact('contacts', 'total_contacts', 'templates', 'cloudapis', 'limit','groups'));
    }

    public function create()
    {
        $groups = Group::where('user_id',Auth::id())->latest()->get();
        return view('user.contact.create',compact('groups'));
    }

    public function store(Request $request)
    {
        if (getUserPlanData('contact_limit') == false) {
            return response()->json([
                'message'=>__('Maximum Contacts Limit Exceeded')
            ],401);  
        }

        $validated = $request->validate([
            'phone'   => ['required', new Phone],
            'name'=>['required','max:20'], 
        ]);

        $is_exist=Contact::where('user_id',Auth::id())->where('phone',$request->phone)->first();

        if (!empty($is_exist)) {
            return response()->json([
                 'message'  => __('Contact already exist..!!'),
                 'redirect' =>  route('user.contact.index')
             ], 401);
         }

         if ($request->group) {
            $group = Group::where('user_id',Auth::id())->findorFail($request->group);
        }

        $contact= new Contact;
        $contact->name = $request->name;
        $contact->phone = $request->phone;
        $contact->user_id = Auth::id();
        $paramsArray = explode(',', $request->param);
        for ($i = 1; $i <= 7; $i++) {
        $paramKey = 'param' . $i;
    
            // Check if the array has a value for the current iteration
            if (isset($paramsArray[$i - 1])) {
                $contact->$paramKey = trim($paramsArray[$i - 1]); // Trim to remove any extra spaces
                } else {
                    $contact->$paramKey = null; // Set to null if the value is not provided
                }
            }
        $contact->save();

        if ($request->group) {
            $contact->groupcontacts()->insert(['group_id'=>$request->group,'contact_id'=>$contact->id]);
            }

        return response()->json([
            'message' => __('New Contact Created Successfully'),
        ], 200);
    }

    public function edit($id)
    {
        $info=Contact::where('user_id',Auth::id())->findorFail($id);
        return view('user.contact.edit',compact('info'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'phone'   => ['required', new Phone],
            'name'=>['required','max:20'], 
        ]);

        $is_exist=Contact::where('user_id',Auth::id())->where('phone',$request->phone)->where('id','!=',$id)->first();
        if (!empty($is_exist)) {
           return response()->json([
                'message'  => __('Opps this contact number you have already added')
            ], 401);
        }

        $contact=  Contact::where('user_id',Auth::id())->findorFail($id);
        $contact->name = $request->name;
        $contact->phone = $request->phone;
        $contact->user_id = Auth::id();
        $paramsArray = explode(',', $request->param);
        for ($i = 1; $i <= 7; $i++) {
        $paramKey = 'param' . $i;
    
            // Check if the array has a value for the current iteration
            if (isset($paramsArray[$i - 1])) {
                $contact->$paramKey = trim($paramsArray[$i - 1]); // Trim to remove any extra spaces
                } else {
                    $contact->$paramKey = null; // Set to null if the value is not provided
                }
            }
        $contact->save();

        if ($request->group) {
            $group = Group::where('user_id',Auth::id())->findorFail($request->group);
            $contact->groupcontact()->sync([$request->group]);
        }

        return response()->json([
            'message' => __('Contact updated Successfully'),
            'redirect' => route('user.contact.index')
        ], 200);
    }

    public function destroy($id)
    {
        $contact=Contact::where('user_id',Auth::id())->findorFail($id);
        $contact->delete();

        return response()->json([
            'message'  => __('Contact deleted successfully..!!'),
            'redirect' =>  route('user.contact.index')
        ], 200);
    }

    public function sendtemplateBulk(Request $request)
{
    if (getUserPlanData('messages_limit') == false) {
        return response()->json([
            'message' => __('Maximum Monthly Messages Limit Exceeded')
        ], 401);  
    }

    $validated = $request->validate([
        'template' => ['required'], 
        'group'    => ['required'],
    ]);

    $group = Group::where('user_id', Auth::id())->whereHas('groupcontacts')->findOrFail($request->group);
    $cloudapi = CloudApi::where('user_id', Auth::id())->where('status', 1)->findOrFail($request->cloudapi);

    $headerParm = null;
    if ($request->header_param || $request->header_image) {
        $imageLink = null;

        if ($request->header_image) {
            $imageLink = $this->saveFile($request, 'header_image');
        }

        $headerParm = urlencode($request->header_param ?? $imageLink);
    }

    $body = [];
    if ($request->body_param) {
        foreach ($request->body_param as $bodyItem) {
            $body[] = urlencode($bodyItem);
        }
    }

    $queryParams = [
        'template' => $request->template,
        'groupid' => $group->id,
        'cloudapiid' => $cloudapi->uuid,
        'headerParm' => isset($headerParm) ? $headerParm : '',
        'body' => isset($body) ? implode(',', $body) : '',
    ];

    $redirectUrl = url('user/sent-bulk-with-template?' . http_build_query($queryParams));

    return response()->json([
        'message' => __('Redirecting to bulk sending page'),
        'redirect' => $redirectUrl
    ], 200);
}


    public function import(Request $request)
    {

        $validated = $request->validate([
            'file'   => 'required|mimes:csv,txt|max:100',
            
        ]);

        if (getUserPlanData('contact_limit') == false) {
            return response()->json([
                'message'=>__('Maximum Contacts Limit Exceeded')
            ],401);  
        }
        else{
            $contact_limit=json_decode(Auth::user()->plan);
            $contact_limit=$contact_limit->contact_limit;
        }


        if ($request->group) {
            $group = Group::where('user_id',Auth::id())->findorFail($request->group);
        }
        

        $file = $request->file('file');

        $insertable=[];

        // Open the CSV file
        if (($handle = fopen($file, 'r')) !== false) {
        // Read the header row
            $header = fgetcsv($handle);

        // Loop through the remaining rows
            while (($data = fgetcsv($handle)) !== false) {
            // Process the row data
            // ...

            // Example: Create a new record in the database
                $row=array(
                    'name'=>$data[0],
                    'phone'=>$data[1],
                    'param1'=>$data[2] ?? null,
                    'param2'=>$data[3] ?? null,
                    'param3'=>$data[4] ?? null,
                    'param4'=>$data[5] ?? null,
                    'param5'=>$data[6] ?? null,
                    'param6'=>$data[7] ?? null,
                    'param7'=>$data[8] ?? null,
                ); 
                array_push($insertable, $row);
                
            }

        // Close the CSV file
            fclose($handle);
           

        }

        $count_contacts=count($insertable);

        if ($contact_limit != -1) {
           $old_rows = Contact::where('user_id',Auth::id())->count();

           $available_rows = $contact_limit-$old_rows;
           


           if ($count_contacts > $available_rows) {
                return response()->json([
                    'message'=>__('Maximum '.$available_rows.' records are available only for create contact')
                ],401);  
           }
        }

        DB::beginTransaction();
        try {
            
            $insertableGroups=[];

              foreach ($insertable as $key => $row) {
                 $contact= new Contact;
                 $contact->name=$row['name'];
                 $contact->phone=$row['phone'];
                 $contact->param1= $row['param1'] ?? null;
                 $contact->param2= $row['param2'] ?? null;
                 $contact->param3= $row['param3'] ?? null;
                 $contact->param4= $row['param4'] ?? null;
                 $contact->param5= $row['param5'] ?? null;
                 $contact->param6= $row['param6'] ?? null;
                 $contact->param7= $row['param7'] ?? null;
                 $contact->user_id=Auth::id();
                 $contact->save();

                 $contactRow=array(
                    'contact_id'=>$contact->id,
                    'group_id'=>$request->group ?? null
                ); 
                 array_push($insertableGroups, $contactRow);

             }


             if ($request->group) {
                Groupcontact::insert($insertableGroups);
            }

            DB::commit();

        } catch (Throwable $th) {
            DB::rollback();

            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }

       
        
        return response()->json([
                'message'  => __('Contact list imported successfully'),
            ], 200);


    }
}
<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Template;
use App\Models\CloudApi;
use App\Models\User;
use App\Rules\Phone;
use App\Traits\Cloud;
use App\Models\Catalog;
use App\Models\Catalogproduct;
use Auth;
use DB;

class ProductsController extends Controller
{
    use Cloud;
    
    public function index()
    {
        $total_products=Product::where('user_id',Auth::id())->count();
        $products=Product::where('user_id',Auth::id())->with('catalogproduct')->latest()->paginate(20);
        $templates=Template::where('user_id',Auth::id())->where('status',1)->latest()->get();
        $cloudapis = CloudApi::where('user_id', Auth::id())->where('status', 1)->latest()->get();
        $limit  = json_decode(Auth::user()->plan);
        $limit = $limit->contact_limit ?? 0;

        if ($limit == '-1') {
            $limit = number_format($total_products);
        }
        else{
            $limit = $total_products.' / '.$limit;
        }
        
        $catalogs = Catalog::where('user_id',Auth::id())->latest()->get();

        return view('user.products.index', compact('products', 'total_products', 'templates', 'cloudapis', 'limit','catalogs'));
    }

    public function create()
    {
        $catalogs = Catalog::where('user_id',Auth::id())->latest()->get();
        return view('user.products.create',compact('catalogs'));
    }

    public function store(Request $request)
    {
        if (getUserPlanData('contact_limit') == false) {
            return response()->json([
                'message'=>__('Maximum Contacts Limit Exceeded')
            ],401);  
        }

        
    }

    public function edit($id)
    {
        
    }

    public function update(Request $request, $id)
    {
        
    }

    public function destroy($id)
    {
        
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
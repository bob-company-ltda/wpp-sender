<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Catalog;
use App\Models\Products;
use App\Models\Group;
use App\Models\CloudApi;
use Illuminate\Support\Facades\Http;
use Auth;
use DB;
class CatalogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cloudapis=CloudApi::where('user_id',Auth::id())->where('status',1)->latest()->get();
        $catalog =  Catalog::where('user_id',Auth::id())->withCount('catalogproducts')->latest()->paginate(20);
        $total_catalogs =  Catalog::where('user_id',Auth::id())->count();

        $limit  = json_decode(Auth::user()->plan);
        $limit = $limit->catalog_limit ?? 0;

        return view('user.catalog.index',compact('catalog','total_catalogs','limit', 'cloudapis'));
    }

   

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:200',
        ]);
        
        
        $apiUrl = 'https://graph.facebook.com/v20.0/285425047408487/owned_product_catalogs';
        $accessToken = 'EAAVFZBfnsFO8BOwZA7JdpzaEEerLQiY82XZC1oeZCFe6eZAJVtmJZBRjG6TuDWmJwRFwslbD28KmCkTk0YvvEEN9n8VwZBXwr5ZAENZBoX6UiCVxt1dfr0yZAXShurPSZCuwneRSOWKfKmlXiza0B4Lp1Y5y0FzsTRGqIvwiPZCGIQadtWk5KvJ0kvbHVYaEmgmNvanWX3BqvGNKUbat16EuVjkPJWQePcrZAZBzwptMFcmOFK';
        
        $response = Http::asForm()->post($apiUrl, [
        'name' => $request->name,
        'access_token' => $accessToken,
        ]);
        
        if ($response->successful()) {
        // Get the catalog ID from the API response
        $catalogId = $response->json('id');

        // Create and save the catalog in the database
        $catalog = new Catalog;
        $catalog->user_id = Auth::id();
        $catalog->name = $request->name;
        $catalog->uuid = $catalogId; // Use the ID returned from the API
        $catalog->save();

        return response()->json([
            'message' => __('Catalog Created Successfully'),
            'redirect' => route('user.catalog.index')
        ], 200);
        } else {
        // Handle API request failure
        return response()->json([
            'message' => __('Failed to create catalog on Facebook.'),
        ], 500);
        }
    }

   

    

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|max:200',
        ]);

        $group =  Group::where('user_id',Auth::id())->findorFail($id);
        $group->name = $request->name;
        $group->save();

        return response()->json([
                'message'  => __('Group Update Successfully'),
                'redirect' => route('user.group.index')
            ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
        DB::beginTransaction();
        try {
            
            $group =  Group::where('user_id',Auth::id())->with('groupcontacts')->findorFail($id);

            $contacts=[];

            foreach ($group->groupcontacts as $key => $row) {
                array_push($contacts, $row->contact_id);
            }

            $group->delete();

            Contact::whereIn('id',$contacts)->where('user_id',Auth::id())->delete();

            DB::commit();

        } catch (Throwable $th) {
            DB::rollback();

            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }


        return response()->json([
                'message'  => __('Group Deleted Successfully'),
                'redirect' => route('user.group.index')
            ], 200);
    }
    
    
    public function getcatalogs(Request $request) 
    {
        $cloudapi = CloudApi::findOrFail($request->cloudapi_id);
        if (!$cloudapi) {
            return response()->json(['error' => 'CloudApi not found'], 404);
        }
        
        $catalogs = Catalog::where('user_id', Auth::id())->latest()->get();
        
        $accessToken = 'EAAVFZBfnsFO8BOwZA7JdpzaEEerLQiY82XZC1oeZCFe6eZAJVtmJZBRjG6TuDWmJwRFwslbD28KmCkTk0YvvEEN9n8VwZBXwr5ZAENZBoX6UiCVxt1dfr0yZAXShurPSZCuwneRSOWKfKmlXiza0B4Lp1Y5y0FzsTRGqIvwiPZCGIQadtWk5KvJ0kvbHVYaEmgmNvanWX3BqvGNKUbat16EuVjkPJWQePcrZAZBzwptMFcmOFK';
        $businessId = '285425047408487';
        
        $response = $this->loadCatalogs($businessId, $accessToken);
        foreach ($response['data'] as $catalogData) {
             $uuid = $catalogData['id'];
    
    // Check if a template with the same UUID already exists
    $existingCatalog = Catalog::where('uuid', $uuid)->first();

    if ($existingCatalog) {
        // Skip if a template with the same UUID exists
        continue;
    }
    
        $catalog = new Catalog;
        $catalog->user_id = Auth::id();
        $catalog->name = $catalogData['name'];
        $catalog->uuid = $catalogData['id']; 
        $catalog->save();
    
    }
    }
    
    public function loadCatalogs($businessId, $accessToken, $after=""){
        $client = new \GuzzleHttp\Client();
        $url="https://graph.facebook.com/v20.0/{$businessId}/owned_product_catalogs";
        $queryParams = [
            'fields' => 'name',
            'limit' => 100
        ];
        if($after!=""){
            $queryParams['after']=$after;
        }

         $response = $client->get($url, [
    'headers' => [
        'Authorization' => 'Bearer ' . $accessToken,
    ],
    'query' => $queryParams,
]);

    

        // Handle the response here
        if ($response->getStatusCode() === 200) {
            $responseData = json_decode($response->getBody(), true);
            return $responseData;
        }else {
            // Handle error response
           return false;
        }
    }
}

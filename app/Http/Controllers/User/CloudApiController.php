<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CloudApi;
use App\Models\Reply;
use App\Models\Smstransaction;
use App\Models\Template;
use App\Models\User;
use App\Libraries\WhatsappLibrary;
use DB;
use Auth;
use Http;
use Session;
use Carbon\Carbon;
use App\Traits\Cloud;
use Illuminate\Support\Facades\Storage;
class CloudApiController extends Controller
{
    use Cloud;
    public $whatsapp_app_cloud_api;
    protected $user;
    
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = User::where('id', Auth::id())->first();

            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $cloudapis = CloudApi::where('user_id', $this->user->id)->orWhere('user_id', $this->user->team_id)->get();
        foreach ($cloudapis as $cloudapi) {
        $transactionCount = Smstransaction::where('user_id', Auth::id())
            ->where('cloudapi_id', $cloudapi->id)
            ->count();
        // Add the count as a property to the CloudApi model
        $cloudapi->smstransaction_count = $transactionCount;
    }
        return view('user.cloudapi.index',compact('cloudapis'));
    }

    /**
     * return cloudapi statics informations
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function cloudapiStatics()
    {
        
       $data['total']=CloudApi::where('user_id', $this->user->id)->orWhere('user_id', $this->user->team_id)->count();
       $data['active']=CloudApi::where('user_id', $this->user->id)->orWhere('user_id', $this->user->team_id)->where('status',1)->count();
       $data['inActive']=CloudApi::where('user_id', $this->user->id)->orWhere('user_id', $this->user->team_id)->where('status',0)->count();
       $limit  = json_decode(Auth::user()->plan);
       $limit = $limit->cloudapi_limit ?? 0;

       if ($limit == '-1') {
           $data['total']= $data['total'];
       }
       else{
         $data['total']= $data['total'].' / '. $limit;
       }
       
       
       return response()->json($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!$this->user->team_id){
        $users = $this->user;
        return view('user.cloudapi.create',compact('users'));
        }else{
             return response()->json(['message'=>__('You are not allowed to create')]);
        }
    }

    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    
       
        if (getUserPlanData('cloudapi_limit') == false) {
            return response()->json([
                'message'=>__('Maximum CloudApi Limit Exceeded')
            ],401);  
        }

        $validated = $request->validate([
            'name' => 'required|max:100',
            'webhook_url' => 'nullable|url|max:100',
        ]);

        $cloudapi=new CloudApi;
        $cloudapi->user_id=Auth::id();
        $cloudapi->phone = $request->phone;
        $cloudapi->name=$request->name;
        $cloudapi->phone_number_id = $request->phone_number_id;
        $cloudapi->wa_business_id = $request->wa_business_id;
        $cloudapi->meta_app_id = $request->meta_app_id;
        $cloudapi->access_token = $request->access_token;
        $cloudapi->hook_url=$request->webhook_url;
        $cloudapi->save();

        return response()->json([
            'redirect'=>url('user/cloudapi/'.$cloudapi->uuid.'/cloud'),
            'message'=>__('CloudApi Created Successfully')
        ],200);
    }

    public function cloudApi($id)
    {
        if(!$this->user->team_id){
        $cloudapi=CloudApi::where('user_id',Auth::id())->where('uuid',$id)->first();
        abort_if(empty($cloudapi),404);
    
    
    
        if($cloudapi->name == 'Embedded Signup'){
            customFunction('EmbeddedSignup', 'callbackurl', $cloudapi);
        }
        return view('user.cloudapi.hook',compact('cloudapi'));
        }else{
             return response()->json(['message'=>__('You are not allowed to see')]);
        }

    }

    public function checkSession($id)
{
    $cloudapi = CloudApi::where('user_id', Auth::id())->where('uuid', $id)->first();
    abort_if(empty($cloudapi), 404);

    if ($cloudapi->status == 1) {
        $message = 'CloudApi Connected Successfully';
        return response()->json(['message' => $message]);
    }
}

public function embeddedSignup(Request $request){
    $response= customFunction('EmbeddedSignup', 'embeddedsignup', null, $request);
    if ($response->status() === 200) {
        return response()->json(['status' => 'success']);
    }else {
        return response()->json(['error' => 'Error']);
    }
}

public function codeExchange(Request $request){
    $response= customFunction('EmbeddedSignup', 'tokencode', null,  $request);
    
    if ($response->status() === 200) {
        return response()->json([
            'redirect' => url('user/cloudapi/' . $response->cloud_id . '/cloud'),
            'message' => __('CloudApi Created Successfully')
        ], 200);
    }
    return response()->json(['status' => 'error', 'message' => 'Failed to create CloudApi'], 500);
}

    public function setStatus($cloudapi_id,$status)
    {

       $cloudapi_id=str_replace('cloudapi_','',$cloudapi_id);

       $cloudapi=CloudApi::where('id',$cloudapi_id)->first();
       if (!empty($cloudapi)) {
          $cloudapi->status=$status;
          $cloudapi->save();
       }


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       if(!$this->user->team_id){
        $cloudapi=CloudApi::where('user_id',Auth::id())->where('uuid',$id)->first();
        abort_if(empty($cloudapi),404);

        $posts=Smstransaction::where('user_id',Auth::id())->where('cloudapi_id',$cloudapi->id)->latest()->paginate();
        $totalUsed=Smstransaction::where('user_id',Auth::id())->where('cloudapi_id',$cloudapi->id)->count();
        $todaysMessage=Smstransaction::where('user_id',Auth::id())->where('cloudapi_id',$cloudapi->id)->whereDate('created_at',Carbon::today())->count();
        $monthlyMessages=Smstransaction::where('user_id',Auth::id())
                        ->where('cloudapi_id',$cloudapi->id)
                        ->where('created_at', '>', now()->subDays(30)->endOfDay())
                        ->count();


        return view('user.cloudapi.show',compact('cloudapi','posts','totalUsed','todaysMessage','monthlyMessages'));
       }else{
           return response()->json(['message'=>__('You are not allowed to see')]);
       }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(!$this->user->team_id){
        $cloudapi=CloudApi::where('user_id',Auth::id())->where('uuid',$id)->first();
        abort_if(empty($cloudapi),404);
        $whatsapp_app_cloud_api = new WhatsappLibrary();
        $accessToken = $cloudapi->access_token;
        $phoneNumberId = $cloudapi->phone_number_id;
        $response = $whatsapp_app_cloud_api->fetchProfile($phoneNumberId,$accessToken);
        //dd($response['data'][0]);
        return view('user.cloudapi.edit',compact('cloudapi', 'response'));
        }else{
            return response()->json(['message'=>__('You are not allowed to see')]);
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
        'name' => 'required|max:100',
        //'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Add validation for the image field
    ]);

    $cloudapi = CloudApi::where('user_id', Auth::id())->where('uuid', $id)->first();
    abort_if(empty($cloudapi), 404);
    
    if($request->access_token){
         $cloudapi->access_token = $request->access_token;
         $cloudapi->save();
    }

    $whatsapp_app_cloud_api = new WhatsappLibrary();
    $accessToken = $cloudapi->access_token;
    $phoneNumberId = $cloudapi->phone_number_id;
    $appId = $cloudapi->meta_app_id;
    $cloudapi->name = $request->name;
    $cloudapi->about = $request->about;
    $cloudapi->address = $request->address;
    $cloudapi->description = $request->description;
    $cloudapi->industry = $request->industry;
    $cloudapi->email = $request->email;
    $cloudapi->website = $request->website;

    $profileData = [
        'messaging_product' => 'whatsapp',
        'about' => $request->about,
        'address' => $request->address,
        'description' => $request->description,
        'vertical' => $request->industry,
        'email' => $request->email,
        'websites' => [
            $request->website,
        ],
    ];

    if ($request->hasFile('image')) {
        $file = $request->file('image');
        $filePath = $file->getPathname();
        $handleResult = $whatsapp_app_cloud_api->uploadProfilePicture($filePath, $appId, $accessToken);
       $profileData['profile_picture_handle'] = $handleResult;
    }
    try{
    $response=$whatsapp_app_cloud_api->updateProfile($profileData, $phoneNumberId, $accessToken);
    
    $reflector = new \ReflectionClass($response);

    // Access http_response_code property
    $httpResponseCodeProperty = $reflector->getProperty('http_response_code');
    $httpResponseCodeProperty->setAccessible(true);
    $responseCode = $httpResponseCodeProperty->getValue($response);

    // Access body property
    $bodyProperty = $reflector->getProperty('body');
    $bodyProperty->setAccessible(true);
    $responseBody = $bodyProperty->getValue($response);
    
    
    if ($responseCode != 200) {
        $errorBody = json_decode($responseBody, true);
        $errorMessage = $errorBody['error']['message'] ?? 'Unknown error';


        return response()->json([
            'message' => $errorMessage,
        ], 500);
    }
    }catch(\Exception $exception){
                $errorMessage = $exception->getMessage();
                preg_match('/\"message\":\"(.*?)\"/', $errorMessage, $matches);
                $extractedMessage = isset($matches[1]) ? $matches[1] : 'Unknown error';
                return response()->json([
                                    'message' => $extractedMessage,
                                ], 400);
                }
    $cloudapi->phone_number_id = $request->phone_number_id;
    $cloudapi->wa_business_id = $request->wa_business_id;
    $cloudapi->meta_app_id = $request->meta_app_id;
    $cloudapi->access_token = $request->access_token;
    $cloudapi->hook_url=$request->webhook_url;
    $cloudapi->save();
    return response()->json([
        'redirect' => route('user.cloudapi.index'),
        'message' => __('CloudApi Updated Successfully'),
        
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
        if(!$this->user->team_id){
        $cloudapi=CloudApi::where('user_id',Auth::id())->where('uuid',$id)->first();
        abort_if(empty($cloudapi),404);
        
        if($cloudapi->name == 'Embedded Signup'){
            customFunction('EmbeddedSignup', 'remove', $cloudapi);
        }
        
        $cloudapi->delete();
        
        

        return response()->json([
            'message' => __('Congratulations! Your CloudApi Successfully Removed'),
            'redirect' => route('user.cloudapi.index')
        ]);
        }else{
            return response()->json(['message'=>__('You are not allowed to remove')]);
        }
       
    }
}

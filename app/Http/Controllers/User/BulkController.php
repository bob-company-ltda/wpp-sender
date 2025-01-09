<?php

namespace App\Http\Controllers\User;

use Netflie\WhatsAppCloudApi\WhatsAppCloudApi;
use Netflie\WhatsAppCloudApi\Message\OptionsList\Row;
use Netflie\WhatsAppCloudApi\Message\OptionsList\Section;
use Netflie\WhatsAppCloudApi\Message\OptionsList\Action;
use Netflie\WhatsAppCloudApi\Message\Media\LinkID;
use Netflie\WhatsAppCloudApi\Message\Media\MediaObjectID;
use Netflie\WhatsAppCloudApi\Message\Template\Component;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Jobs\SendMessageJob;
use App\Models\Smstransaction;
use App\Models\Smstesttransactions;
use App\Http\Requests\Bulkrequest;
use App\Models\ChatMessage;
use App\Models\BulkmessageTask;
use App\Models\User;
use App\Models\Option;
use App\Models\App;
use App\Models\CloudApi;
use App\Models\Contact;
use App\Models\Template;
use App\Models\Group;
use Carbon\Carbon;
use App\Traits\Cloud;
use Http;
use Auth;
use Str;
use DB;
class BulkController extends Controller
{
    use Cloud;
    public $whatsapp_app_cloud_api;
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts=Smstransaction::where('user_id',Auth::id())->with('cloudapi')->with('template')->where('type','bulk-message')->latest()->paginate(20);
        $total=Smstransaction::where('user_id',Auth::id())->where('type','bulk-message')->count();
        $today_transaction=Smstransaction::where('user_id',Auth::id())
                           ->where('type','bulk-message')
                           ->whereRaw('date(created_at) = ?', [Carbon::now()->format('Y-m-d')] )
                           ->count();
        $last30_messages=Smstransaction::where('user_id',Auth::id())
                           ->where('type','bulk-message')
                           ->where('created_at', '>', now()
                           ->subDays(30)
                           ->endOfDay())
                           ->count();
                                              
        $cloudapis=CloudApi::where('user_id',Auth::id())->where('status',1)->latest()->get();
        $templates=Template::where('user_id',Auth::id())->where('status',1)->latest()->get();
        $groups=Group::where('user_id',Auth::id())->whereHas('groupcontacts')->latest()->get();

        return view('user.whatsapp.bulk.index',compact('posts','total','today_transaction','last30_messages','cloudapis','templates','groups'));
    }

    public function create()
    {
        $cloudapis=CloudApi::where('user_id',Auth::id())->where('status',1)->latest()->get();
        $groups=Group::where('user_id',Auth::id())->with('contacts')->whereHas('contacts')->latest()->get();

        return view('user.whatsapp.bulk.multiple',compact('cloudapis','groups'));
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
            'phone'   => 'required|numeric',
            'message' => 'required|max:1000',
            'cloudapi' => 'required',
        ]);

        
        $cloudapi=CloudApi::where('user_id',Auth::id())->where('status',1)->findorFail($request->cloudapi);
        $phone=str_replace('+', '', $request->phone);
        $contact=Contact::where('user_id',Auth::id())->where('phone', $phone)->first();
        $user=User::where('id',Auth::id())->first();
        $userChat = ChatMessage::where('phone_number', $phone)->where('cloudapi_id', $cloudapi->id)->first();
        $whatsapp_app_cloud_api = new WhatsAppCloudApi([
            'from_phone_number_id' => $cloudapi->phone_number_id,
            'access_token' => $cloudapi->access_token,
        ]);
        
        $message = $this->formatText($request['message'], $contact, $user);
        try {
            $response = $whatsapp_app_cloud_api->sendTextMessage($phone, $message);
            $status = customFunction('ReadReceipt', 'status', $response);
                
               if ($status->status() === 200 && getUserPlanData('read_receipt') === true) {
                    $wamid = $status->getData(true)['wamid'];
                }else{
                    $wamid = uniqid('chat_', true);
                }
            $logs['user_id']=Auth::id();
           $logs['cloudapi_id']=$cloudapi->id;
           $logs['from']=$cloudapi->phone ?? null;
           $logs['to']=$phone;
           $logs['type']='bulk-message';
           $logs['wamid'] = $wamid;
           $this->saveLog($logs);
           $this->saveMessageToUserChat($wamid,$userChat,$request['message'],'plain-text', $phone, $cloudapi->id);

           return response()->json([
                    'message' => __('Message sent successfully..!!'),
                ], 200);
        } catch(\Netflie\WhatsAppCloudApi\Response\ResponseException $e){
                        $errorDetails = $e->getMessage(); // This gets the JSON string
                        $errorObject = json_decode($errorDetails); // Decode into an object
    
                        // Now, access the nested message property
                        $errorMessage = isset($errorObject->error->message) ? $errorObject->error->message : 'An unknown error occurred';
                        return response()->json([
                            'message' => $errorMessage
                                        ],500);
                    }
    }


    //creating record
    public function createTransaction($arr)
    {
       $trans=new Smstransaction;
       foreach ($arr as $key => $value) {
           $trans->$key=$value;
       }
       $trans->save();

       return $trans;
    }
    
    public function saveMessageToUserChat($id='', $userChat, $templateName, $type, $request_from, $templateCloudId)
{
    if ($userChat) {
        $userChatMessages = json_decode($userChat->message_history, true) ?? [];
        $chatID = uniqid('chat_', true);
        $newMessage = [
            'chatID' => $id,
            'message' => '['.$type.']:'. $templateName,
            'timestamp' => now()->toDateTimeString(),
            'status' => 'sent',
            'type' => 'sent',
        ];

        $userChatMessages[] = $newMessage;

        // Update the message history in the database
        $userChat->message_history = json_encode($userChatMessages);
        $userChat->save();
    }else{
        $newUserChat = new ChatMessage();
        $newUserChat->phone_number = $request_from;
        $newUserChat->cloudapi_id = $templateCloudId;
        $newUserChat->message_history = json_encode([
        [
            'chatID' => $id,
            'message' => '['.$type.']:'. $templateName,
            'timestamp' => now()->toDateTimeString(),
            'status' => 'sent',
            'type' => 'sent',
        ]
    ]);
    $newUserChat->save();

    }
}

    public function submitRequest(Bulkrequest $request)
    {
       
      
        $user=User::where('status',1)->where('authkey',$request->authkey)->first();

        $app=App::where('key',$request->appkey)->whereHas('cloudapi')->with('cloudapi')->where('status',1)->first();
        

        
       
        if ($user == null || $app == null) {
            return response()->json(['error'=>'Invalid Auth and AppKey'],401);
        }

        if (getUserPlanData('messages_limit') == false) {
            return response()->json([
                'message'=>__('Maximum Monthly Messages Limit Exceeded')
            ],401);  
        }
        
        if (!empty($request->template_id)) {
            $template = Template::where('user_id',$user->id)->where('uuid',$request->template_id)->where('status',1)->first();
            if (empty($template)) {
                return response()->json(['error'=>'Template Not Found'],401);
            }

            if (isset($template->body)) {
                $body = $template->body;
            }
            else{
                return response()->json(['error'=>'Template Not Found'],401);
            }
            $type = $template->type;

            if($type == 'plain-text'){
                $data = $body;
                $desc = $body['text'];
                try{
                $response= $whatsapp_app_cloud_api->sendTextMessage($request->to, $desc);
                $status = customFunction('ReadReceipt', 'status', $response);
                
               if ($status->status() === 200 && getUserPlanData('read_receipt') === true) {
                    $wamid = $status->getData(true)['wamid'];
                }else{
                    $wamid = uniqid('chat_', true);
                }
                $logs['user_id']=Auth::id();
                $logs['cloudapi_id']=$app->cloudapi_id;
                $logs['from']=$app->cloudapi->phone ?? null;
                $logs['to']=$request->to;
                $logs['type']='bulk-message';
                $logs['wamid'] = $wamid;
                $this->saveLog($logs);
                return response()->json(['message_status'=>'Success','data'=>[
                    'from'=>$app->cloudapi->phone ?? null,
                    'to'=>$request->to,                
                    'status_code'=>200,
                ]],200);
                }catch(\Netflie\WhatsAppCloudApi\Response\ResponseException $e){
                        $errorDetails = $e->getMessage(); // This gets the JSON string
                        $errorObject = json_decode($errorDetails); // Decode into an object
    
                        // Now, access the nested message property
                        $errorMessage = isset($errorObject->error->message) ? $errorObject->error->message : 'An unknown error occurred';
                        return response()->json([
                            'message' => $errorMessage
                                        ],500);
                    }
            }

            if($template->type == 'text-with-list'){
                $data = $body;

                // Extract the required information
                $title = $data['title'];
                $text = $data['text'];
                $footer = $data['footer'];
                $sectionsData = $data['sections'];

                $rows = [];
                $a=0;
                foreach ($sectionsData[0]['rows'] as $row) {
                    $rowId = $a;
                    $title = $row['title'];
                    $description = $row['description'] ?? null;
                    $rows[] = new Row($rowId, $title, $description);
                    $a++;
                }

                $sections = [new Section($sectionsData[0]['title'], $rows)];
                $action = new Action($data['buttonText'], $sections);

                // Send the list message using the transformed data
                try{
                $response=$whatsapp_app_cloud_api->sendList(
                $request->to,
                $data['title'],
                $data['text'],
                $data['footer'],
                $action
                );
                $status = customFunction('ReadReceipt', 'status', $response);
                
               if ($status->status() === 200 && getUserPlanData('read_receipt') === true) {
                    $wamid = $status->getData(true)['wamid'];
                }else{
                    $wamid = uniqid('chat_', true);
                }
                $logs['user_id']=Auth::id();
            $logs['cloudapi_id']=$app->cloudapi_id;
            $logs['from']=$app->cloudapi->phone ?? null;
            $logs['to']=$request->to;
            $logs['type']='bulk-message';
            $logs['wamid'] = $wamid;
            $this->saveLog($logs);

                return response()->json(['message_status'=>'Success','data'=>[
                    'from'=>$app->cloudapi->phone ?? null,
                    'to'=>$request->to,                
                    'status_code'=>200,
                ]],200);

                }catch(\Netflie\WhatsAppCloudApi\Response\ResponseException $e){
                        $errorDetails = $e->getMessage(); // This gets the JSON string
                        $errorObject = json_decode($errorDetails); // Decode into an object
    
                        // Now, access the nested message property
                        $errorMessage = isset($errorObject->error->message) ? $errorObject->error->message : 'An unknown error occurred';
                        return response()->json([
                            'message' => $errorMessage
                                        ],500);
                    }
            }
            if($template->type == 'text-with-media'){
                $data = $body;

                if (isset($data['image']) && !empty($data['image']['url'])) {
                    $link_id = new LinkID($data['image']['url']);
                    try{
                    $response = $whatsapp_app_cloud_api->sendImage($request->to, $link_id);
                    $status = customFunction('ReadReceipt', 'status', $response);
                
               if ($status->status() === 200 && getUserPlanData('read_receipt') === true) {
                    $wamid = $status->getData(true)['wamid'];
                }else{
                    $wamid = uniqid('chat_', true);
                }
                    $logs['user_id']=Auth::id();
                    $logs['cloudapi_id']=$app->cloudapi_id;
                    $logs['from']=$app->cloudapi->phone ?? null;
                    $logs['to']=$request->to;
                    $logs['type']='bulk-message';
                    $logs['wamid'] = $wamid;
                    $this->saveLog($logs);
                    return response()->json(['message_status'=>'Success','data'=>[
                        'from'=>$app->cloudapi->phone ?? null,
                        'to'=>$request->to,                
                        'status_code'=>200,
                    ]],200);
                    }catch(\Netflie\WhatsAppCloudApi\Response\ResponseException $e){
                        $errorDetails = $e->getMessage(); // This gets the JSON string
                        $errorObject = json_decode($errorDetails); // Decode into an object
    
                        // Now, access the nested message property
                        $errorMessage = isset($errorObject->error->message) ? $errorObject->error->message : 'An unknown error occurred';
                        return response()->json([
                            'message' => $errorMessage
                                        ],500);
                    }
                }
                elseif (isset($data['document']) && !empty($data['document']['url'])) {
                    $document_caption = $data['caption'];
                    $document_url = $data['document']['url'];
                    $document_name = basename($document_url);
                    $link_id = new LinkID($document_url);
                    try{
                    $response=$whatsapp_app_cloud_api->sendDocument($request->to, $link_id, $document_name);
                    $status = customFunction('ReadReceipt', 'status', $response);
                
               if ($status->status() === 200 && getUserPlanData('read_receipt') === true) {
                    $wamid = $status->getData(true)['wamid'];
                }else{
                    $wamid = uniqid('chat_', true);
                }
                    $logs['user_id']=Auth::id();
                    $logs['cloudapi_id']=$app->cloudapi_id;
                    $logs['from']=$app->cloudapi->phone ?? null;
                    $logs['to']=$request->to;
                    $logs['type']='bulk-message';
                    $logs['wamid'] = $wamid;
                    $this->saveLog($logs);
                    return response()->json(['message_status'=>'Success','data'=>[
                        'from'=>$app->cloudapi->phone ?? null,
                        'to'=>$request->to,                
                        'status_code'=>200,
                    ]],200);

                    }catch(\Netflie\WhatsAppCloudApi\Response\ResponseException $e){
                        $errorDetails = $e->getMessage(); // This gets the JSON string
                        $errorObject = json_decode($errorDetails); // Decode into an object
    
                        // Now, access the nested message property
                        $errorMessage = isset($errorObject->error->message) ? $errorObject->error->message : 'An unknown error occurred';
                        return response()->json([
                            'message' => $errorMessage
                                        ],500);
                    }
                }
                

            }

            if($template->type == 'text-with-location'){
                $data = $body;
                $latitude = $data['location']['degreesLatitude'];
                $longitude= $data['location']['degreesLongitude'];
                try{
                $response=$whatsapp_app_cloud_api->sendLocation($request->to, $longitude, $latitude, '', $template->title);
                $status = customFunction('ReadReceipt', 'status', $response);
                
               if ($status->status() === 200 && getUserPlanData('read_receipt') === true) {
                    $wamid = $status->getData(true)['wamid'];
                }else{
                    $wamid = uniqid('chat_', true);
                }
                $logs['user_id']=Auth::id();
                    $logs['cloudapi_id']=$app->cloudapi_id;
                    $logs['from']=$app->cloudapi->phone ?? null;
                    $logs['to']=$request->to;
                    $logs['type']='bulk-message';
                    $logs['wamid'] = $wamid;
                    $this->saveLog($logs);
                return response()->json(['message_status'=>'Success','data'=>[
                    'from'=>$app->cloudapi->phone ?? null,
                    'to'=>$request->to,                
                    'status_code'=>200,
                ]],200);
                }catch(\Netflie\WhatsAppCloudApi\Response\ResponseException $e){
                        $errorDetails = $e->getMessage(); // This gets the JSON string
                        $errorObject = json_decode($errorDetails); // Decode into an object
    
                        // Now, access the nested message property
                        $errorMessage = isset($errorObject->error->message) ? $errorObject->error->message : 'An unknown error occurred';
                        return response()->json([
                            'message' => $errorMessage
                                        ],500);
                    }
            }
            if($template->type == 'meta-template'){
                
                $wallet = customFunction('WalletSystem', 'CheckCredit', $user);
                
                if ($wallet->status() === 200 && getUserPlanData('wallet_system') === true) {
                   return response()->json([
                    'message'=>__('Credit Exhausted, Please Recharge Your Wallet.')
                ],401);
                }
                
                $templateName = $body['name'];
               if (!empty($body['components'][0]['example']['header_text'][0]) || !empty($body['components'][0]['example']['body_text'][0][0])){
                    return response()->json([
                        'message' => __('Dynamic Template Found, Bulk Message not supported for this Item'),
                    ], 401);
                } else {
                    try{
                    $response = $whatsapp_app_cloud_api-> sendTemplate($request->to, $templateName, $body['language']);
                    $status = customFunction('ReadReceipt', 'status', $response);
                
               if ($status->status() === 200 && getUserPlanData('read_receipt') === true) {
                    $wamid = $status->getData(true)['wamid'];
                }else{
                    $wamid = uniqid('chat_', true);
                }
                    $logs['user_id']=Auth::id();
                    $logs['cloudapi_id']=$app->cloudapi_id;
                    $logs['from']=$app->cloudapi->phone ?? null;
                    $logs['to']=$request->to;
                    $logs['type']='bulk-message';
                    $logs['wamid'] = $wamid;
                    $this->saveLog($logs);
                     if (getUserPlanData('wallet_system') === true){
                            customFunction('WalletSystem', 'CreditRate', $user);
                       }
                
                    return response()->json(['message_status'=>'Success','data'=>[
                    'from'=>$app->cloudapi->phone ?? null,
                    'to'=>$request->to,                
                    'status_code'=>200,
                ]],200);
                    }catch(\Netflie\WhatsAppCloudApi\Response\ResponseException $e){
                        $errorDetails = $e->getMessage(); // This gets the JSON string
                        $errorObject = json_decode($errorDetails); // Decode into an object
    
                        // Now, access the nested message property
                        $errorMessage = isset($errorObject->error->message) ? $errorObject->error->message : 'An unknown error occurred';
                        return response()->json([
                            'message' => $errorMessage
                                        ],500);
                    }
                }
            }
        }
        else{
            $text=$this->formatText($request->message);
            $body['text'] = $text;
            $whatsapp_app_cloud_api->sendTextMessage($request->to, $body['text']);
            return response()->json(['message_status'=>'Success','data'=>[
                'from'=>$app->cloudapi->phone ?? null,
                'to'=>$request->to,                
                'status_code'=>200,
            ]],200);
        }

        if (!isset($body)) {
            return response()->json(['error'=>'Request Failed'],401);
        }    
       
    }

    public function sendBulkToContacts(Request $request)
{
    $id = $request->query('template');
    $group_id = $request->query('groupid');
    $cloudapi_id = $request->query('cloudapiid');
    $headerParm = urldecode($request->query('headerParm', ''));
    $body = $request->query('body', '');

    $template = Template::where('user_id', Auth::id())->findOrFail($id);

    $contacts = Contact::where('user_id', Auth::id())->whereHas('groupcontacts', function($q) use ($group_id) {
        return $q->where('group_id', $group_id);
    })->get();

    $templates = Template::where('user_id', Auth::id())->where('status', 1)->latest()->get();
    $cloudapi = CloudApi::where('user_id', Auth::id())->where('status', 1)->where('uuid', $cloudapi_id)->first();
    $cloudapis = CloudApi::where('user_id', Auth::id())->where('status', 1)->latest()->get();

    abort_if(empty($cloudapi), 404);

    return view('user.template.bulk', compact('template', 'templates', 'contacts', 'cloudapi', 'cloudapis', 'headerParm', 'body'));
}


    
    
    

    public function sendMessageToContact(Request $request)
{
    if (getUserPlanData('messages_limit') == false) {
        return response()->json([
            'message' => __('Maximum Monthly Messages Limit Exceeded')
        ], 401);  
    }

    $validated = $request->validate([
        'contact' => ['required'],
        'template' => ['required'], 
    ]);

    $template = Template::where('user_id', Auth::id())->where('status', 1)->findOrFail($request->template);
    $cloudApi = CloudApi::where('user_id', Auth::id())->where('status', 1)->findOrFail($request->cloudapi);
    $contact = Contact::where('user_id', Auth::id())->findOrFail($request->contact);
    $userId = Auth::id();
    $saveToChat = $request->savetochat;
    $replacedHeaderParm = $request->headerParam;
    $replacedBody = $request->body;

    SendMessageJob::dispatch($contact, $template->id, $cloudApi->id, $userId, $saveToChat, $replacedHeaderParm, $replacedBody);

    return response()->json(['message_status' => 'Queued for sending Campaign. You can track at Bulk Transaction'], 200);
}
   

    public function templateWithMessage()
    {
       
        $templates=Template::where('user_id',Auth::id())->where('status',1)->latest()->get();
        $contacts=Contact::where('user_id',Auth::id())->latest()->get();
        $cloudapis=CloudApi::where('user_id',Auth::id())->where('status',1)->latest()->get();

        return view('user.template.template',compact('templates','contacts','cloudapis'));
    }


}

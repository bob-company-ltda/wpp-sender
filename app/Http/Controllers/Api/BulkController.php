<?php

namespace App\Http\Controllers\Api;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Smstransaction;
use App\Jobs\UpdateMessageStatus;
use App\Http\Requests\Bulkrequest;
use Netflie\WhatsAppCloudApi\WhatsAppCloudApi;
use Netflie\WhatsAppCloudApi\Message\OptionsList\Row;
use Netflie\WhatsAppCloudApi\Message\OptionsList\Section;
use Netflie\WhatsAppCloudApi\Message\OptionsList\Action;
use Netflie\WhatsAppCloudApi\Message\Media\LinkID;
use Netflie\WhatsAppCloudApi\Message\Media\MediaObjectID;
use Netflie\WhatsAppCloudApi\Message\Template\Component;
use Netflie\WhatsAppCloudApi\Message\ButtonReply\Button;
use Netflie\WhatsAppCloudApi\Message\ButtonReply\ButtonAction;
use App\Libraries\WhatsappLibrary;
use App\Models\User;
use App\Models\ChatMessage;
use App\Models\App;
use App\Models\CloudApi;
use App\Models\Option;
use App\Models\Contact;
use App\Models\Group;
use App\Models\Groupcontact;
use App\Models\Template;
use App\Models\Reply;
use Carbon\Carbon;
use App\Traits\Cloud;
use App\Models\Notification;
use App\Traits\Notifications;
use App\Models\Webhook;
use Http;
use Auth;
use Str;
use DB;
use Session;
class BulkController extends Controller
{
    use Cloud;
    use Notifications;
    public $whatsapp_app_cloud_api;

    
    /**
     * sent message
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function submitRequest(Bulkrequest $request)
    {

        
        $user=User::where('status',1)->where('will_expire','>',now())->where('authkey',$request->authkey)->first();
        $app=App::where('key',$request->appkey)->whereHas('cloudapi')->with('cloudapi')->where('status',1)->first();
        $cloudapi = CloudApi::where('id', $app->cloudapi_id)->where('status',1)->first();
    $whatsapp_app_cloud_api = new WhatsAppCloudApi([
        'from_phone_number_id' => $cloudapi->phone_number_id,
        'access_token' => $cloudapi->access_token,
    ]);

        if ($user == null || $app == null) {
            return response()->json(['error'=>'Invalid Auth and AppKey'],401);
        }

        if (getUserPlanData('messages_limit', $user->id) == false) {
            return response()->json([
                'message'=>__('Maximum Monthly Messages Limit Exceeded')
            ],401);  
        }
        
        

        if (!empty($request->template_id)) {

            $template = Template::where('user_id',$user->id)->where('uuid',$request->template_id)->where('status',1)->first();
            
            if (empty($template)) {
                return response()->json(['error'=>'Template Not Found'],401);
            }

            if (isset($template->body['text'])) {
                $body = $template->body;
                $text=$this->formatText($template->body['text'],[],$user);
                $text=$this->formatCustomText($text,$request->variables ?? []);
                $body['text'] = $text;
            }
            else{
                
                $body=$template->body;
            }
            $type = $template->type;
            $body = $template->body;
            
            if($type == 'plain-text'){
                
                $text = $this->formatCustomText($body['text'],$request->variables ?? []);
                try {
                $response = $whatsapp_app_cloud_api->sendTextMessage($request->to,$text,true);
                 $logs['user_id']=Auth::id();
                 $logs['user_id']=$user->id;
                 $logs['cloudapi_id']=$app->cloudapi_id;
                 $logs['app_id']=$app->id;
                 $logs['from']=$app->cloudapi->phone ?? null;
                 $logs['to']=$request->to;
                 $logs['template_id']=$template->id ?? null;
                 $logs['type']='from_api';
                       $this->saveLog($logs);
                       
                       return response()->json([
                                'message' => __('Message sent successfully..!!'),
                            ], 200);
            
            } catch (\Netflie\WhatsAppCloudApi\Response\ResponseException $e) {
            return response()->json([
                                'message' => $e->rawResponse(),
                            ], $e->httpStatusCode());
            }
            }

            if($type=='text-with-image'){
                $link_id = new LinkID($body['image']['url']);
                try {
                    $response = $whatsapp_app_cloud_api->sendImage($request->to, $link_id , $body['caption']);
                     $logs['user_id']=Auth::id();
                     $logs['user_id']=$user->id;
                     $logs['cloudapi_id']=$app->cloudapi_id;
                     $logs['app_id']=$app->id;
                     $logs['from']=$app->cloudapi->phone ?? null;
                     $logs['to']=$request->to;
                     $logs['template_id']=$template->id ?? null;
                     $logs['type']='from_api';
                           $this->saveLog($logs);
                           
                           return response()->json([
                                    'message' => __('Message sent successfully..!!'),
                                ], 200);
                
                } catch (\Netflie\WhatsAppCloudApi\Response\ResponseException $e) {
                return response()->json([
                                    'message' => $e->rawResponse(),
                                ], $e->httpStatusCode());
                }
            }
            elseif($type=='text-with-list'){
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

                try {
                    $response = $whatsapp_app_cloud_api->sendList(
                        $request->to,
                        $data['title'],
                        $data['text'],
                        $data['footer'],
                        $action
                        );
                     $logs['user_id']=Auth::id();
                     $logs['user_id']=$user->id;
                     $logs['cloudapi_id']=$app->cloudapi_id;
                     $logs['app_id']=$app->id;
                     $logs['from']=$app->cloudapi->phone ?? null;
                     $logs['to']=$request->to;
                     $logs['template_id']=$template->id ?? null;
                     $logs['type']='from_api';
                           $this->saveLog($logs);
                           
                           return response()->json([
                                    'message' => __('Message sent successfully..!!'),
                                ], 200);
                
                } catch (\Netflie\WhatsAppCloudApi\Response\ResponseException $e) {
                return response()->json([
                                    'message' => $e->rawResponse(),
                                ], $e->httpStatusCode());
                }
            }elseif($type == 'text-with-media'){
                $data = $body;

                if (isset($data['image']) && !empty($data['image']['url'])) {
                    $link_id = new LinkID($data['image']['url']);
                    $response=$whatsapp_app_cloud_api->sendImage($request->to, $link_id);
                }
                elseif (isset($data['document']) && !empty($data['document']['url'])) {
                    $document_caption = $data['caption'];
                    $document_url = $data['document']['url'];
                    $document_name = basename($document_url);
                    $link_id = new LinkID($document_url);
                    try {
                    $response =$whatsapp_app_cloud_api->sendDocument($request->to, $link_id, $document_name);
                    $logs['user_id']=Auth::id();
                     $logs['user_id']=$user->id;
                     $logs['cloudapi_id']=$app->cloudapi_id;
                     $logs['app_id']=$app->id;
                     $logs['from']=$app->cloudapi->phone ?? null;
                     $logs['to']=$request->to;
                     $logs['template_id']=$template->id ?? null;
                     $logs['type']='from_api';
                           $this->saveLog($logs);
                           
                           return response()->json([
                                    'message' => __('Message sent successfully..!!'),
                                ], 200);
                    }catch (\Netflie\WhatsAppCloudApi\Response\ResponseException $e) {
                        return response()->json([
                                            'message' => $e->rawResponse(),
                                        ], $e->httpStatusCode());
                        }
                } 

            }elseif($type == 'text-with-location'){
                $data = $body;
                $latitude = $data['location']['degreesLatitude'];
                $longitude= $data['location']['degreesLongitude'];
                try {
                $response=$whatsapp_app_cloud_api->sendLocation($request->to, $longitude, $latitude, '', $template->title);
                $logs['user_id']=Auth::id();
                     $logs['user_id']=$user->id;
                     $logs['cloudapi_id']=$app->cloudapi_id;
                     $logs['app_id']=$app->id;
                     $logs['from']=$app->cloudapi->phone ?? null;
                     $logs['to']=$request->to;
                     $logs['template_id']=$template->id ?? null;
                     $logs['type']='from_api';
                           $this->saveLog($logs);
                           
                           return response()->json([
                                    'message' => __('Message sent successfully..!!'),
                                ], 200);
                    }catch (\Netflie\WhatsAppCloudApi\Response\ResponseException $e) {
                        return response()->json([
                                            'message' => $e->rawResponse(),
                                        ], $e->httpStatusCode());
                        }
            }
            elseif($type == 'meta-template'){
                
                $wallet = customFunction('WalletSystem', 'CheckCredit', $user);
                
                if ($wallet->status() === 200 && getUserPlanData('wallet_system', $user->id) === true) {
                   return response()->json([
                    'message'=>__('Credit Exhausted, Please Recharge Your Wallet.')
                ],401);
                }
                
                $templateName = $body['name'];
               if (!empty($body['components'][0]['example']['header_text'][0]) || !empty($body['components'][0]['example']['body_text'][0][0]) || !empty($body['components'][0]['example']['header_handle'][0])) {
                   
                    $component_header = [];
                    $component_body = [];
                    $component_buttons = [];
                    $templateName = $body['name'];
                    if ($body['components'][0]['type'] === 'HEADER' && $body['components'][0]['format'] === 'TEXT'  ) {
                                    if (!empty($request->header )) {
                                        
                                        $component_header = [];
                                        
                                        $componentHeader = [
                                        'type' => 'text',
                                        'text' => $request->header,
                                    ];
                                    $component_header[] = $componentHeader;
                                
                            }
                        }
                    elseif($body['components'][0]['type'] === 'HEADER' && $body['components'][0]['format'] === 'IMAGE'  ){
                            
                                        
                                        $componentHeader = [
                                        'type' => 'image',
                                        'image' => [
                                            'link' => $request->header,
                                            ],
                                    ];
                                    $component_header[] = $componentHeader;
                        }elseif($body['components'][0]['type'] === 'HEADER' && $body['components'][0]['format'] === 'DOCUMENT'  ){
                            $componentHeader = [
                                        'type' => 'document',
                                        'document' => [
                                            'link' => $request->header,
                                            ],
                                    ];
                                    $component_header[] = $componentHeader;
                        }
                        else{
                            
                                    $component_header = [];
                            }
                            if ($body['components'][0]['type'] === 'BODY' || $body['components'][1]['type'] === 'BODY'  ) {
                            if (!empty($request->body)){
                            $bodyString = $request->body;
                            $bodyArray = explode(',', $bodyString);
                            
                            $component_body = [];
                            

                            foreach ($bodyArray as $value) {
                                
                            $componentBody = [
                            'type' => 'text',
                            'text' => $value,
                            ];

                            $component_body[] = $componentBody;
                        }
                    }}
                    $components = new Component($component_header, $component_body, $component_buttons);
                    try{
                    $response= $whatsapp_app_cloud_api->sendTemplate($request->to, $templateName, $body['language'], $components);
                    $logs['user_id']=Auth::id();
                     $logs['user_id']=$user->id;
                     $logs['cloudapi_id']=$app->cloudapi_id;
                     $logs['app_id']=$app->id;
                     $logs['from']=$app->cloudapi->phone ?? null;
                     $logs['to']=$request->to;
                     $logs['template_id']=$template->id ?? null;
                     $logs['type']='from_api';
                           $this->saveLog($logs);
                           if (getUserPlanData('wallet_system', $user->id) === true){
                            customFunction('WalletSystem', 'CreditRate', $user);
                       }
                           
                           return response()->json([
                                    'message' => __('Message sent successfully..!!'),
                                ], 200);
                    }catch (\Netflie\WhatsAppCloudApi\Response\ResponseException $e) {
                        return response()->json([
                                            'message' => $e->rawResponse(),
                                        ], $e->httpStatusCode());
                        }
                } else {
                    try{
                    $whatsapp_app_cloud_api-> sendTemplate($request->to, $templateName, $body['language']);
                    $logs['user_id']=Auth::id();
                     $logs['user_id']=$user->id;
                     $logs['cloudapi_id']=$app->cloudapi_id;
                     $logs['app_id']=$app->id;
                     $logs['from']=$app->cloudapi->phone ?? null;
                     $logs['to']=$request->to;
                     $logs['template_id']=$template->id ?? null;
                     $logs['type']='from_api';
                           $this->saveLog($logs);
                           if (getUserPlanData('wallet_system', $user->id) === true){
                            customFunction('WalletSystem', 'CreditRate', $user);
                       }
                           
                           return response()->json([
                                    'message' => __('Message sent successfully..!!'),
                                ], 200);
                    }catch (\Netflie\WhatsAppCloudApi\Response\ResponseException $e) {
                        return response()->json([
                                            'message' => $e->rawResponse(),
                                        ], $e->httpStatusCode());
                        }
                }
            }
            
        }
        
        else if($request->media_link){
                $document_caption = $request->message;
                    $document_url = $request->media_link;
                    $document_name = 'attachments';
                    $link_id = new LinkID($document_url);
                    try {
                    $response =$whatsapp_app_cloud_api->sendDocument($request->to, $link_id, $document_name, $document_caption);
                    $logs['user_id']=Auth::id();
                     $logs['user_id']=$user->id;
                     $logs['cloudapi_id']=$app->cloudapi_id;
                     $logs['app_id']=$app->id;
                     $logs['from']=$app->cloudapi->phone ?? null;
                     $logs['to']=$request->to;
                     $logs['template_id']=$template->id ?? null;
                     $logs['type']='from_api';
                           $this->saveLog($logs);
                           
                           return response()->json([
                                    'message' => __('Message sent successfully..!!'),
                                ], 200);
                    }catch (\Netflie\WhatsAppCloudApi\Response\ResponseException $e) {
                        return response()->json([
                                            'message' => $e->rawResponse(),
                                        ], $e->httpStatusCode());
                        }
            }
            
        else{
            
            $text=$this->formatText($request->message);
            
            $body['text'] = $text;
            $type='plain-text';
            
            try {
                $response = $whatsapp_app_cloud_api->sendTextMessage($request->to,$text,true);
                 $logs['user_id']=Auth::id();
                 $logs['user_id']=$user->id;
                 $logs['cloudapi_id']=$app->cloudapi_id;
                 $logs['app_id']=$app->id;
                 $logs['from']=$app->cloudapi->phone ?? null;
                 $logs['to']=$request->to;
                 $logs['template_id']=$template->id ?? null;
                 $logs['type']='from_api';
                       $this->saveLog($logs);
                       
                       return response()->json([
                                'message' => __('Message sent successfully..!!'),
                            ], 200);
            
            } catch (\Netflie\WhatsAppCloudApi\Response\ResponseException $e) {
            return response()->json([
                                'message' => $e->rawResponse(),
                            ], $e->httpStatusCode());
            }

        }

        if (!isset($body)) {
            return response()->json(['error'=>'Request Failed'],401);
        }    

 }


 /**
  * set status cloudapi
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
  public function setStatus($cloudapi_id,$status){

       $cloudapi_id=str_replace('cloudapi_','',$cloudapi_id);

       $cloudapi=CloudApi::where('id',$cloudapi_id)->first();
       if (!empty($cloudapi)) {
          $cloudapi->status=$status;
          $cloudapi->save();
       }


  }
  
  
  public function saveMessageToUserChat($userChat, $message, $type,$id='')
{
    if ($userChat) {
        $userChatMessages = json_decode($userChat->message_history, true) ?? [];
        //$chatID = uniqid('chat_', true);
        $newMessage = [
            'chatID' => $id,
            'message' => $message,
            'timestamp' => now()->toDateTimeString(),
            'status' => 'sent',
            'type' => $type,
        ];

        $userChatMessages[] = $newMessage;

        // Update the message history in the database
        $userChat->message_history = json_encode($userChatMessages);
        $userChat->save();
    }
}


  /**
  * receive webhook response
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
  public function webHook(Request $request,$cloudapi_id){
    $session=$cloudapi_id;
    $cloudapi_id=str_replace('cloudapi_','',$cloudapi_id);
    $cloudapi=CloudApi::with('user')->whereHas('user',function($query){
        return $query->where('will_expire','>',now());
       })->where('uuid',$cloudapi_id)->first();

       $cloudapis = CloudApi::with('user')
    ->where('uuid', $cloudapi_id)
    ->first();

    $verifyToken = '123456';
    $payload = $request->all();
    Log::info('User_ID:' . $cloudapi_id);
    Log::info('Received Webhook Payload from API: ' . json_encode($payload));
    
    

    if (isset($payload['hub_mode']) && $payload['hub_mode'] === 'subscribe') {
        if ($payload['hub_verify_token'] === $verifyToken) {
            $cloudapi->status = 1;
            $cloudapi->save();
            return $payload['hub_challenge'];

        } else {
            $cloudapi->status = 0;
            $cloudapi->save();
            return response('Invalid verify token.', 403);
        }
    }
    

   try{
       
    if (isset($payload['entry'][0]['changes'][0]['value']['messages'])) {
        $value = $payload['entry'][0]['changes'][0]['value'];
        $name = $value['contacts'][0]['profile']['name'] ?? null;
        $phone = $value['messages'][0]['from'] ?? null;
        $messageEntry = $payload['entry'][0]['changes'][0]['value']['messages'][0];
        
        if ($name !== null && $phone !== null) {
        $contact = Contact::where('phone', $phone)->where('user_id', $cloudapi->user_id)->first();

        if (!$contact) {
             $contact= new Contact;
             $contact->name = $name;
             $contact->phone = $phone;
             $contact->user_id = $cloudapi->user_id;
             $contact->save();
        }
    }

        if($messageEntry['type'] == 'text'){
            $message = $messageEntry['text']['body'];
            $message_id = $messageEntry['id'];
        }
        elseif($messageEntry['type'] == 'interactive'){
            $message = $messageEntry['interactive']['list_reply']['title'];
            $message_id = $messageEntry['id'];
        }elseif ($messageEntry['type'] == 'button') {
            $message = $messageEntry['button']['text'];
            $message_id = $messageEntry['id'];
        }
        elseif($messageEntry['type'] == 'image'){
            $whatsapp_app_cloud_api = new WhatsappLibrary();
            $media_id = $messageEntry['image']['id'];
            $access_token = $cloudapis->access_token;
            $caption = $messageEntry['image']['caption'] ?? null;
            $image_url = $whatsapp_app_cloud_api->retrieveUrl($media_id, $access_token);
            $message = $image_url . "\n" . ($caption ? "Caption: $caption" : "");
            $message_id = $messageEntry['id'];

        }
        elseif($messageEntry['type'] == 'audio'){
            $whatsapp_app_cloud_api = new WhatsappLibrary();
            $media_id = $messageEntry['audio']['id'];
            $access_token = $cloudapis->access_token;
            $message = $whatsapp_app_cloud_api->retrieveUrl($media_id,$access_token);
            $message_id = $messageEntry['id'];
        }
        elseif($messageEntry['type'] == 'document'){
            $whatsapp_app_cloud_api = new WhatsappLibrary();
            $media_id = $messageEntry['document']['id'];
            $access_token = $cloudapis->access_token;
            $message = $whatsapp_app_cloud_api->retrieveUrl($media_id,$access_token);
            $message_id = $messageEntry['id'];
        }
        elseif($messageEntry['type'] == 'video'){
            $whatsapp_app_cloud_api = new WhatsappLibrary();
            $media_id = $messageEntry['video']['id'];
            $access_token = $cloudapis->access_token;
            $message = $whatsapp_app_cloud_api->retrieveUrl($media_id,$access_token);
            $message_id = $messageEntry['id'];
        }else{
            $messageEntry = null;
        }
        
        if($cloudapi->hook_url && getUserPlanData('webhooks_payload', $cloudapi->user_id) === true){
            $hook['message'] = $message;
          $hook['phone'] = $phone;
          $details = new Request($hook);
          customFunction('Webhooks', 'Hooks', $cloudapi, $details);
        }
        
    http_response_code(200);
    echo 'recieved successfully';
    }
    else if(isset($payload['entry'][0]['changes'][0]['value']['statuses'][0])){
        $statuses = $payload['entry'][0]['changes'][0]['value']['statuses'][0];
        
        Smstransaction::where('wamid', $statuses['id'])
        ->update(['status' => $statuses['status']]);
        
    $request_from = $statuses['recipient_id'];
    $userChat = ChatMessage::where('phone_number', $request_from)
    ->where('cloudapi_id', $cloudapi->id)
    ->first();
    
    if ($userChat) {
    $messageHistory = json_decode($userChat->message_history, true) ?? [];

    // Get the last 20 messages from the message history
    $last20Messages = array_slice($messageHistory, -20, null, true);

    // Iterate over the last 20 messages
    foreach ($last20Messages as $index => $message) {
        $messageId = $message['chatID'];

        // Check if the message ID matches the upcoming payload ID
        if ($messageId === $statuses['id']) {
            // Update the status in the matched message
            $message['status'] = $statuses['status'];

            // Update the message history in the database
            $messageHistory[$index] = $message;
            $userChat->update(['message_history' => json_encode($messageHistory)]);

            return Log::info('Updated ' . $message['type']);
        }
    }

    // Handle the case where no message IDs match
    return Log::info('No matching message IDs found');
}

    }else{
        return response()->json(['error' => 'No messages found'], 400);
    }
   
    if(!empty($messageEntry) && $message_id){
    
    $request_from = $messageEntry['from'];
    $userChat = ChatMessage::where('phone_number', $request_from)
    ->where('cloudapi_id', $cloudapi->id)
    ->first();
       
       $whatsapp_app_cloud_api = new WhatsAppCloudApi([
        'from_phone_number_id' => $cloudapis->phone_number_id,
        'access_token' => $cloudapis->access_token,
    ]);
    
        $notification['user_id']   = $cloudapis->user_id;
        $notification['url']       = '/user/cloudapi/chats/'.$cloudapi->uuid;

        

    if ($userChat) {
        $messageHistory = json_decode($userChat->message_history, true) ?? [];
        $chatID = $message_id;
        $newMessage = [
            'chatID' => $chatID,
            'message' => $message,
            'timestamp' => now()->toDateTimeString(),
            'type' => 'received',
            'status' => 'delivered',
        ];

        $messageHistory[] = $newMessage;
        

        // Update the message history in the database
        $userChat->message_history = json_encode($messageHistory);
        $userChat->counts = $userChat->counts + 1;
        $userChat->save();
        if($userChat->notification == 0){
        $this->createNotificationForMessage($notification);
        }

    }else{
        $chatID = $message_id;
        $newUserChat = new ChatMessage();
        $newUserChat->phone_number = $request_from;
        $newUserChat->cloudapi_id = $cloudapi->id;
        $newUserChat->message_history = json_encode([
        [
            'chatID' => $chatID,
            'message' => $message,
            'timestamp' => now()->toDateTimeString(),
            'type' => 'received',
            'status' => 'delivered'
        ]
    ]);
    $newUserChat->counts = 1;
    $newUserChat->save();
    $this->createNotificationForMessage($notification);

    }
    }
}catch(Exception $e){
       return Log::info('No Valid Payload Found');
   }

       

      
       
       $cloudapi_ids=$cloudapi->id;

       

      if (isset($message)) {
       if ($cloudapi != null && $message != null) {
          $replies=Reply::where('cloudapi_id',$cloudapi_ids)->with('template')->where('keyword','LIKE','%'.$message.'%')->latest()->get();
          $default = Reply::where('cloudapi_id',$cloudapi_ids)->with('template')->where('keyword','default')->latest()->get();
          
          $user = User::where('id', $cloudapi->user_id)->first();
          
          $gpt['message'] = $message;
          $gpt['from'] = $request_from;
          $details = new Request($gpt);
          $status = customFunction('ChatGPTReply', 'OpenAi', $cloudapi, $details);
          
          if ($status->status() === 200) {
              return response()->json([
        'message' => 'Operation successful'
    ], 200);
          }else{

          $messageMatched = false;

          foreach ($replies as $key => $reply) {
            if ($reply->match_type == 'equal') {

                if ($reply->reply_type == 'text') {
                  
                  $logs['user_id']=$cloudapi->user_id;
                  $logs['cloudapi_id']=$cloudapi->id;
                  $logs['from']=$cloudapi->phone ?? null;
                  $logs['to']=$request_from;
                  $logs['type']='chatbot';
                  $this->saveLog($logs);
                 
                $response= $whatsapp_app_cloud_api->sendTextMessage($request_from, $reply->reply);
                $messageMatched = true;
                
                $status = customFunction('ReadReceipt', 'status', $response);
                
                if ($status->status() === 200 && getUserPlanData('read_receipt', $cloudapi->user_id) === true) {
                    $wamid = $status->getData(true)['wamid'];
                }else{
                    $wamid = uniqid('chat_', true);
                }
                $this->saveMessageToUserChat($userChat, $reply->reply, 'sent', $wamid);
                }
                else{
                    if (!empty($reply->template)) {
                        $template = $reply->template;
                        $body=$template->body;

                        $logs['user_id']=$cloudapi->user_id;
                        $logs['cloudapi_id']=$cloudapi->id;
                        $logs['from']=$cloudapi->phone ?? null;
                        $logs['to']=$request_from;
                        $logs['type']='chatbot';
                        $logs['template_id']=$template->id ?? null;
                        $this->saveLog($logs);


                        if($template->type == 'plain-text'){
                            $data = $body;
                            $desc = $body['text'];
                            $response=$whatsapp_app_cloud_api->sendTextMessage($request_from, $desc);
                            $messageMatched = true;
                            $status = customFunction('ReadReceipt', 'status', $response);
                
                            if ($status->status() === 200 && getUserPlanData('read_receipt', $cloudapi->user_id) === true) {
                                $wamid = $status->getData(true)['wamid'];
                            }else{
                                $wamid = uniqid('chat_', true);
                            }
                            $this->saveMessageToUserChat($userChat, $desc, 'sent', $wamid);
                        }
                        if($template->type == 'text-with-image'){
                            $data = $body;
                            $link_id = new LinkID($data['image']['url']);
                            $response = $whatsapp_app_cloud_api->sendImage($request_from, $link_id , $data['caption']);
                            $messageMatched = true;
                            $status = customFunction('ReadReceipt', 'status', $response);
                
                            if ($status->status() === 200 && getUserPlanData('read_receipt', $cloudapi->user_id) === true) {
                                $wamid = $status->getData(true)['wamid'];
                            }else{
                                $wamid = uniqid('chat_', true);
                            }
                            $this->saveMessageToUserChat($userChat, 'Media Messages-'.$data['caption'], 'sent', $wamid);
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
                            $response=$whatsapp_app_cloud_api->sendList(
                            $request_from,
                            $data['title'],
                            $data['text'],
                            $data['footer'],
                            $action
                            );
                            $messageMatched = true;
                            $status = customFunction('ReadReceipt', 'status', $response);
                
                            if ($status->status() === 200 && getUserPlanData('read_receipt', $cloudapi->user_id) === true) {
                                $wamid = $status->getData(true)['wamid'];
                            }else{
                                $wamid = uniqid('chat_', true);
                            }
                            $this->saveMessageToUserChat($userChat, 'List Messages-'.$data['title'], 'sent', $wamid);

                            return response()->json([
                                'message' => 'Webhook received successfully'
                            ], 200);
                            
                        }
                        if($template->type == 'meta-template'){
                            $wallet = customFunction('WalletSystem', 'CheckCredit', $user);
                
                if ($wallet->status() === 200 && getUserPlanData('wallet_system', $user->id) === true) {
                   return response()->json([
                    'message'=>__('Credit Exhausted, Please Recharge Your Wallet.')
                ],401);
                }
            
                            $templateName = $body['name'];
                            //
                            if (!empty($body['components'][0]['example']['header_text'][0]) || !empty($body['components'][0]['example']['body_text'][0][0]) || !empty($body['components'][0]['example']['header_handle'][0])) {
                                
                                $component_header = [];
                                $component_body = [];
                                $component_buttons = [];
                                $templateName = $body['name'];
                                $parameters = $reply->parameters; // Decode JSON as an associative array
                                $parametersData = json_decode($parameters, true);
                                 
                                if ($body['components'][0]['type'] === 'HEADER' && $body['components'][0]['format'] === 'TEXT'  ) {
                                    if (!empty($parametersData['header_parameters'])) {
                                        
                                        $component_header = [];
                                        
                                        $componentHeader = [
                                        'type' => 'text',
                                        'text' => $parametersData['header_parameters'],
                                    ];
                                    $component_header[] = $componentHeader;
                                
                            }
                        } elseif($body['components'][0]['type'] === 'HEADER' && $body['components'][0]['format'] === 'IMAGE'  ){
                            
                                        
                                        $componentHeader = [
                                        'type' => 'image',
                                        'image' => [
                                            'link' => $parametersData['header_parameters'] ?? $body['components'][0]['example']['header_handle'][0],
                                            ],
                                    ];
                                    $component_header[] = $componentHeader;
                        }elseif($body['components'][0]['type'] === 'HEADER' && $body['components'][0]['format'] === 'VIDEO'  ){
                            
                                        
                                        $componentHeader = [
                                        'type' => 'video',
                                        'video' => [
                                            'link' => $parametersData['header_parameters'] ?? $body['components'][0]['example']['header_handle'][0],
                                            ],
                                    ];
                                    $component_header[] = $componentHeader;
                        }elseif($body['components'][0]['type'] === 'HEADER' && $body['components'][0]['format'] === 'DOCUMENT'  ){
                            $componentHeader = [
                                        'type' => 'document',
                                        'document' => [
                                            'link' => $parametersData['header_parameters'] ?? $body['components'][0]['example']['header_handle'][0],
                                            ],
                                    ];
                                    $component_header[] = $componentHeader;
                        }
                        else{
                            
                                    $component_header = [];
                            }
                            // Assuming $reply->parameters contains the JSON data
                            
                            
                            if ($body['components'][0]['type'] === 'BODY' || $body['components'][1]['type'] === 'BODY'  ) {
                            if (!empty($parametersData['message_parameters'])) {
                            Log::info('akkadbakaadnuum: ' . $parametersData['message_parameters'][0]);
                            $component_body = [];

                            foreach ($parametersData['message_parameters'] as $value) {
                                
                            $componentBody = [
                            'type' => 'text',
                            'text' => $value,
                            ];

                            $component_body[] = $componentBody;
                        }
                    }}
                    $components = new Component($component_header, $component_body, $component_buttons);
                    $response= $whatsapp_app_cloud_api->sendTemplate($request_from, $templateName, $body['language'], $components);
                    $messageMatched = true;
                    $status = customFunction('ReadReceipt', 'status', $response);
                
                            if ($status->status() === 200 && getUserPlanData('read_receipt', $cloudapi->user_id) === true){
                                $wamid = $status->getData(true)['wamid'];
                            }else{
                                $wamid = uniqid('chat_', true);
                            }
                    $this->saveMessageToUserChat($userChat, 'Meta Messages-'.$templateName, 'sent', $wamid);
                    
                    if (getUserPlanData('wallet_system', $user->id) === true){
                            customFunction('WalletSystem', 'CreditRate', $user);
                       }
                    return response()->json([
                        'message' => 'Webhook received successfully'
                    ], 200);
                            } else {
                                $response=$whatsapp_app_cloud_api-> sendTemplate($request_from, $templateName, $body['language']);
                                $status = customFunction('ReadReceipt', 'status', $response);
                
                            if ($status->status() === 200 && getUserPlanData('read_receipt', $cloudapi->user_id) === true) {
                                $wamid = $status->getData(true)['wamid'];
                            }else{
                                $wamid = uniqid('chat_', true);
                            }
                                $this->saveMessageToUserChat($userChat, 'Template Messages-'.$templateName, 'sent', $wamid);
                                if (getUserPlanData('wallet_system', $user->id) === true){
                                    customFunction('WalletSystem', 'CreditRate', $user);
                               }
                                return response()->json([
                                    'message' => 'Webhook received successfully'
                                ], 200);
                            }
                        }
                        if($template->type == 'text-with-button'){
                            $data = $body;
                            $message = $body['text'];
                            $row = [];
                            foreach ($body['buttons'] as $button) {
                                $id = $button['buttonId'];
                                $label = $button['buttonText']['displayText'];
                                $buttonObj = new Button($id, $label);
                                $rows[] = $buttonObj;
                            }
                            $action = new ButtonAction($rows);
                            $response = $whatsapp_app_cloud_api->sendButton($request_from,$message,$action, $body['header_text'] ?? null, $body['footer_text'] ?? null);
                            $messageMatched = true;
                            $status = customFunction('ReadReceipt', 'status', $response);
                
                            if ($status->status() === 200 && getUserPlanData('read_receipt', $cloudapi->user_id) === true) {
                                $wamid = $status->getData(true)['wamid'];
                            }else{
                                $wamid = uniqid('chat_', true);
                            }
                            $this->saveMessageToUserChat($userChat, 'Button Messages-'.$template->title, 'sent', $wamid);
                        }
                        
                        if($template->type == 'text-with-media'){
                            $data = $body;

                            if (isset($data['image']) && !empty($data['image']['url'])) {
                                $link_id = new LinkID($data['image']['url']);
                                $response=$whatsapp_app_cloud_api->sendImage($request_from, $link_id);
                                $messageMatched = true;
                                $status = customFunction('ReadReceipt', 'status', $response);
                
                            if ($status->status() === 200 && getUserPlanData('read_receipt', $cloudapi->user_id) === true){
                                $wamid = $status->getData(true)['wamid'];
                            }else{
                                $wamid = uniqid('chat_', true);
                            }
                                $this->saveMessageToUserChat($userChat, 'Media Messages', 'sent', $wamid);
                            }
                            elseif (isset($data['document']) && !empty($data['document']['url'])) {
                                $document_caption = $data['caption'];
                                $document_url = $data['document']['url'];
                                $document_name = basename($document_url);
                                $link_id = new LinkID($document_url);
                                $response=$whatsapp_app_cloud_api->sendDocument($request_from, $link_id, $document_name);
                                $messageMatched = true;
                                $status = customFunction('ReadReceipt', 'status', $response);
                
                            if ($status->status() === 200 && getUserPlanData('read_receipt', $cloudapi->user_id) === true) {
                                $wamid = $status->getData(true)['wamid'];
                            }else{
                                $wamid = uniqid('chat_', true);
                            }
                                $this->saveMessageToUserChat($userChat, 'Doc Messages-'.$document_name, 'sent', $wamid);
                            }
                            

                        }

                        if($template->type == 'text-with-location'){
                            $data = $body;
                            $latitude = $data['location']['degreesLatitude'];
                            $longitude= $data['location']['degreesLongitude'];
                            $response=$whatsapp_app_cloud_api->sendLocation($request_from, $longitude, $latitude, '', $template->title);
                            $messageMatched = true;
                            $status = customFunction('ReadReceipt', 'status', $response);
                
                            if ($status->status() === 200 && getUserPlanData('read_receipt', $cloudapi->user_id) === true) {
                                $wamid = $status->getData(true)['wamid'];
                            }else{
                                $wamid = uniqid('chat_', true);
                            }
                            $this->saveMessageToUserChat($userChat, 'Location Messages-'.$template->title, 'sent', $wamid);
                        }


                         
                    }                    
                }
                break;                
            }
          }

          if (!$messageMatched) {
            foreach ($default as $key => $reply){
                if ($reply->reply_type == 'text') {
                  
                    $logs['user_id']=$cloudapi->user_id;
                    $logs['cloudapi_id']=$cloudapi->id;
                    $logs['from']=$cloudapi->phone ?? null;
                    $logs['to']=$request_from;
                    $logs['type']='chatbot';
                    $this->saveLog($logs);
                   
                  $response=$whatsapp_app_cloud_api->sendTextMessage($request_from, $reply->reply);
                $status = customFunction('ReadReceipt', 'status', $response);
                
                            if ($status->status() === 200 && getUserPlanData('read_receipt', $cloudapi->user_id) === true) {
                                $wamid = $status->getData(true)['wamid'];
                            }else{
                                $wamid = uniqid('chat_', true);
                            }
                $this->saveMessageToUserChat($userChat, $reply->reply, 'sent', $wamid);
                  }
                  else{
                      if (!empty($reply->template)) {
                          $template = $reply->template;
                          $body=$template->body;
  
                          $logs['user_id']=$cloudapi->user_id;
                          $logs['cloudapi_id']=$cloudapi->id;
                          $logs['from']=$cloudapi->phone ?? null;
                          $logs['to']=$request_from;
                          $logs['type']='chatbot';
                          $logs['template_id']=$template->id ?? null;
                          $this->saveLog($logs);
  
  
                          if($template->type == 'plain-text'){
                              $data = $body;
                              $desc = $body['text'];
                              $response=$whatsapp_app_cloud_api->sendTextMessage($request_from, $desc);
                              $status = customFunction('ReadReceipt', 'status', $response);
                
                            if ($status->status() === 200 && getUserPlanData('read_receipt', $cloudapi->user_id) === true){
                                $wamid = $status->getData(true)['wamid'];
                            }else{
                                $wamid = uniqid('chat_', true);
                            }
                              $this->saveMessageToUserChat($userChat, $desc, 'sent', $wamid);
                              
                          }
                          if($template->type == 'text-with-image'){
                              $data = $body;
                              $link_id = new LinkID($data['image']['url']);
                              $response = $whatsapp_app_cloud_api->sendImage($request_from, $link_id , $data['caption']);
                              $status = customFunction('ReadReceipt', 'status', $response);
                
                            if ($status->status() === 200 && getUserPlanData('read_receipt', $cloudapi->user_id) === true){
                                $wamid = $status->getData(true)['wamid'];
                            }else{
                                $wamid = uniqid('chat_', true);
                            }
                              $this->saveMessageToUserChat($userChat, 'Media Messages-'.$data['caption'], 'sent', $wamid);
                              
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
                              $response=$whatsapp_app_cloud_api->sendList(
                              $request_from,
                              $data['title'],
                              $data['text'],
                              $data['footer'],
                              $action
                              );
                              
                              $status = customFunction('ReadReceipt', 'status', $response);
                
                            if ($status->status() === 200 && getUserPlanData('read_receipt', $cloudapi->user_id) === true) {
                                $wamid = $status->getData(true)['wamid'];
                            }else{
                                $wamid = uniqid('chat_', true);
                            }
                              $this->saveMessageToUserChat($userChat, 'List Messages-'.$data['title'], 'sent', $wamid);
                              
  
                              return response()->json([
                                  'message' => 'Webhook received successfully'
                              ], 200);
                              
                          }
                          if($template->type == 'meta-template'){
                              $wallet = customFunction('WalletSystem', 'CheckCredit', $user);
                
                if ($wallet->status() === 200 && getUserPlanData('wallet_system', $user->id) === true) {
                   return response()->json([
                    'message'=>__('Credit Exhausted, Please Recharge Your Wallet.')
                ],401);
                }
                            $templateName = $body['name'];
                            //
                            
                            
                            
                            if (!empty($body['components'][0]['example']['header_text'][0]) || !empty($body['components'][0]['example']['body_text'][0][0]) || !empty($body['components'][0]['example']['header_handle'][0])) {
                                
                                $component_header = [];
                                $component_body = [];
                                $component_buttons = [];
                                $templateName = $body['name'];
                                $parameters = $reply->parameters; // Decode JSON as an associative array
                                $parametersData = json_decode($parameters, true);
                                
                                 
                                if ($body['components'][0]['type'] === 'HEADER' && $body['components'][0]['format'] === 'TEXT'  ) {
                                    if (!empty($parametersData['header_parameters'])) {
                                        
                                        $component_header = [];
                                        
                                        $componentHeader = [
                                        'type' => 'text',
                                        'text' => $parametersData['header_parameters'],
                                    ];
                                    $component_header[] = $componentHeader;
                                
                            }
                        } elseif($body['components'][0]['type'] === 'HEADER' && $body['components'][0]['format'] === 'IMAGE'  ){
                            
                                        
                                        $componentHeader = [
                                        'type' => 'image',
                                        'image' => [
                                            'link' => $parametersData['header_parameters'] ?? $body['components'][0]['example']['header_handle'][0],
                                            ],
                                    ];
                                    $component_header[] = $componentHeader;
                        }elseif($body['components'][0]['type'] === 'HEADER' && $body['components'][0]['format'] === 'VIDEO'  ){
                            
                                        
                                        $componentHeader = [
                                        'type' => 'video',
                                        'video' => [
                                            'link' => $parametersData['header_parameters'] ?? $body['components'][0]['example']['header_handle'][0],
                                            ],
                                    ];
                                    $component_header[] = $componentHeader;
                        }elseif($body['components'][0]['type'] === 'HEADER' && $body['components'][0]['format'] === 'DOCUMENT'  ){
                            $componentHeader = [
                                        'type' => 'document',
                                        'document' => [
                                            'link' => $parametersData['header_parameters'] ?? $body['components'][0]['example']['header_handle'][0],
                                            ],
                                    ];
                                    $component_header[] = $componentHeader;
                        }
                        else{
                            
                                    $component_header = [];
                            }
                            // Assuming $reply->parameters contains the JSON data
                            
                            
                            if ($body['components'][0]['type'] === 'BODY' || $body['components'][1]['type'] === 'BODY'  ) {
                            if (!empty($parametersData['message_parameters'])) {
                            Log::info('akkadbakaadnuum: ' . $parametersData['message_parameters'][0]);
                            $component_body = [];

                            foreach ($parametersData['message_parameters'] as $value) {
                                
                            $componentBody = [
                            'type' => 'text',
                            'text' => $value,
                            ];

                            $component_body[] = $componentBody;
                        }
                    }}
                    $components = new Component($component_header, $component_body, $component_buttons);
                    $response=$whatsapp_app_cloud_api->sendTemplate($request_from, $templateName, $body['language'], $components);
                    $messageMatched = true;
                    $status = customFunction('ReadReceipt', 'status', $response);
                
                            if ($status->status() === 200 && getUserPlanData('read_receipt', $cloudapi->user_id) === true) {
                                $wamid = $status->getData(true)['wamid'];
                            }else{
                                $wamid = uniqid('chat_', true);
                            }
                    $this->saveMessageToUserChat($userChat, 'Meta Messages-'.$templateName, 'sent', $wamid);
                    if (getUserPlanData('wallet_system', $user->id) === true){
                            customFunction('WalletSystem', 'CreditRate', $user);
                       }
                    return response()->json([
                        'message' => 'Webhook received successfully'
                    ], 200);
                            } else {
                                $response=$whatsapp_app_cloud_api-> sendTemplate($request_from, $templateName, $body['language']);
                                $status = customFunction('ReadReceipt', 'status', $response);
                
                            if ($status->status() === 200 && getUserPlanData('read_receipt', $cloudapi->user_id) === true) {
                                $wamid = $status->getData(true)['wamid'];
                            }else{
                                $wamid = uniqid('chat_', true);
                            }
                                $this->saveMessageToUserChat($userChat, 'Meta Messages-'.$templateName, 'sent', $wamid);
                                if (getUserPlanData('wallet_system', $user->id) === true){
                                    customFunction('WalletSystem', 'CreditRate', $user);
                               }
                                return response()->json([
                                    'message' => 'Webhook received successfully'
                                ], 200);
                            }
                        }
                          if($template->type == 'text-with-button'){
                              $data = $body;
  
                              // Assuming $data contains body
                              $component_header = [];
                              $component_body = [];
                              $component_buttons = [];
                              $templateName = $body['templateName'];
  
  
                              $component_header = [];
                              $component_body = [
                                           [
                                              'type' => 'text',
                                              'text' => $data['text'],
                                           ],
                                          ];
  
                              $component_buttons = [];
                              foreach ($data['buttons'] as $index => $button) {
                                      $component_buttons[] = [
                                       'type' => 'button',
                                       'sub_type' => 'quick_reply',
                                       'index' => $index,
                                       'parameters' => [
                                               [
                                                  'type' => 'text',
                                                  'text' => $button['buttonText']['displayText'],
                                               ]
                                          ]
                                      ];
                                  }
  
                              $components = new Component($component_header, $component_body, $component_buttons);
                              $respons=$whatsapp_app_cloud_api->sendTemplate($request_from, $template->title, 'en_US', $components);
                              $status = customFunction('ReadReceipt', 'status', $response);
                
                            if ($status->status() === 200 && getUserPlanData('read_receipt', $cloudapi->user_id) === true) {
                                $wamid = $status->getData(true)['wamid'];
                            }else{
                                $wamid = uniqid('chat_', true);
                            }
                            $this->saveMessageToUserChat($userChat, 'Button Messages-'.$template->title, 'sent', $wamid);
                              
                          }
                          
                          if($template->type == 'text-with-media'){
                              $data = $body;
  
                              if (isset($data['image']) && !empty($data['image']['url'])) {
                                  $link_id = new LinkID($data['image']['url']);
                                  $response=$whatsapp_app_cloud_api->sendImage($request_from, $link_id);
                                  $status = customFunction('ReadReceipt', 'status', $response);
                
                            if ($status->status() === 200 && getUserPlanData('read_receipt', $cloudapi->user_id) === true) {
                                $wamid = $status->getData(true)['wamid'];
                            }else{
                                $wamid = uniqid('chat_', true);
                            }
                                $this->saveMessageToUserChat($userChat, 'Media Messages', 'sent', $wamid);
                                  
                              }
                              elseif (isset($data['document']) && !empty($data['document']['url'])) {
                                  $document_caption = $data['caption'];
                                  $document_url = $data['document']['url'];
                                  $document_name = basename($document_url);
                                  $link_id = new LinkID($document_url);
                                  $response=$whatsapp_app_cloud_api->sendDocument($request_from, $link_id, $document_name);
                                  $status = customFunction('ReadReceipt', 'status', $response);
                
                            if ($status->status() === 200 && getUserPlanData('read_receipt', $cloudapi->user_id) === true) {
                                $wamid = $status->getData(true)['wamid'];
                            }else{
                                $wamid = uniqid('chat_', true);
                            }
                                $this->saveMessageToUserChat($userChat, 'Doc Messages-'.$document_name, 'sent', $wamid);
                                  
                              }
                              
  
                          }
  
                          if($template->type == 'text-with-location'){
                              $data = $body;
                              $latitude = $data['location']['degreesLatitude'];
                              $longitude= $data['location']['degreesLongitude'];
                              $response=$whatsapp_app_cloud_api->sendLocation($request_from, $longitude, $latitude, '', $template->title);
                              $status = customFunction('ReadReceipt', 'status', $response);
                
                            if ($status->status() === 200 && getUserPlanData('read_receipt', $cloudapi->user_id) === true) {
                                $wamid = $status->getData(true)['wamid'];
                            }else{
                                $wamid = uniqid('chat_', true);
                            }
                                $this->saveMessageToUserChat($userChat, 'Location Messages-'.$template->title, 'sent', $wamid);
                              
                          }
  
  
                           
                      }                    
                  }
                  break; 
            }
          }
          }
       }
      }
       
       return response()->json([
        'message' => 'Webhook received successfully'
    ], 200);
       
    }
    
    public function userChatContainsNamePrompt($userChat, $namePrompt)
{
    if ($userChat) {
        $userChatMessages = json_decode($userChat->message_history, true);
        
        foreach ($userChatMessages as $message) {
            if (isset($message['message']) && $message['message'] == $namePrompt) {
                return true;
            }
        }
    }

    return false;
}



public function userChatContainsPostSuccess($userChat, $postSuccess)
{
    if ($userChat) {
        $userChatMessages = json_decode($userChat->message_history, true);
        foreach ($userChatMessages as $message) {
            if (isset($message['message']) && $message['message'] == $postSuccess) {
                return true;
            }
        }
    }
    return false;
}



public function isValidName($name) {
    // Check if the name starts with a letter
    // Check if the name has a reasonable size (greater than 4 and not more than 25 characters)
    // Check if the name does not end with an underscore (you can customize these rules)

    $startsWithLetter = preg_match('/^[a-zA-Z]/', $name);
    $reasonableSize = strlen($name) > 4 && strlen($name) <= 25;
    $doesNotEndWithUnderscore = substr($name, -1) !== '_';

    return $startsWithLetter && $reasonableSize && $doesNotEndWithUnderscore;
}
}

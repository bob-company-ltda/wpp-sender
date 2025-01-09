<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Storage;
use Illuminate\Queue\SerializesModels;
use App\Models\Template;
use App\Models\CloudApi;
use App\Models\Contact;
use App\Models\User;
use App\Models\Option;
use App\Http\Requests\Bulkrequest;
use App\Models\ChatMessage;
use Illuminate\Support\Facades\Log;
use Netflie\WhatsAppCloudApi\WhatsAppCloudApi;
use Netflie\WhatsAppCloudApi\Message\OptionsList\Row;
use Netflie\WhatsAppCloudApi\Message\OptionsList\Section;
use Netflie\WhatsAppCloudApi\Message\OptionsList\Action;
use Netflie\WhatsAppCloudApi\Message\Media\LinkID;
use Netflie\WhatsAppCloudApi\Message\Media\MediaObjectID;
use Netflie\WhatsAppCloudApi\Message\Template\Component;
use App\Models\Group;
use Carbon\Carbon;
use App\Traits\Cloud;
use Http;
use Auth;
use DB;
use Str;

class SendMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Cloud;
    public $whatsapp_app_cloud_api;
    
    protected $contact;
    protected $templateId;
    protected $cloudApiId;
    protected $userId;
    protected $saveToChat;
    protected $replacedHeaderParm;
    protected $replacedBody;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($contact, $templateId, $cloudApiId, $userId, $saveToChat, $replacedHeaderParm, $replacedBody)
    {
        $this->contact = $contact;
        $this->templateId = $templateId;
        $this->cloudApiId = $cloudApiId;
        $this->userId = $userId;
        $this->saveToChat = $saveToChat;
        $this->replacedHeaderParm = $replacedHeaderParm;
        $this->replacedBody = $replacedBody;
    }
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        
        $template = Template::where('user_id', $this->userId)->where('status', 1)->findOrFail($this->templateId);
        $cloudapi = CloudApi::where('user_id', $this->userId)->where('status', 1)->findOrFail($this->cloudApiId);
        $contact = Contact::where('user_id', $this->userId)->findOrFail($this->contact->id);
        $user = User::where('id', $this->userId)->first();
        $userChat = ChatMessage::where('phone_number', $contact->phone)->where('cloudapi_id', $cloudapi->id)->first();

        $whatsapp_app_cloud_api = new WhatsAppCloudApi([
            'from_phone_number_id' => $cloudapi->phone_number_id,
            'access_token' => $cloudapi->access_token,
        ]);

        if (isset($template->body)) {
                $body = $template->body;
            }
            else{
                return response()->json(['error'=>'Template Not Found'],401);
            }
        $type = $template->type;

        if($type == 'plain-text'){
                $data = $body;
                $formatText=$this->formatText($body['text'],$contact,$user);
                $desc = $formatText;
                try{
                $response= $whatsapp_app_cloud_api->sendTextMessage($contact->phone, $desc);
                $status = customFunction('ReadReceipt', 'status', $response);
                
                if ($status->status() === 200 && getUserPlanData('read_receipt', $cloudapi->user_id) === true) {
                    $wamid = $status->getData(true)['wamid'];
                }else{
                    $wamid = uniqid('chat_', true);
                }
                $logs['user_id']=$cloudapi->user_id;
            $logs['cloudapi_id']=$cloudapi->id;
            $logs['from']=$cloudapi->phone ?? null;
            $logs['to']=$contact->phone;
            $logs['type']='bulk-message';
            $logs['template_id']=$template->id ?? null;
            $logs['wamid'] = $wamid;
            $logs['status'] = 'Sent';
            $this->saveLog($logs);
            if($this->saveToChat == 'true'){
            $this->saveMessageToUserChat($wamid,$userChat,$body['text'],$type, $contact->phone, $cloudapi->id);
            }
                Log::info('Message sent successfully', [
                    'from' => $cloudapi->phone ?? null,
                    'to' => $contact->phone,
                    'status_code' => 200,
                ]);
                
                }catch(\Netflie\WhatsAppCloudApi\Response\ResponseException $e){
                        $errorDetails = $e->getMessage(); // This gets the JSON string
                        $errorObject = json_decode($errorDetails); // Decode into an object
    
                        // Now, access the nested message property
                        $errorMessage = isset($errorObject->error->message) ? $errorObject->error->message : 'An unknown error occurred';
                        Log::error('Message sending failed', ['error' => $errorMessage]);
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
                $contact->phone,
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
                $logs['user_id']=$cloudapi->user_id;
            $logs['cloudapi_id']=$cloudapi->id;
            $logs['from']=$cloudapi->phone ?? null;
            $logs['to']=$contact->phone;
            $logs['type']='bulk-message';
            $logs['template_id']=$template->id ?? null;
            $logs['wamid'] = $wamid;
            $logs['status'] = 'Sent';
            $this->saveLog($logs);
            if($this->saveToChat == 'true'){
            $this->saveMessageToUserChat($wamid,$userChat,$template->title,$type, $contact->phone, $cloudapi->id);
            }

                return response()->json(['message_status'=>'Success','data'=>[
                    'from'=>$cloudapi->phone ?? null,
                    'to'=>$contact->phone,                
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
            if($template->type == 'text-with-media' || $template->type == 'text-with-image' ){
                $data = $body;

                if (isset($data['image']) && !empty($data['image']['url'])) {
                    $link_id = new LinkID($data['image']['url']);
                    try{
                    $response = $whatsapp_app_cloud_api->sendImage($contact->phone, $link_id);
                    $status = customFunction('ReadReceipt', 'status', $response);
                
                if ($status->status() === 200 && getUserPlanData('read_receipt', $cloudapi->user_id) === true) {
                    $wamid = $status->getData(true)['wamid'];
                }else{
                    $wamid = uniqid('chat_', true);
                }
                    $logs['user_id']=$cloudapi->user_id;
                    $logs['cloudapi_id']=$cloudapi->id;
                    $logs['from']=$cloudapi->phone ?? null;
                    $logs['to']=$contact->phone;
                    $logs['type']='bulk-message';
                    $logs['template_id']=$template->id ?? null;
                    $logs['wamid'] = $wamid;
                    $logs['status'] = 'Sent';
                    $this->saveLog($logs);
                    if($this->saveToChat == 'true'){
                    $this->saveMessageToUserChat($wamid,$userChat,$template->title,$template->type, $contact->phone, $cloudapi->id);
                    }
                    return response()->json(['message_status'=>'Success','data'=>[
                        'from'=>$cloudapi->phone ?? null,
                        'to'=>$contact->phone,                
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
                    $response=$whatsapp_app_cloud_api->sendDocument($contact->phone, $link_id, $document_name);
                    $status = customFunction('ReadReceipt', 'status', $response);
                
                if ($status->status() === 200 && getUserPlanData('read_receipt', $cloudapi->user_id) === true) {
                    $wamid = $status->getData(true)['wamid'];
                }else{
                    $wamid = uniqid('chat_', true);
                }   
                    $logs['user_id']=$cloudapi->user_id;
                    $logs['cloudapi_id']=$cloudapi->id;
                    $logs['from']=$cloudapi->phone ?? null;
                    $logs['to']=$contact->phone;
                    $logs['type']='bulk-message';
                    $logs['template_id']=$template->id ?? null;
                    $logs['wamid'] = $wamid;
                    $logs['status'] = 'Sent';
                    $this->saveLog($logs);
                    if($this->saveToChat == 'true'){
                    $this->saveMessageToUserChat($wamid,$userChat,$template->title,$template->type, $contact->phone, $cloudapi->id);
                    }
                    return response()->json(['message_status'=>'Success','data'=>[
                        'from'=>$cloudapi->phone ?? null,
                        'to'=>$contact->phone,                
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
                $response=$whatsapp_app_cloud_api->sendLocation($contact->phone, $longitude, $latitude, '', $template->title);
                $status = customFunction('ReadReceipt', 'status', $response);
                
                if ($status->status() === 200 && getUserPlanData('read_receipt', $cloudapi->user_id) === true) {
                    $wamid = $status->getData(true)['wamid'];
                }else{
                    $wamid = uniqid('chat_', true);
                }
                $logs['user_id']=$cloudapi->user_id;
                    $logs['cloudapi_id']=$cloudapi->id;
                    $logs['from']=$cloudapi->phone ?? null;
                    $logs['to']=$contact->phone;
                    $logs['type']='bulk-message';
                    $logs['template_id']=$template->id ?? null;
                    $logs['wamid'] = $wamid;
                    $logs['status'] = 'Sent';
                    $this->saveLog($logs);
                    if($this->saveToChat == 'true'){
                    $this->saveMessageToUserChat($wamid,$userChat,$template->title,$template->type, $contact->phone, $cloudapi->id);
                    }
                return response()->json(['message_status'=>'Success','data'=>[
                    'from'=>$cloudapi->phone ?? null,
                    'to'=>$contact->phone,                
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
                
                if ($wallet->status() === 200 && getUserPlanData('wallet_system',$cloudapi->user_id) === true) {
                   Log::info('Credit Exhausted, Please Recharge Your Wallet.', [
                'status_code' => 400,
            ]);
            $this->fail(new \Exception('Credit Exhausted, Please Recharge Your Wallet.'));
            return; // Stop further execution
                }

                $templateName = $body['name'];
                $language = $body['language'];
                if (!empty($body['components'][0]['example']['header_text'][0]) || !empty($body['components'][0]['example']['body_text'][0][0]) || !empty($body['components'][0]['example']['header_handle'][0])) {
                    
                                
                                $component_header = [];
                                $component_body = [];
                                $component_buttons = [];
                                $templateName = $body['name'];
                                if ($body['components'][0]['type'] === 'HEADER' && $body['components'][0]['format'] === 'TEXT'  ) {
                                    if (!empty($this->replacedHeaderParm)) {
                                        
                                        $component_header = [];
                                        
                                        $componentHeader = [
                                        'type' => 'text',
                                        'text' => $this->replacedHeaderParm,
                                    ];
                                    $component_header[] = $componentHeader;
                                
                            }
                        } elseif($body['components'][0]['type'] === 'HEADER' && $body['components'][0]['format'] === 'IMAGE'  ){
                            
                                        if($this->replacedHeaderParm == NULL){
                                            $link = $body['components'][0]['example']['header_handle'][0];
                                            $imagePath = $this->saveImg($link);
                                            $link = $imagePath;
                                        }else{
                                            $link = $this->replacedHeaderParm;
                                        }
                                        
                                        $componentHeader = [
                                        'type' => 'image',
                                        'image' => [
                                            'link' => $link,
                                            ],
                                    ];
                                    $component_header[] = $componentHeader;
                        }elseif($body['components'][0]['type'] === 'HEADER' && $body['components'][0]['format'] === 'DOCUMENT'  ){
                            if($this->replacedHeaderParm == NULL){
                                            $link = $body['components'][0]['example']['header_handle'][0];
                                            $imagePath = $this->saveImg($link);
                                            $link = $imagePath;
                                        }else{
                                            $link = $this->replacedHeaderParm;
                                        }
                            $componentHeader = [
                                        'type' => 'document',
                                        'document' => [
                                            'link' => $link,
                                            ],
                                    ];
                                    $component_header[] = $componentHeader;
                        }
                        else{
                            
                                    $component_header = [];
                            }
                            // Assuming $reply->parameters contains the JSON data
                            
                            
                            if ($body['components'][0]['type'] === 'BODY' || $body['components'][1]['type'] === 'BODY'  ) {
                            if (!empty($this->replacedBody)){
                            $bodyString = $this->replacedBody;
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
                    $response= $whatsapp_app_cloud_api->sendTemplate($contact->phone, $templateName, $language, $components);
                    $status = customFunction('ReadReceipt', 'status', $response);
                
                if ($status->status() === 200 && getUserPlanData('read_receipt', $cloudapi->user_id) === true) {
                    $wamid = $status->getData(true)['wamid'];
                }else{
                    $wamid = uniqid('chat_', true);
                }
                     $logs['user_id']=$cloudapi->user_id;
                    $logs['cloudapi_id']=$cloudapi->id;
                    $logs['from']=$cloudapi->phone ?? null;
                    $logs['to']=$contact->phone;
                    $logs['type']='bulk-message';
                    $logs['template_id']=$template->id ?? null;
                    $logs['wamid'] = $wamid;
                    $logs['status'] = 'Sent';
                    $this->saveLog($logs);
                    if($this->saveToChat == 'true'){
                        
                    $this->saveMessageToUserChat($wamid,$userChat,$template->title,$template->type, $contact->phone, $cloudapi->id);
                    }
                    
                   if (getUserPlanData('wallet_system', $cloudapi->user_id) === true){
                            customFunction('WalletSystem', 'CreditRate', $user);
                       }

                    Log::info('Message sent successfully', [
                    'from' => $cloudapi->phone ?? null,
                    'to' => $contact->phone,
                    'status_code' => 200,
                ]);
                    }catch(\Netflie\WhatsAppCloudApi\Response\ResponseException $e){
                        $errorDetails = $e->getMessage(); // This gets the JSON string
                        $errorObject = json_decode($errorDetails); // Decode into an object
    
                        // Now, access the nested message property
                        $errorMessage = isset($errorObject->error->message) ? $errorObject->error->message : 'An unknown error occurred';
                        Log::error('Message sending failed', ['error' => $errorMessage]);
                    }
                } else {
                    try{
                    $response = $whatsapp_app_cloud_api-> sendTemplate($contact->phone, $templateName, $body['language']);
                    $status = customFunction('ReadReceipt', 'status', $response);
                
                if ($status->status() === 200 && getUserPlanData('read_receipt', $cloudapi->user_id) === true) {
                    $wamid = $status->getData(true)['wamid'];
                }else{
                    $wamid = uniqid('chat_', true);
                }
                    $logs['user_id']=$cloudapi->user_id;
                    $logs['cloudapi_id']=$cloudapi->id;
                    $logs['from']=$cloudapi->phone ?? null;
                    $logs['to']=$contact->phone;
                    $logs['type']='bulk-message';
                    $logs['template_id']=$template->id ?? null;
                    $logs['wamid'] = $wamid;
                    $logs['status'] = 'Sent';
                    $this->saveLog($logs);
                    if($this->saveToChat == 'true'){
                    $this->saveMessageToUserChat($wamid,$userChat,$template->title,$template->type, $contact->phone, $cloudapi->id);
                    }
                    if (getUserPlanData('wallet_system', $cloudapi->user_id) === true){
                            customFunction('WalletSystem', 'CreditRate', $user);
                       }

                    Log::info('Message sent successfully', [
                    'from' => $cloudapi->phone ?? null,
                    'to' => $contact->phone,
                    'status_code' => 200,
                ]);
                    }catch(\Netflie\WhatsAppCloudApi\Response\ResponseException $e){
                        $errorDetails = $e->getMessage(); // This gets the JSON string
                        $errorObject = json_decode($errorDetails); // Decode into an object
    
                        // Now, access the nested message property
                        $errorMessage = isset($errorObject->error->message) ? $errorObject->error->message : 'An unknown error occurred';
                        Log::error('Message sending failed', ['error' => $errorMessage]);
                    }
                }
            }

            return response()->json([
                'message'  => __('!Opps Request Failed'),
            ], 401);
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

public function saveImg($url)
{
    // Define the directory where you want to save the image
    $path = 'uploads/' . date('Y') . '/' . date('m') . '/'; // Define path with year and month

    // Ensure the directory exists (create if not)
    if (!Storage::exists($path)) {
        Storage::makeDirectory($path);
    }

    // Extract the file name from the URL
    $fileName = basename(parse_url($url, PHP_URL_PATH)); 

    // Create the full file path
    $filePath = $path . $fileName;

    // Use file_get_contents to fetch the image from the URL
    $imageData = file_get_contents($url);

    if ($imageData === false) {
        // Handle error if the image could not be fetched
        return false;
    }

    // Save the image to the specified directory using Laravel's storage system
    if (!Storage::put($filePath, $imageData)) {
        // Handle error if the image could not be saved
        return false;
    }

    // Return the local path to the saved image
    return Storage::url($filePath);
}
}
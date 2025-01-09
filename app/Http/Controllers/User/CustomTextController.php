<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Smstransaction;
use App\Models\CloudApi;
use App\Models\Template;
use App\Rules\Phone;
use App\Traits\Cloud;
use App\Models\Option;
use App\Models\ChatMessage;
use App\Libraries\WhatsappLibrary;
use Http;
use Auth;
use File;
use DB;
use Netflie\WhatsAppCloudApi\WhatsAppCloudApi;
use Netflie\WhatsAppCloudApi\Message\OptionsList\Row;
use Netflie\WhatsAppCloudApi\Message\OptionsList\Section;
use Netflie\WhatsAppCloudApi\Message\OptionsList\Action;
use Netflie\WhatsAppCloudApi\Message\Media\LinkID;
use Netflie\WhatsAppCloudApi\Message\Media\MediaObjectID;
use Netflie\WhatsAppCloudApi\Message\Template\Component;
use Netflie\WhatsAppCloudApi\Message\ButtonReply\Button;
use Netflie\WhatsAppCloudApi\Message\ButtonReply\ButtonAction;




class CustomTextController extends Controller
{

    use Cloud;
    public $whatsapp_app_cloud_api;
    public $wa_lib;

    
   

    //return custom text message view page
    public function index()
    {
       
        $phoneCodes=file_exists('uploads/phonecode.json') ? json_decode(file_get_contents('uploads/phonecode.json')) : [];
        $cloudapis=CloudApi::where('user_id',Auth::id())->where('status',1)->latest()->get();
        $templates = template::where('type', 'meta-template')->where('user_id', Auth::id())->where('status',1)->get();
	
        return view('user.singlesend.create',compact('phoneCodes','cloudapis','templates'));
    }

    public function templateDetails(Request $request)
{
    $selectedTemplate = $request->template;

    // Retrieve the template details based on the selected template
    $template = Template::where('title', $selectedTemplate)->first();

    if ($template && isset($template->body)) {
        $body = $template->body;

        return response()->json(['body' => $body]);
    }

    return response()->json(['body' => []]);
}


public function saveMessageToUserChat($id='',$userChat, $templateName, $type, $request_from, $templateCloudId)
{
    if ($userChat) {
        $userChatMessages = json_decode($userChat->message_history, true) ?? [];
        //$chatID = uniqid('chat_', true);
        $newMessage = [
            'chatID' => $id,
            'message' => 'Single Send-['.$type.']:'. $templateName,
            'timestamp' => now()->toDateTimeString(),
            'status' => 'sent',
            'type' => 'sent',
        ];

        $userChatMessages[] = $newMessage;

        // Update the message history in the database
        $userChat->message_history = json_encode($userChatMessages);
        $userChat->save();
    }else{
        //$chatID = uniqid('chat_', true);
        $newUserChat = new ChatMessage();
        $newUserChat->phone_number = $request_from;
        $newUserChat->cloudapi_id = $templateCloudId;
        $newUserChat->message_history = json_encode([
        [
            'chatID' => $id,
            'message' => 'Single Send-['.$type.']:'. $templateName,
            'timestamp' => now()->toDateTimeString(),
            'status' => 'sent',
            'type' => 'sent',
        ]
    ]);
    $newUserChat->save();

    }
}

    //sent custom text msg request to api
    public function sentCustomText(Request $request,$type)
    {
        //dd($request);
        
        $cloudapi = CloudApi::findOrFail($request->cloudapi);
        $user = Auth::user();
        

        $whatsapp_app_cloud_api = new WhatsAppCloudApi([
            'from_phone_number_id' => $cloudapi->phone_number_id,
            'access_token' => $cloudapi->access_token,
        ]);
        
        $wa_lib = new WhatsappLibrary();

        $validated = $request->validate([
            'phone'   => ['required', new Phone],
            'cloudapi'=>['required','numeric'], 
        ]);

        if (getUserPlanData('messages_limit') == false) {
            return response()->json([
                'message'=>__('Maximum Monthly Messages Limit Exceeded')
            ],401);  
        }

        if ($request->templatestatus) {
            if (getUserPlanData('template_limit') == false) {
                return response()->json([
                    'message'=>__('Maximum Template Limit Exceeded')
                ],401);  
            }
        }

        $cloudapi=CloudApi::where('user_id',Auth::id())->where('status',1)->findorFail($request->cloudapi);

        $phone=str_replace('+', '', $request->phone);
        $userChat = ChatMessage::where('phone_number', $phone)->where('cloudapi_id', $cloudapi->id)->first();
    
        
        if($type == 'meta-template'){
            
            $wallet = customFunction('WalletSystem', 'CheckCredit', $user);
                
                if ($wallet->status() === 200 && getUserPlanData('wallet_system') === true) {
                   return response()->json([
                    'message'=>__('Credit Exhausted, Please Recharge Your Wallet.')
                ],401);
                }
            
            if($request->header_text_parameter){
               $validated = $request->validate([
            'header_text_parameter'=>['required'], 
        ]);
            }
            
            try{
                $response = $wa_lib->metaTemplatemessage($cloudapi->phone_number_id, $cloudapi->access_token, $request);
                $status = customFunction('ReadReceipt', 'status', $response, null, true);
                
               if ($status->status() === 200 && getUserPlanData('read_receipt') === true) {
                    $wamid = $status->getData(true)['wamid'];
                }else{
                    $wamid = uniqid('chat_', true);
                }
                
                $logs['user_id']=Auth::id();
                       $logs['cloudapi_id']=$cloudapi->id;
                       $logs['from']=$cloudapi->phone;
                       $logs['to']=$phone;
                       $logs['type']='single-send';
                       $logs['wamid'] = $wamid;
                       $this->saveLog($logs);
                       $this->saveMessageToUserChat($wamid,$userChat,$request->template_text,$type, $phone, $cloudapi->id);
                       
                       if (getUserPlanData('wallet_system') === true){
                            customFunction('WalletSystem', 'CreditRate', $user);
                       }
                       return response()->json([
                                'message' => __("Message send Successfully"),
                            ], 200);
            }catch(\Exception $exception){
                $errorMessage = $exception->getMessage();
                preg_match('/\"message\":\"(.*?)\"/', $errorMessage, $matches);
                $extractedMessage = isset($matches[1]) ? $matches[1] : 'Unknown error';
                return response()->json([
                                    'message' => $extractedMessage,
                                ], 500);
                }
            }


        elseif($type == 'plain-text'){
            $validated = $request->validate([
                'message' => 'required|max:1000',
            ]);
            $message = $request->message;

            try {
                $response = $whatsapp_app_cloud_api->sendTextMessage($phone, $message, true);
                $status = customFunction('ReadReceipt', 'status', $response);
                
               if ($status->status() === 200 && getUserPlanData('read_receipt') === true) {
                    $wamid = $status->getData(true)['wamid'];
                }else{
                    $wamid = uniqid('chat_', true);
                }
                 $logs['user_id']=Auth::id();
                       $logs['cloudapi_id']=$cloudapi->id;
                       $logs['from']=$cloudapi->phone;
                       $logs['to']=$phone;
                       $logs['type']='single-send';
                       $logs['wamid'] = $wamid;
            
                       $this->saveLog($logs);
                       $this->saveMessageToUserChat($wamid,$userChat,$message,$type,$phone, $cloudapi->id);
                       
                       return response()->json([
                                'message' => __('Message sent successfully..!!'),
                            ], 200);
            
            } catch (\Netflie\WhatsAppCloudApi\Response\ResponseException $e) {
                        $errorDetails = $e->getMessage(); 
                        $errorObject = json_decode($errorDetails); 
                        $errorMessage = isset($errorObject->error->message) ? $errorObject->error->message : 'An unknown error occurred';
            return response()->json([
                                'message' => $errorMessage,
                            ], $e->httpStatusCode());
            }

        }

        if ($type == 'text-with-media') {
            $validated = $request->validate([
                'file' => 'required|mimes:jpg,jpeg,png,webp,gif,pdf,docx,xlsx,csv,txt|max:1000',
                'message' => 'required|max:1000',
            ]);

            $file=$this->saveFile($request,'file');
            $fileExt = $this->saveFileExt($request, 'file');
            $request['attachment']=$file;
            $document = $file;
            $document_name = $fileExt;
            $message = $request->message;


            $link_id = new LinkID($document);

            try {
                $response = $whatsapp_app_cloud_api->sendDocument($phone, $link_id, $document_name, $message);
                $status = customFunction('ReadReceipt', 'status', $response);
                
                if ($status->status() === 200 && getUserPlanData('read_receipt') === true) {
                    $wamid = $status->getData(true)['wamid'];
                }else{
                    $wamid = uniqid('chat_', true);
                }
                 $logs['user_id']=Auth::id();
                       $logs['cloudapi_id']=$cloudapi->id;
                       $logs['from']=$cloudapi->phone;
                       $logs['to']=$phone;
                       $logs['type']='single-send';
                       $logs['wamid'] = $wamid;
            
                       $this->saveLog($logs);
                       $this->saveMessageToUserChat($wamid, $userChat,$message,$type,$phone, $cloudapi->id);
                       
                       return response()->json([
                                'message' => __('Message sent successfully..!!'),
                            ], 200);
            
            } catch (\Netflie\WhatsAppCloudApi\Response\ResponseException $e) {
            return response()->json([
                                'message' => $e->rawResponse(),
                            ], $e->httpStatusCode());
            }

        }elseif ($type == 'text-with-image'){
            $validated = $request->validate([
                'image' => 'required|mimes:jpg,jpeg,png|max:1000',
            ]);

            $image=$this->saveFile($request,'image');
            $link_id = new LinkID($image);
            $caption = $request->message;
            try {
                $response = $whatsapp_app_cloud_api->sendImage($phone, $link_id, $caption);
                $status = customFunction('ReadReceipt', 'status', $response);
                
                if ($status->status() === 200 && getUserPlanData('read_receipt') === true){
                    $wamid = $status->getData(true)['wamid'];
                }else{
                    $wamid = uniqid('chat_', true);
                }
                $logs['user_id']=Auth::id();
                       $logs['cloudapi_id']=$cloudapi->id;
                       $logs['from']=$cloudapi->phone;
                       $logs['to']=$phone;
                       $logs['type']='single-send';
                       $logs['wamid'] = $wamid;
            
                       $this->saveLog($logs);
                       $this->saveMessageToUserChat($wamid, $userChat,$caption,$type,$phone, $cloudapi->id);
                       
                       return response()->json([
                                'message' => __('Message sent successfully..!!'),
                            ], 200);
            
            } catch (\Netflie\WhatsAppCloudApi\Response\ResponseException $e) {
            return response()->json([
                                'message' => $e->rawResponse(),
                            ], $e->httpStatusCode());
            }
        }elseif ($type == 'text-with-video'){
            $validated = $request->validate([
                'video' => 'required|mimes:mp4|max:6000',
            ]);

            $image=$this->saveFile($request,'video');
            $link_id = new LinkID($image);
            $caption = $request->message;
            try {
                $response = $whatsapp_app_cloud_api->sendVideo($phone, $link_id, $caption);
                $status = customFunction('ReadReceipt', 'status', $response);
                
                if ($status->status() === 200 && getUserPlanData('read_receipt') === true){
                    $wamid = $status->getData(true)['wamid'];
                }else{
                    $wamid = uniqid('chat_', true);
                }
                $logs['user_id']=Auth::id();
                       $logs['cloudapi_id']=$cloudapi->id;
                       $logs['from']=$cloudapi->phone;
                       $logs['to']=$phone;
                       $logs['type']='single-send';
                       $logs['wamid'] = $wamid;
            
                       $this->saveLog($logs);
                       $this->saveMessageToUserChat($wamid, $userChat,$caption,$type,$phone, $cloudapi->id);
                       return response()->json([
                                'message' => __('Message sent successfully..!!'),
                            ], 200);
            
            } catch (\Netflie\WhatsAppCloudApi\Response\ResponseException $e) {
            return response()->json([
                                'message' => $e->rawResponse(),
                            ], $e->httpStatusCode());
            }
        }elseif ($type == 'text-with-vcard') {
            $validated = $request->validate([
                'display_name' => 'required|max:100',
                'full_name' => 'required|max:100',
                'org_name' => 'required|max:100',
                'contact_number' => ['required', new Phone,'max:20'],
                'wa_number' => ['required', new Phone,'max:20'],
                
            ]);
        }elseif($type == 'text-with-audio'){
            $validated = $request->validate([
                'audio' => 'required|mimes:mp3,ogg,wav|max:500',
            ]);
            $image=$this->saveFile($request,'audio');
            $link_id = new LinkID($image);
            //$caption = $request->message;
            try {
                $response = $whatsapp_app_cloud_api->sendAudio($phone, $link_id);
                $status = customFunction('ReadReceipt', 'status', $response);
                
                if ($status->status() === 200 && getUserPlanData('read_receipt') === true) {
                    $wamid = $status->getData(true)['wamid'];
                }else{
                    $wamid = uniqid('chat_', true);
                }
                $logs['user_id']=Auth::id();
                       $logs['cloudapi_id']=$cloudapi->id;
                       $logs['from']=$cloudapi->phone;
                       $logs['to']=$phone;
                       $logs['type']='single-send';
                       $logs['wamid'] = $wamid;
            
                       $this->saveLog($logs);
                       $this->saveMessageToUserChat($wamid, $userChat,'Audio Message',$type,$phone, $cloudapi->id);
                       return response()->json([
                                'message' => __('Message sent successfully..!!'),
                            ], 200);
            }
            catch (\Netflie\WhatsAppCloudApi\Response\ResponseException $e) {
                return response()->json([
                                    'message' => $e->rawResponse(),
                                ], $e->httpStatusCode());
                }
        }
        
        elseif ($type == 'text-with-template') {
            
            
            
                $wallet = customFunction('WalletSystem', 'CheckCredit', $user);
                
                if ($wallet->status() === 200 && getUserPlanData('wallet_system') === true) {
                   return response()->json([
                    'message'=>__('Credit Exhausted, Please Recharge Your Wallet.')
                ],401);
                }
            
            $template=Template::where('user_id',Auth::id())->where('status',1)->findorFail($request->template); 
                $body = $template->body;
                $templateName = $body['name'];
                $language = $body['language'];
                if (!empty($body['components'][0]['example']['header_text'][0]) || !empty($body['components'][0]['example']['body_text'][0][0]) || !empty($body['components'][0]['example']['header_handle'][0])) {
                    
                                
                                $component_header = [];
                                $component_body = [];
                                $component_buttons = [];
                                $templateName = $body['name'];
                                if ($body['components'][0]['type'] === 'HEADER' && $body['components'][0]['format'] === 'TEXT'  ) {
                                    
                                    if (!empty($request->header_param)) {
                                        
                                        $component_header = [];
                                        
                                        $componentHeader = [
                                        'type' => 'text',
                                        'text' => $request->header_param,
                                    ];
                                    $component_header[] = $componentHeader;
                                
                            }
                        } elseif($body['components'][0]['type'] === 'HEADER' && $body['components'][0]['format'] === 'IMAGE'  ){
                            
                                        
                                        $componentHeader = [
                                        'type' => 'image',
                                        'image' => [
                                            'link' => $request->header_param ?? $body['components'][0]['example']['header_handle'][0],
                                            ],
                                    ];
                                    $component_header[] = $componentHeader;
                        }elseif($body['components'][0]['type'] === 'HEADER' && $body['components'][0]['format'] === 'DOCUMENT'  ){
                            $componentHeader = [
                                        'type' => 'document',
                                        'document' => [
                                            'link' => $request->header_param ?? $body['components'][0]['example']['header_handle'][0],
                                            ],
                                    ];
                                    $component_header[] = $componentHeader;
                        } elseif($body['components'][0]['type'] === 'HEADER' && $body['components'][0]['format'] === 'VIDEO'  ){
                            
                                        
                                        $componentHeader = [
                                        'type' => 'video',
                                        'video' => [
                                            'link' => $request->header_param ?? $body['components'][0]['example']['header_handle'][0],
                                            ],
                                    ];
                                    $component_header[] = $componentHeader;
                        }
                        else{
                            
                                    $component_header = [];
                            }
                            // Assuming $reply->parameters contains the JSON data
                            
                            
                            if ($body['components'][0]['type'] === 'BODY' || $body['components'][1]['type'] === 'BODY'  ) {
                            if (!empty($request->body_param)){
                            $bodyString = $request->body_param;
                            
                            $component_body = [];
                            

                            foreach ($bodyString as $value) {
                                
                            $componentBody = [
                            'type' => 'text',
                            'text' => $value,
                            ];

                            $component_body[] = $componentBody;
                        }
                    }}
                    $components = new Component($component_header, $component_body, $component_buttons);
                    try{
                    $response= $whatsapp_app_cloud_api->sendTemplate($request->phone, $templateName, $language, $components);
                    $status = customFunction('ReadReceipt', 'status', $response);
                
                if ($status->status() === 200 && getUserPlanData('read_receipt') === true) {
                    $wamid = $status->getData(true)['wamid'];
                }else{
                    $wamid = uniqid('chat_', true);
                }
                     $logs['user_id']=Auth::id();
                    $logs['cloudapi_id']=$cloudapi->id;
                    $logs['from']=$cloudapi->phone ?? null;
                    $logs['to']=$request->phone;
                    $logs['type']='single-send';
                    $logs['template_id']=$template->id ?? null;
                    $logs['wamid'] = $wamid;
                    $this->saveLog($logs);
                    $this->saveMessageToUserChat($wamid,$userChat,$template->title,$template->type, $request->phone, $cloudapi->id);
                    if (getUserPlanData('wallet_system') === true){
                            customFunction('WalletSystem', 'CreditRate', $user);
                       }
                    return response()->json([
                                'message' => __("Message send Successfully"),
                            ], 200);
                    }catch(\Netflie\WhatsAppCloudApi\Response\ResponseException $e){
                        $errorDetails = $e->getMessage(); // This gets the JSON string
                        $errorObject = json_decode($errorDetails); // Decode into an object
    
                        // Now, access the nested message property
                        $errorMessage = isset($errorObject->error->message) ? $errorObject->error->message : 'An unknown error occurred';
                        return response()->json([
                            'message' => $errorMessage
                                        ],500);
                    }
                } else {
                    try{
                    $response = $whatsapp_app_cloud_api-> sendTemplate($request->phone, $templateName, $body['language']);
                    $status = customFunction('ReadReceipt', 'status', $response);
                
                if ($status->status() === 200 && getUserPlanData('read_receipt') === true) {
                    $wamid = $status->getData(true)['wamid'];
                }else{
                    $wamid = uniqid('chat_', true);
                }
                    $logs['user_id']=Auth::id();
                    $logs['cloudapi_id']=$cloudapi->id;
                    $logs['from']=$cloudapi->phone ?? null;
                    $logs['to']=$request->phone;
                    $logs['type']='single-send';
                    $logs['template_id']=$template->id ?? null;
                    $logs['wamid'] = $wamid;
                    $this->saveMessageToUserChat($wamid,$userChat,$template->title,$template->type, $request->phone, $cloudapi->id);
                    if (getUserPlanData('wallet_system') === true){
                            customFunction('WalletSystem', 'CreditRate', $user);
                       }
                    return response()->json([
                                'message' => __("Message send Successfully"),
                            ], 200);
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
        elseif ($type == 'text-with-location') {
            $validated = $request->validate([
                'degreesLatitude' => 'required|max:100',
                'degreesLongitude' => 'required|max:100',
            ]);

            $longitude = $request->degreesLongitude;
            $latitude = $request->degreesLatitude;
            $name = '';
            $address = '';

            try {
                $response = $whatsapp_app_cloud_api->sendLocation($phone, $longitude, $latitude, $name, $address);
                $status = customFunction('ReadReceipt', 'status', $response);
                
                if ($status->status() === 200 && getUserPlanData('read_receipt') === true) {
                    $wamid = $status->getData(true)['wamid'];
                }else{
                    $wamid = uniqid('chat_', true);
                }
                 $logs['user_id']=Auth::id();
                       $logs['cloudapi_id']=$cloudapi->id;
                       $logs['from']=$cloudapi->phone;
                       $logs['to']=$phone;
                       $logs['type']='single-send';
                       $logs['wamid'] = $wamid;
                       $this->saveLog($logs);
                       $this->saveMessageToUserChat($wamid, $userChat,'Location Message',$type,$phone, $cloudapi->id);
                       return response()->json([
                                'message' => __('Message sent successfully..!!'),
                            ], 200);
            
            } catch (\Netflie\WhatsAppCloudApi\Response\ResponseException $e) {
            return response()->json([
                                'message' => $e->rawResponse(),
                            ], $e->httpStatusCode());
            }

        }
        elseif ($type == 'text-with-list') {
            $validated = $request->validate([
                'header_title' => 'required|max:30',
                'message' => 'required|max:300',
                'footer_text' => 'required|max:30',
                'button_text' => 'required|max:30',
                'section.*' => 'required|max:1000',

            ]);
            $header_title = $request->header_title;
            $message = $request->message;
            $footer = $request->footer_text;
            $button_text =  $request->button_text;
            $is_valid= count($request->section ?? []) > 20 ? false : true;
            $error_message=__('Maximum Section Limit Is 20');

            if ($is_valid == false) {
                return response()->json([
                    'message' => $error_message,
                ], 403);
            }

            foreach ($request->section as $key => $section) {
            
               if (count($section['value'] ?? []) == 0) {
                   $is_valid=false;
                   $error_message=__('Fill up the list option value');

                   break;
               }
               elseif ($section['title'] == null || !$section['title']) {
                   $is_valid=false;
                   $error_message=__('Fill up all the title field');

                   break;
               }
               elseif (strlen($section['title']) > 50) {
                   $is_valid=false;
                   $error_message=__('Maximum title limit is 50');

                   break;
               }
               else{
                $ar = 1;
                foreach ($section['value'] as $value_key => $value) {
                    if (empty($value['title'])) {
                     $is_valid=false;
                     $error_message=__('Option title is required');

                     break;
                    }
                    elseif (strlen($value['title']) > 50) {
                     $is_valid=false;
                     $error_message=__('List value name maximum word limit is 50');

                     break;
                    }
                    elseif (strlen($value['description']) > 50) {
                        $is_valid=false;
                        $error_message=__('List value description maximum word limit is 50');

                        break;
                    }
                    $ar++;
                    $rows[] = new Row($ar, $value['title'], $value['description']);
                    
                }
               }
            }
            
            
            if ($is_valid == false) {
                return response()->json([
                    'message' => $error_message,
                ], 403);
            }

            $sections = [new Section('Stars', $rows)];
            $action = new Action($button_text, $sections);

            


            try {
                $response = $whatsapp_app_cloud_api->sendList($phone, $header_title,$message,$footer,$action);
                $status = customFunction('ReadReceipt', 'status', $response);
                
                if ($status->status() === 200 && getUserPlanData('read_receipt') === true) {
                    $wamid = $status->getData(true)['wamid'];
                }else{
                    $wamid = uniqid('chat_', true);
                }
                     $logs['user_id']=Auth::id();
                       $logs['cloudapi_id']=$cloudapi->id;
                       $logs['from']=$cloudapi->phone;
                       $logs['to']=$phone;
                       $logs['type']='single-send';
                       $logs['wamid'] = $wamid;
            
                       $this->saveLog($logs);
                       $this->saveMessageToUserChat($wamid,$userChat,$message,$type,$phone, $cloudapi->id);
                       return response()->json([
                                'message' => __("Message send Successfully"),
                            ], 200);
            
            } catch (\Netflie\WhatsAppCloudApi\Response\ResponseException $e) {
                return response()->json([
                                    'message' => $e->rawResponse(),
                                ], $e->httpStatusCode());
                }

        }
        elseif ($type == 'text-with-button'){
            
            $validated = $request->validate([
                'footer_text' => 'required|max:100',
                'buttons.*' => 'required|max:50',
                'message' => 'required|max:1000',
            ]);

             if (count($request->buttons) > 3) {
                return response()->json([
                    'message' => __('Maximum Button Limit Is 3'),
                ], 403);
             }
             $message = $request->message;
             $row = [];
             
             foreach ($request->buttons as $index => $label) {
            $id = 'button-' . ($index + 1);
            $button = new Button($id, $label);
            $rows[] = $button;
            }
            $action = new ButtonAction($rows);
            
            try {
                $response = $whatsapp_app_cloud_api->sendButton($phone,$message,$action, $request->header_text ?? null, $request->footer_text ?? null);
                $status = customFunction('ReadReceipt', 'status', $response);
                
                if ($status->status() === 200 && getUserPlanData('read_receipt') === true) {
                    $wamid = $status->getData(true)['wamid'];
                }else{
                    $wamid = uniqid('chat_', true);
                }
                $logs['user_id']=Auth::id();
                       $logs['cloudapi_id']=$cloudapi->id;
                       $logs['from']=$cloudapi->phone;
                       $logs['to']=$phone;
                       $logs['type']='text-with-buttons';
                       $logs['wamid'] = $wamid;
            
                       $this->saveLog($logs);
                       $this->saveMessageToUserChat($wamid,$userChat,$message,$type,$phone, $cloudapi->id);
                       return response()->json([
                                'message' => __("Message send Successfully"),
                            ], 200);
            }
            catch (\Netflie\WhatsAppCloudApi\Response\ResponseException $e) {
                return response()->json([
                                    'message' => $e->rawResponse(),
                                ], $e->httpStatusCode());
                }
            
            
             
             
        }
        
        

       

       

        if ($request->templatestatus) {
            $validated = $request->validate([
                'template_name' => 'required|max:100',
            ]);

           $template=$this->saveTemplate($request->all(), $request->message,$type,Auth::id());
           if ($template == false) {
               return response()->json([
                    'message' => __('Maximum Template Limit Exceeded'),
                ], 403);
           }
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
    }




}
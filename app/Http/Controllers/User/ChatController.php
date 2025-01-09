<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CloudApi;
use App\Models\Template;
use App\Models\ChatMessage;
use App\Models\Notification;
use App\Models\User;
use App\Models\Contact;
use Auth;
use DB;
use Carbon\Carbon;
use App\Traits\Cloud;
use Netflie\WhatsAppCloudApi\WhatsAppCloudApi;
use Netflie\WhatsAppCloudApi\Message\OptionsList\Row;
use Netflie\WhatsAppCloudApi\Message\OptionsList\Section;
use Netflie\WhatsAppCloudApi\Message\OptionsList\Action;
use Netflie\WhatsAppCloudApi\Message\Media\LinkID;
use Netflie\WhatsAppCloudApi\Message\Media\MediaObjectID;
use Netflie\WhatsAppCloudApi\Message\Template\Component;
use Netflie\WhatsAppCloudApi\Message\ButtonReply\Button;
use Netflie\WhatsAppCloudApi\Message\ButtonReply\ButtonAction;
class ChatController extends Controller
{
    use Cloud;
    
    protected $user;
    
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = User::where('id', Auth::id())->first();

            return $next($request);
        });
    }

    public function chats($id)
    {
        $cloudapi=CloudApi::where('user_id', $this->user->id)->orWhere('user_id', $this->user->team_id)->where('status',1)->where('uuid',$id)->first();
        abort_if(empty($cloudapi),404);
        $templates = Template::where('user_id', $this->user->id)->orWhere('user_id', $this->user->team_id)->where('status',1)->latest()->get();
        $team = User::where('team_id', $this->user->id)->get();
        return view('user.chats.list2',compact('cloudapi','templates', 'team'));
    }
    
    public function chats2($id)
    {
        $cloudapi=CloudApi::where('user_id', $this->user->id)->orWhere('user_id', $this->user->team_id)->where('status',1)->where('uuid',$id)->first();
        abort_if(empty($cloudapi),404);
        $templates = Template::where('user_id', $this->user->id)->orWhere('user_id', $this->user->team_id)->where('status',1)->latest()->get();
        $team = User::where('team_id', $this->user->id)->get();
        return view('user.chats.list',compact('cloudapi','templates','team'));
    }

    public function chatHistory($id, Request $request){
        
    $cloudapi = CloudApi::where('user_id', $this->user->id)->orWhere('user_id', $this->user->team_id)->where('status', 1)->where('uuid', $id)->first();
    abort_if(empty($cloudapi), 404);
    $lastReceivedTimestamp = $request->input('last_received_timestamp', 0);
    $page = $request->input('page', 1);
    $perPageItem = $request->input('perpageitem', 5);
    
     if($this->user->team_id && json_decode($this->user->meta)->chat == 'true'){
        $allowedChatNumbers = explode(',', json_decode($this->user->meta)->allowed_chat ?? []);
        $chatHistory = ChatMessage::where('cloudapi_id', $cloudapi->id)
        ->whereIn('phone_number', $allowedChatNumbers)->where('updated_at', '>', Carbon::createFromTimestamp($lastReceivedTimestamp))->orderByDesc('updated_at')
        ->paginate($perPageItem, ['*'], 'page', $page);
        $formattedHistory = $chatHistory->map(function ($item) {
        $contact = Contact::where('user_id', $this->user->id)->orWhere('user_id', $this->user->team_id)->where('phone', $item->phone_number)->first();
        $name = $contact ? $contact->name : null;
        $messageHistory = json_decode($item->message_history, true);
        $lastMessage = end($messageHistory);

        return [
            'id' => $item->id,
            'cloudapi_id' => $item->cloudapi_id,
            'phone_number' => $item->phone_number,
            'lastmessage' => $lastMessage,
            'name' => $name,
            'follow_up'=> $item->follow_up,
            'pinned'=> $item->pinned,
            'counts' => $item->counts,
            'created_at' => $item->created_at,
            'updated_at' => $item->updated_at,
        ];
    });
        
    }else if($this->user->team_id && json_decode($this->user->meta)->chat == 'false'){
        return response()->json(['message' => 'Chat message not found'], 404);
    }else{
        $chatHistory = ChatMessage::where('cloudapi_id', $cloudapi->id)->where('updated_at', '>', Carbon::createFromTimestamp($lastReceivedTimestamp))->orderByDesc('updated_at')->paginate($perPageItem, ['*'], 'page', $page);
        
    $formattedHistory = $chatHistory->map(function ($item) {
        $contact = Contact::where('user_id', $this->user->id)->where('phone', $item->phone_number)->first();
        $name = $contact ? $contact->name : null;

        $messageHistory = json_decode($item->message_history, true);
        $lastMessage = end($messageHistory);
        return [
            'id' => $item->id,
            'cloudapi_id' => $item->cloudapi_id,
            'phone_number' => $item->phone_number,
            'lastmessage' => $lastMessage,
            'name' => $name,
            'follow_up'=> $item->follow_up,
            'pinned'=> $item->pinned,
            'counts' => $item->counts,
            'created_at' => $item->created_at,
            'updated_at' => $item->updated_at,
        ];
    });
    }
    return response()->json($formattedHistory);
}



public function chatMessage($number, $id){
    
    $cloudapi = CloudApi::where('user_id', $this->user->id)
        ->orWhere('user_id', $this->user->team_id)
        ->where('status', 1)
        ->where('uuid', $id)
        ->first();
        
    $whatsapp_cloud_api = new WhatsAppCloudApi([
        'from_phone_number_id' => $cloudapi->phone_number_id,
        'access_token' => $cloudapi->access_token,
    ]);
        

    $chatHistory = ChatMessage::where('cloudapi_id', $cloudapi->id)
        ->where('phone_number', $number)
        ->first();
        
        $messageHistory = json_decode($chatHistory->message_history, true);
        $lastReceivedDeliveredMessage = null;
        foreach ($messageHistory as &$message) {
            if ($message['type'] === 'received' && isset($message['status']) && $message['status'] === 'delivered') {
                $lastReceivedDeliveredMessage = $message;
            }
        }
        
        // Check if there is a last message to mark as read
        try {
            if ($lastReceivedDeliveredMessage !== null) {
                $whatsapp_cloud_api->markMessageAsRead($lastReceivedDeliveredMessage['chatID']);
        
                // Update the status to 'seen'
                $lastReceivedDeliveredMessage['status'] = 'read';
            }
        } catch (\Netflie\WhatsAppCloudApi\Response\ResponseException $e) {
            // For now, just logging the error message
            error_log("Error marking message as read: " . $e->getMessage());
        }
        
        $chatHistory->message_history = json_encode($messageHistory);
        $chatHistory->counts = 0;
        // Save the changes to the ChatMessage model
        $chatHistory->save();

        Notification::where('user_id', $this->user->id)->orWhere('user_id', $this->user->team_id)->where('comment', 'user-message')->update(['seen' => 1]);

    return [
        'message' => [json_decode($chatHistory->message_history)]
    ];
}



public function updateTagLabel(Request $request, $id){
    $cloudapi = CloudApi::where('user_id', $this->user->id)->orWhere('user_id', $this->user->team_id)->where('status', 1)->where('uuid', $id)->first();
    $chatMessage = ChatMessage::where('cloudapi_id', $cloudapi->id)->where('phone_number', $request->phone)->first();

    if ($chatMessage) {
        $chatMessage->follow_up = $request->tag;
        $chatMessage->save();
        return response()->json(['message' => 'Follow-up tag updated successfully']);
    } else {
        return response()->json(['message' => 'Chat message not found'], 404);
    }
}

public function updatePinned(Request $request, $id){
    
    $cloudapi = CloudApi::where('user_id', $this->user->id)->orWhere('user_id', $this->user->team_id)->where('status', 1)->where('uuid', $id)->first();
    $chatMessage = ChatMessage::where('cloudapi_id', $cloudapi->id)->where('phone_number', $request->phone)->first();

    if ($chatMessage) {
        $chatMessage->pinned = $request->value;
        $chatMessage->save();
        return response()->json(['message' => 'Updated successfully']);
    } else {
        return response()->json(['message' => 'Chat message not found'], 404);
    }
}


public function updateMute(Request $request, $id){
    
    $cloudapi = CloudApi::where('user_id', $this->user->id)->orWhere('user_id', $this->user->team_id)->where('status', 1)->where('uuid', $id)->first();
    $chatMessage = ChatMessage::where('cloudapi_id', $cloudapi->id)->where('phone_number', $request->phone)->first();

    if ($chatMessage) {
        $chatMessage->notification = $request->value;
        $chatMessage->save();
        return response()->json(['message' => 'Updated successfully']);
    } else {
        return response()->json(['message' => 'Chat message not found'], 404);
    }
}


    public function sendMessage(Request $request, $id)
{
    
    
    $cloudapi = CloudApi::where('user_id', $this->user->id)->orWhere('user_id', $this->user->team_id)
        ->where('status', 1)
        ->where('uuid', $id)
        ->first();

        $userChat = ChatMessage::where('phone_number', $request->receiver)
    ->where('cloudapi_id', $cloudapi->id)
    ->first();

    $whatsapp_app_cloud_api = new WhatsAppCloudApi([
        'from_phone_number_id' => $cloudapi->phone_number_id,
        'access_token' => $cloudapi->access_token,
    ]);

    if (getUserPlanData('messages_limit') == false) {
        return response()->json([
            'message' => __('Maximum Monthly Messages Limit Exceeded')
        ], 401);
    }

    abort_if(empty($cloudapi), 404);

    $validated = $request->validate([
        'receiver' => 'required|max:20',
        'message' => 'required'
    ]);
    
    $file = $request->file('fileInput');
    if ($file !== null && is_a($file, 'Illuminate\Http\UploadedFile') && $file->isValid()) {
    $fileType = $file->getMimeType();
    
    if (in_array($fileType, ['application/pdf', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'text/csv', 'text/plain'])) {
        $file = $this->saveFile($request, 'fileInput');
        $fileExt = $this->saveFileExt($request, 'fileInput');
        $request['attachment'] = $file;
        $document = $file;
        $document_name = $fileExt;
        $message = $request->message;
        
        $link_id = new LinkID($document);
        
        try {
                $response = $whatsapp_app_cloud_api->sendDocument($request->receiver, $link_id, $document_name, $message);
                
                $status = customFunction('ReadReceipt', 'status', $response);
                if ($status->status() === 200 && getUserPlanData('read_receipt') === true){
                    $wamid = $status->getData(true)['wamid'];
                }else{
                    $wamid = uniqid('chat_', true);
                }
                
                $logs["user_id"] = Auth::id();
                $logs["cloudapi_id"] = $cloudapi->id;
                $logs["from"] = $cloudapi->phone ?? null;
                $logs["to"] = $request->receiver;
                $logs["template_id"] = $template->id ?? null;
                $logs["type"] = "live-chat";
                $logs["wamid"] = $wamid;
                $this->saveLog($logs);
                if ($userChat) {
        $messageHistory = json_decode($userChat->message_history, true) ?? [];
       
        $newMessage = [
            'chatID' => $wamid,
            'message' => $document.'\nCaption:'.$message ?? null,
            'timestamp' => now()->toDateTimeString(),
            'status' => 'sent',
            'type' => 'sent',
        ];

        $messageHistory[] = $newMessage;

        // Update the message history in the database
        $userChat->message_history = json_encode($messageHistory);
        $userChat->save();
    }else{
        $newUserChat = new ChatMessage();
    $newUserChat->phone_number = $request->receiver;
    $newUserChat->cloudapi_id = $cloudapi->id;
    $newUserChat->message_history = json_encode([
        [
            'chatID' => $wamid,
            'message' => $document.'\nCaption:'.$message ?? null,
            'timestamp' => now()->toDateTimeString(),
            'status' => 'sent',
            'type' => 'sent',
        ]
    ]);
    $newUserChat->save();

    }
    return response()->json([
            'message' => __('Message sent successfully..!!'),
        ], 200);
    } catch (Exception $e) {
        return response()->json([
            'error' => 'Request Failed',
        ], 401);
    }
}elseif (in_array($fileType, ['image/jpeg', 'image/png', 'image/gif'])) {
    $validated = $request->validate([
            'fileInput' => 'required|mimes:jpg,jpeg,png|max:3000',
        ]);
        $image = $this->saveFile($request, 'fileInput');
        $link_id = new LinkID($image);
        $caption = $request->message;
        try {
                $response = $whatsapp_app_cloud_api->sendImage($request->receiver, $link_id, $caption);
                $status = customFunction('ReadReceipt', 'status', $response);
                
                if ($status->status() === 200 && getUserPlanData('read_receipt') === true) {
                    $wamid = $status->getData(true)['wamid'];
                }else{
                    $wamid = uniqid('chat_', true);
                }
                $logs["user_id"] = Auth::id();
                $logs["cloudapi_id"] = $cloudapi->id;
                $logs["from"] = $cloudapi->phone ?? null;
                $logs["to"] = $request->receiver;
                $logs["template_id"] = $template->id ?? null;
                $logs["type"] = "live-chat";
                $logs["wamid"] = $wamid;
                $this->saveLog($logs);
                //$chatID = $id;
                if ($userChat) {
        $messageHistory = json_decode($userChat->message_history, true) ?? [];
       
        $newMessage = [
            'chatID' => $wamid,
            'message' => $image.'\nCaption:' .$caption ?? null,
            'timestamp' => now()->toDateTimeString(),
            'status' => 'sent',
            'type' => 'sent',
        ];

        $messageHistory[] = $newMessage;

        // Update the message history in the database
        $userChat->message_history = json_encode($messageHistory);
        $userChat->save();
    }else{
        $newUserChat = new ChatMessage();
    $newUserChat->phone_number = $request->receiver;
    $newUserChat->cloudapi_id = $cloudapi->id;
    $newUserChat->message_history = json_encode([
        [
            'chatID' => $wamid,
            'message' => $image.'\nCaption:' .$caption ?? null,
            'timestamp' => now()->toDateTimeString(),
            'status' => 'sent',
            'type' => 'sent',
        ]
    ]);
    $newUserChat->save();

    }
    return response()->json([
            'message' => __('Message sent successfully..!!'),
        ], 200);
    } catch (Exception $e) {
        return response()->json([
            'error' => 'Request Failed',
        ], 401);
    }
}elseif (in_array($fileType, ['video/mp4'])) {
    $validated = $request->validate([
            'fileInput' => 'required|mimes:mp4|max:14000',
        ]);
        $video = $this->saveFile($request, 'fileInput');
        $link_id = new LinkID($video);
        $caption = $request->message;
         try {
                $response = $whatsapp_app_cloud_api->sendVideo($request->receiver, $link_id, $caption);
                $status = customFunction('ReadReceipt', 'status', $response);
                
                if ($status->status() === 200 && getUserPlanData('read_receipt') === true) {
                    $wamid = $status->getData(true)['wamid'];
                }else{
                    $wamid = uniqid('chat_', true);
                }
                
                $logs["user_id"] = Auth::id();
                $logs["cloudapi_id"] = $cloudapi->id;
                $logs["from"] = $cloudapi->phone ?? null;
                $logs["to"] = $request->receiver;
                $logs["template_id"] = $template->id ?? null;
                $logs["type"] = "live-chat";
                $logs["wamid"] = $wamid;
                $this->saveLog($logs);
                
                $chatID = $id;
                if ($userChat) {
        $messageHistory = json_decode($userChat->message_history, true) ?? [];
       
        $newMessage = [
            'chatID' => $wamid,
            'message' => $video.'\nCaption:'.$caption ?? null,
            'timestamp' => now()->toDateTimeString(),
            'status' => 'sent',
            'type' => 'sent',
        ];

        $messageHistory[] = $newMessage;

        // Update the message history in the database
        $userChat->message_history = json_encode($messageHistory);
        $userChat->save();
    }else{
        $newUserChat = new ChatMessage();
    $newUserChat->phone_number = $request->receiver;
    $newUserChat->cloudapi_id = $cloudapi->id;
    $newUserChat->message_history = json_encode([
        [
            'chatID' => $wamid,
            'message' => $video.'\nCaption:'.$caption ?? null,
            'timestamp' => now()->toDateTimeString(),
            'status' => 'sent',
            'type' => 'sent',
        ]
    ]);
    $newUserChat->save();

    }
    return response()->json([
            'message' => __('Message sent successfully..!!'),
        ], 200);
    } catch (Exception $e) {
        return response()->json([
            'error' => 'Request Failed',
        ], 401);
    }
                
}elseif (in_array($fileType, ['audio/mpeg', 'audio/ogg', 'audio/wav'])) {
    $validated = $request->validate([
            'fileInput' => 'required|mimes:mp3,ogg,wav|max:500',
        ]);
        $audio = $this->saveFile($request, 'fileInput');
        $link_id = new LinkID($audio);
        try {
                $response = $whatsapp_app_cloud_api->sendAudio($request->receiver, $link_id);
                //$jsonResponse = json_decode($response->body(), true);
                //$id = $jsonResponse['messages'][0]['id'];
                
                 $status = customFunction('ReadReceipt', 'status', $response);
                
                if ($status->status() === 200 && getUserPlanData('read_receipt') === true) {
                    $wamid = $status->getData(true)['wamid'];
                }else{
                    $wamid = uniqid('chat_', true);
                }
                
                $logs["user_id"] = Auth::id();
                $logs["cloudapi_id"] = $cloudapi->id;
                $logs["from"] = $cloudapi->phone ?? null;
                $logs["to"] = $request->receiver;
                $logs["template_id"] = $template->id ?? null;
                $logs["type"] = "live-chat";
                $logs["wamid"] = $wamid;
                $this->saveLog($logs);
                if ($userChat) {
        $messageHistory = json_decode($userChat->message_history, true) ?? [];
       
        $newMessage = [
            'chatID' => $wamid,
            'message' => $audio.'\nCaption:'.$request->message ?? null,
            'timestamp' => now()->toDateTimeString(),
            'status' => 'sent',
            'type' => 'sent',
        ];

        $messageHistory[] = $newMessage;

        // Update the message history in the database
        $userChat->message_history = json_encode($messageHistory);
        $userChat->save();
    }else{
        $newUserChat = new ChatMessage();
    $newUserChat->phone_number = $request->receiver;
    $newUserChat->cloudapi_id = $cloudapi->id;
    $newUserChat->message_history = json_encode([
        [
            'chatID' => $wamid,
            'message' => $audio.'\nCaption:'.$request->message ?? null,
            'timestamp' => now()->toDateTimeString(),
            'status' => 'sent',
            'type' => 'sent',
        ]
    ]);
    $newUserChat->save();

    }
    return response()->json([
            'message' => __('Message sent successfully..!!'),
        ], 200);
    } catch (Exception $e) {
        return response()->json([
            'error' => 'Request Failed',
        ], 401);
    }
}

}else{
    try {
        $response = $whatsapp_app_cloud_api->sendTextMessage($request->receiver, $request->message, true);
         //$jsonResponse = json_decode($response->body(), true);
        //$id = $jsonResponse['messages'][0]['id'];
        $status = customFunction('ReadReceipt', 'status', $response);
                //dd($status);
                if ($status->status() === 200 && getUserPlanData('read_receipt') === true) {
                    $wamid = $status->getData(true)['wamid'];
                }else{
                    $wamid = uniqid('chat_', true);
                }
                
        $logs["user_id"] = Auth::id();
                $logs["cloudapi_id"] = $cloudapi->id;
                $logs["from"] = $cloudapi->phone ?? null;
                $logs["to"] = $request->receiver;
                $logs["template_id"] = $template->id ?? null;
                $logs["type"] = "live-chat";
                $logs["wamid"] = $wamid;
                $this->saveLog($logs);
        if ($userChat) {
        $messageHistory = json_decode($userChat->message_history, true) ?? [];
       
        $newMessage = [
            'chatID' => $wamid,
            'message' => $request->message,
            'timestamp' => now()->toDateTimeString(),
            'status' => 'sent',
            'type' => 'sent',
        ];

        $messageHistory[] = $newMessage;

        // Update the message history in the database
        $userChat->message_history = json_encode($messageHistory);
        $userChat->save();
    }else{
        $newUserChat = new ChatMessage();
    $newUserChat->phone_number = $request->receiver;
    $newUserChat->cloudapi_id = $cloudapi->id;
    $newUserChat->message_history = json_encode([
        [
            'chatID' => $wamid,
            'message' => $request->message,
            'timestamp' => now()->toDateTimeString(),
            'status' => 'sent',
            'type' => 'sent',
        ]
    ]);
    $newUserChat->save();

    }

        return response()->json([
            'message' => __('Message sent successfully..!!'),
        ], 200);
    } catch (Exception $e) {
        return response()->json([
            'error' => 'Request Failed',
        ], 401);
    }
}

}


public function clearMessages(Request $request, $id) {
    $cloudapi = CloudApi::where('user_id', $this->user->id)
                        ->orWhere('user_id', $this->user->team_id)
                        ->where('status', 1)
                        ->where('uuid', $id)
                        ->first();

    if (!$cloudapi) {
        return response()->json(['status' => 'error', 'message' => 'Cloud API not found'], 404);
    }

    $chatMessage = ChatMessage::where('cloudapi_id', $cloudapi->id)
                              ->where('phone_number', $request->phone)
                              ->first();

    if (!$chatMessage) {
        return response()->json(['status' => 'error', 'message' => 'No messages found for the specified phone number'], 404);
    }

    $messageHistory = json_decode($chatMessage->message_history, true);

    if (is_array($messageHistory) && !empty($messageHistory)) {
        $firstMessage = array_shift($messageHistory); // Get the first message

        // Re-encode the first message as an array
        $chatMessage->message_history = json_encode([$firstMessage]); // Ensure it's an array
        $chatMessage->save();

        return response()->json(['status' => 'success', 'message' => 'Messages cleared successfully, except the first one']);
    } else {
        return response()->json(['status' => 'error', 'message' => 'No messages to clear'], 404);
    }
}




}
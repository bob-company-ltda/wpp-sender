<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Smstransaction;
use App\Models\ChatMessage;
use App\Models\Template;
use App\Models\App;
use Carbon\Carbon;
use Auth;
class LogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $logs=Smstransaction::where('user_id',Auth::id())
              ->with('cloudapi','app','template')
              ->latest()
              ->paginate(30);
        
        $cid = Smstransaction::where('user_id', Auth::id())->latest()->first();

if ($cid) { // Check if $cid is not null
    $chatmessageCount = ChatMessage::where('cloudapi_id', $cid->cloudapi_id)
                                    ->whereNotNull('follow_up')
                                    ->count();
} else {
    $chatmessageCount = 0; // Set to 0 if no Smstransaction found
}
        $total_messages=Smstransaction::where('user_id',Auth::id())->count();
        $today_messages=Smstransaction::where('user_id',Auth::id())
                        ->whereRaw('date(created_at) = ?', [Carbon::now()->format('Y-m-d')] )
                        ->count();
        $last30_messages=Smstransaction::where('user_id',Auth::id())
                            ->where('created_at', '>', now()
                            ->subDays(30)
                            ->endOfDay())
                            ->count();
        $failed = Smstransaction::where('user_id',Auth::id())->where('status', 'failed')->count();
        $bulk_shoot = Smstransaction::where('user_id',Auth::id())->where('type', 'bulk-message')->count();
        $chatbot_replies = Smstransaction::where('user_id',Auth::id())->where('type', 'chatbot')->count();
        $single_send = Smstransaction::where('user_id',Auth::id())->where('type', 'single-send')->count();
        $live_chat = Smstransaction::where('user_id',Auth::id())->where('type', 'live-chat')->count();
        $campaign = Smstransaction::where('user_id',Auth::id())->where('type', 'campaign')->count();
        $templates = Template::where('user_id',Auth::id())->where('type', 'meta-template')->count();
        $apps = App::where('user_id', Auth::id())->count();

        return view('user.log.index',compact('logs','total_messages','today_messages','last30_messages','failed','bulk_shoot','chatbot_replies','single_send','live_chat','templates','apps','chatmessageCount','campaign'));
    }

}

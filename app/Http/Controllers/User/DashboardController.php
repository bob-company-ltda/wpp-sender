<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\CloudApi;
use App\Models\Smstransaction;
use App\Models\ChatMessage;
use App\Models\Schedulemessage;
use App\Models\Contact;
use App\Models\Template;
use Carbon\Carbon;
use Auth;
use Session;

class DashboardController extends Controller
{
    public function index()
    {
        
        
        
        if (Auth::user()->will_expire != null) {
            $nextDate= Carbon::now()->addDays(7)->format('Y-m-d');
            if (Auth::user()->will_expire <= now()) {
                Session::flash('saas_error', __('Your subscription was expired at '.Carbon::parse(Auth::user()->will_expire)->diffForHumans().' please renew the subscription'));
            }

            elseif(Auth::user()->will_expire <= $nextDate){
                Session::flash('saas_error', __('Your subscription is ending in '.Carbon::parse(Auth::user()->will_expire)->diffForHumans()));
            }
        }
       
        return view('user.dashboard');
    }
    
    public function index2()
    {
        
        
        
        if (!Auth::user()->team_id && Auth::user()->will_expire != null) {
    $cloudapi = CloudApi::where('user_id', Auth::id())->first();
    $phone = $cloudapi->phone ?? null;
    $nextDate = Carbon::now()->addDays(7)->format('Y-m-d');

    $plan = Auth::user()->plan ? json_decode(Auth::user()->plan) : null;
    $credit = Auth::user()->wallet ?? 0;
    $creditRate = get_option('credit_rate');
    $currency = get_option('base_currency',true);

    $mostUsedTemplateId = Smstransaction::where('user_id', Auth::id())
        ->whereDate('created_at', '>', Carbon::now()->subDays(30))
        ->whereNotNull('template_id')
        ->groupBy('template_id')
        ->select('template_id', DB::raw('COUNT(*) as template_count'))
        ->orderByDesc('template_count')
        ->first();

    $chatMessages = $cloudapi
        ? ChatMessage::where('cloudapi_id', $cloudapi->id)->orderBy('updated_at', 'desc')->take(4)->get()
        : collect();

    $templates = $mostUsedTemplateId
        ? Template::where('id', $mostUsedTemplateId->template_id)->first()
        : null;

    if (Auth::user()->will_expire <= now()) {
        Session::flash('saas_error', __('Your subscription expired '.Carbon::parse(Auth::user()->will_expire)->diffForHumans().' ago. Please renew the subscription.'));
    } elseif (Auth::user()->will_expire <= $nextDate) {
        Session::flash('saas_error', __('Your subscription is ending in '.Carbon::parse(Auth::user()->will_expire)->diffForHumans().'.'));
    }

    return view('user.dashboard2', compact('plan', 'mostUsedTemplateId', 'templates', 'chatMessages', 'phone','credit','creditRate','currency'));
} elseif (Auth::user()->will_expire != null && Auth::user()->team_id) {
    $nextDate = Carbon::now()->addDays(7)->format('Y-m-d');

    if (Auth::user()->will_expire <= now()) {
        Session::flash('saas_error', __('Your subscription expired '.Carbon::parse(Auth::user()->will_expire)->diffForHumans().' ago. Please renew the subscription.'));
    } elseif (Auth::user()->will_expire <= $nextDate) {
        Session::flash('saas_error', __('Your subscription is ending in '.Carbon::parse(Auth::user()->will_expire)->diffForHumans().'.'));
    }

    return view('user.dashboard');
}elseif (Auth::user()->will_expire == null){
    return view('user.dashboard');
}
        
        
       
        
    }

    public function dashboardData()
    {
        
        $data['cloudapisCount'] = CloudApi::where('user_id',Auth::id())->count();
        $data['messagesCount'] = Smstransaction::where('user_id',Auth::id())->count();
        $data['contactCount'] = Contact::where('user_id',Auth::id())->count();
        $data['scheduleCount'] = Schedulemessage::where('status','pending')->where('user_id',Auth::id())->count();
        
        $data['cloudapis'] = CloudApi::where('user_id',Auth::id())->withCount('smstransaction')->orderBy('status','DESC')->latest()->get()->map(function($rq){
                $map['uuid']= $rq->uuid;
                $map['name']= $rq->name;
                $map['status']= $rq->status;
                $map['phone']= $rq->phone;
                $map['smstransaction_count']= $rq->smstransaction_count;
                return $map;
        });
        $data['messagesStatics'] = $this->getMessagesTransaction(7);
        $data['typeStatics'] = $this->messagesStatics(7);
        $data['chatbotStatics'] = $this->getChatbotTransaction(7);
        
        
        $data['messagesAnalysis'] = Smstransaction::where('user_id',Auth::id())->whereDate('created_at', '>', Carbon::now()->subDays(30))->count();
        
        $data['readCount'] = Smstransaction::where('user_id', Auth::id())->where('status', 'read')->whereDate('created_at', '>', Carbon::now()->subDays(30))->count();
        $data['deliveredCount'] = Smstransaction::where('user_id', Auth::id())->where('status', 'delivered')->whereDate('created_at', '>', Carbon::now()->subDays(30))->count();
        $data['failedCount'] = Smstransaction::where('user_id', Auth::id())->where('status', 'failed')->whereDate('created_at', '>', Carbon::now()->subDays(30))->count();
        
        
         $data['chatbotMeter'] = Smstransaction::where('user_id',Auth::id())->where('type','chatbot')->whereDate('created_at', '>', Carbon::now()->subDays(30))->count();
         $data['bulkMeter'] = Smstransaction::where('user_id',Auth::id())->where('type','bulk-message')->whereDate('created_at', '>', Carbon::now()->subDays(30))->count();
         $data['singleMeter'] = Smstransaction::where('user_id',Auth::id())->where('type','single-send')->whereDate('created_at', '>', Carbon::now()->subDays(30))->count();

        
        return response()->json($data);

    }

    public function getMessagesTransaction($days)
    {
       $statics= Smstransaction::query()->where('user_id',Auth::id())
                ->whereDate('created_at', '>', Carbon::now()->subDays($days))
                ->orderBy('id', 'asc')
                ->selectRaw('date(created_at) date, 
                     count(*) smstransactions, 
                     CAST(sum(case when status = "read" then 1 else 0 end) AS UNSIGNED) as read_count,
                     CAST(sum(case when status = "delivered" then 1 else 0 end) AS UNSIGNED) as delivered_count,
                     CAST(sum(case when status = "failed" then 1 else 0 end) AS UNSIGNED) as failed_count')
                ->groupBy('date')
                ->get();

        return $statics;
                
    }

    public function getChatbotTransaction($days)
    {
        $statics= Smstransaction::query()
                ->where('user_id',Auth::id())
                ->where('type','chatbot')
                ->whereDate('created_at', '>', Carbon::now()->subDays($days))
                ->orderBy('id', 'asc')
                ->selectRaw('date(created_at) date, count(*) smstransactions')
                ->groupBy('date')
                ->get();

        return $statics;
    }
    
    public function getBulkTransaction($days)
    {
        $statics= Smstransaction::query()
                ->where('user_id',Auth::id())
                ->where('type','bulk-message')
                ->whereDate('created_at', '>', Carbon::now()->subDays($days))
                ->orderBy('id', 'asc')
                ->selectRaw('date(created_at) date, count(*) smstransactions')
                ->groupBy('date')
                ->get();

        return $statics;
    }
    
    public function getSingleTransaction($days)
    {
        $statics= Smstransaction::query()
                ->where('user_id',Auth::id())
                ->where('type','single-send')
                ->whereDate('created_at', '>', Carbon::now()->subDays($days))
                ->orderBy('id', 'asc')
                ->selectRaw('date(created_at) date, count(*) smstransactions')
                ->groupBy('date')
                ->get();

        return $statics;
    }

    public function messagesStatics($days)
    {
        $statics= Smstransaction::query()->where('user_id',Auth::id())
                ->whereDate('created_at', '>', Carbon::now()->subDays($days))
                ->orderBy('id', 'asc')
                ->selectRaw('type type, count(*) smstransactions')
                ->groupBy('type')
                ->get();

        return $statics;
    }
}

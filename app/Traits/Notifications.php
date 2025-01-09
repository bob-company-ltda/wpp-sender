<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Order;
use App\Models\Notification;
use App\Mail\AlertMail;
use Mail;
use Http;
use Carbon\Carbon;
trait Notifications
{
    private function sentOrderMail($data)
    {

    }

    private function createNotification($data)
    {
        $notification          = new Notification;
        $notification->user_id = $data['user_id'];
        $notification->title   = $data['title'];
        $notification->comment = $data['comment'] ?? null;
        $notification->url     = $data['url'];
        $notification->is_admin= $data['is_admin'] ?? 0;
        $notification->save();

    }
    
    private function createNotificationForMessage($data)
{
    $existingNotification = Notification::where('user_id', $data['user_id'])->where('comment', 'user-message')->first();

    if ($existingNotification) {
        // Update existing notification
        $existingNotification->updated_at = now();
        $existingNotification->seen = 0;
        $existingNotification->update([
            'title' => 'you have a new message',
            'comment' => 'user-message',
            'url' => $data['url'],
            'is_admin' => $data['is_admin'] ?? 0,
        ]);
    } else {
        // Create new notification
        $notification = new Notification;
        $notification->user_id = $data['user_id'];
        $notification->title = 'you have a new message';
        $notification->comment = 'user-message';
        $notification->url = $data['url'];
        $notification->is_admin = $data['is_admin'] ?? 0;
        $notification->save();
    }
}


    private function sentWillExpireEmail($data)
    {

       $mailData['name'] = $data['name'];
       $mailData['plan_name'] = $data['subscription']['title'];
       $mailData['plan_id'] = $data['plan_id'];
       $mailData['price'] = amount_format($data['subscription']['price']);
       $mailData['will_expire'] = Carbon::parse($data['will_expire'])->format('F-d-Y');
       $mailData['link'] = '/user/subscription/'.$data['plan_id'];
       $mailData['contents'] = array(
        'Plan Name :' => $data['subscription']['title'],
        'Renewal Price:' => amount_format($data['subscription']['price']),
        'Due Date:' => $mailData['will_expire'],
       );

       $title = 'Subscription revenueal notice';
       $comment = 'Your subscription will end soon the due date is '.$mailData['will_expire'];

       $notification['user_id'] = $data['id'];
       $notification['title']   = $title;
       $notification['comment']   = $comment;
       $notification['url'] = '/user/subscription/'.$data['plan_id'];

       $this->createNotification($notification);

      try {
           Mail::to($data['email'])->send(new AlertMail($mailData));
      } catch (Exception $e) {
          
      }
    }
    
    private function sentWalletEmail($data)
    {

       $mailData['name'] = $data['name'];
       $mailData['plan_name'] = $data['subscription']['title'];
       $mailData['plan_id'] = $data['plan_id'];
       $mailData['price'] = amount_format($data['subscription']['price']);
       $mailData['will_expire'] = Carbon::parse($data['will_expire'])->format('F-d-Y');
       $mailData['contents'] = array(
        'Plan Name :' => $data['subscription']['title'],
        'Renewal Price:' => amount_format($data['subscription']['price']),
        'Due Date:' => $mailData['will_expire'],
       );

       $title = 'Your Credit is about to End.';
       $comment = 'Your Wallet Credit is about to end, please Recharge your wallet. Contact Admin now';

       $notification['user_id'] = $data['id'];
       $notification['title']   = $title;
       $notification['comment']   = $comment;
       $notification['url'] = '/user/subscription/'.$data['plan_id'];

       $this->createNotification($notification);

      try {
           Mail::to($data['email'])->send(new AlertMail($mailData));
      } catch (Exception $e) {
          
      }
    }

}    
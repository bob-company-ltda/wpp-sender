<?php

// app/Http/Controllers/SSEController.php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Pusher\Pusher;
use App\Events\SSEMessage;
use Illuminate\Support\Facades\Config;

class SSEController extends Controller
{
    

public function send(Request $request)
{
    $pusher = new Pusher(
        Config::get('broadcasting.connections.pusher.key'),
        Config::get('broadcasting.connections.pusher.secret'),
        Config::get('broadcasting.connections.pusher.app_id'), [
            'cluster' => Config::get('broadcasting.connections.pusher.options.cluster'),
        ]
    );

    $data['message'] = 'Hello, this is a real-time notification!';
    $pusher->trigger('notifications', 'new-notification', $data);

    return 'Notification sent!';
}

}


<?php

namespace App\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     * 
     */
     protected $listen = [
        'App\Events\NewMessageReceived' => [
            'App\Listeners\ProcessNewMessage',
            // Add any other listeners for this event
        ],
    ];
    
    public function boot()
    {
        Broadcast::routes();

        require base_path('routes/channels.php');
    }
}

<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Smstransaction;

class UpdateMessageStatus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $whatsappMessageId;
    public $status;

    public function __construct($whatsappMessageId, $status)
    {
        $this->whatsappMessageId = $whatsappMessageId;
        $this->status = $status;
        Log::info('wamid status: ' . $status);
    }

    public function handle()
    {
        
        Log::info('Updating Smstransaction for wamid: ' . $this->whatsappMessageId . ', Status: ' . $this->status);

    $affectedRows = Smstransaction::where('wamid', $this->whatsappMessageId)
        ->update(['status' => $this->status]);

    Log::info('Rows updated: ' . $affectedRows);
    }
}

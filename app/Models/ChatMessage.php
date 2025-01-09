<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Str;
class ChatMessage extends Model
{
    use HasFactory;

    
    protected $table = 'chat_messages';

    protected $fillable = [
        'cloudapi_id',
        'phone_number',
        'message_history',
        'user_notes',
        'follow_up',
    ];

    protected $dates = [
        'timestamp',
        'created_at',
        'updated_at',
    ];

   

    public function cloudapi()
    {
        return $this->belongsTo('App\Models\CloudApi');
    }
}

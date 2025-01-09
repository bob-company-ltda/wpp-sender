<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    use HasFactory;
    
    public $timestamps = false;
    protected $fillable = ['key', 'value', 'user_id'];
    
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
    
    public function shouldCache()
    {
        return false;
    }
}

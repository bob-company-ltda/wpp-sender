<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Smstransaction extends Model
{
    use HasFactory;
    protected $table = "smstransactions";

    public function cloudapi()
    {
        return $this->belongsTo('App\Models\CloudApi');
    }

     public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function app()
    {
        return $this->belongsTo('App\Models\App');
    }

    public function template()
    {
        return $this->belongsTo('App\Models\Template');
    }
}

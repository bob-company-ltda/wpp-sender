<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use HasFactory;

    public function cloudapi()
    {
        return $this->belongsTo('App\Models\CloudApi');
    }

    public function template()
    {
        return $this->belongsTo('App\Models\Template');
    }
}

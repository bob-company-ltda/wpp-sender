<?php

namespace App\Models;
use App\Autoload\HasUid;
use App\Autoload\Foreign;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Str;
class CloudApi extends Model
{
    use HasCustomForeignKeyConvention, HasFactory, HasUid;

    protected $table ="cloudapis";

    public $timestamps = false;

     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'uuid','access_token', 'phone_number_id',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function($model){
            $model->uuid = Str::uuid()->toString();
        });
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function smstransaction()
    {
        return $this->hasMany('App\Models\Smstransaction');
    }
}

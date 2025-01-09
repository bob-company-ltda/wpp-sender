<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    use HasFactory;
    protected $table = 'orders';

    protected $primaryKey = 'order_id';

    protected $fillable = [
        'user_id',
        'cloudapi_id',
        'recipient_phone',
        'product_name',
        'quantity',
        'additional_instructions',
        'order_date',
        'status',
        'total_amount',
        'delivery_address',
        'invoices',
    ];
}

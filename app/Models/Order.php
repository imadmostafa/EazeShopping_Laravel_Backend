<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [

        'latitude',
        'longitude',
        'amount',
        "item_count",
        "customer_id",
        "cashier_id",
        "store_id",
        "isDelivered"


    ];
}

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
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id', 'id');
    }
    public function cashier()
    {
        return $this->belongsTo(User::class, 'cashier_id', 'id');
    }
    public function store_owner()
    {
        return $this->belongsTo(User::class, 'store_id', 'id');
    }
}

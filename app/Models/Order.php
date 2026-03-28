<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'hotelier_id', 'total_amount', 'delivery_charge',
        'grand_total', 'delivery_lat', 'delivery_lng', 'distance_km',
        'estimated_delivery_time', 'delivery_address', 'status',
        'payment_method', 'payment_status',
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function hotelier()
    {
        return $this->belongsTo(HotelierProfile::class, 'hotelier_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'order_id');
    }
}
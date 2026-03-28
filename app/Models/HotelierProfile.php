<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HotelierProfile extends Model
{
    protected $fillable = [
        'user_id', 'hotel_name', 'hotel_logo', 'hotel_banner', 'description',
        'cuisine_type', 'address', 'city', 'pincode', 'latitude', 'longitude',
        'opening_time', 'closing_time', 'is_open', 'delivery_radius_km',
        'base_delivery_charge', 'per_km_charge', 'free_delivery_above',
        'max_delivery_charge', 'avg_delivery_time', 'minimum_order',
        'rating', 'gstin', 'bank_account', 'is_verified', 'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function deliverySlabs()
    {
        return $this->hasMany(DeliveryPricingSlab::class, 'hotelier_id');
    }

    public function categories()
    {
        return $this->hasMany(Category::class, 'hotelier_id');
    }

    public function foodItems()
    {
        return $this->hasMany(FoodItem::class, 'hotelier_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'hotelier_id');
    }
}
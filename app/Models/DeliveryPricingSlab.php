<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryPricingSlab extends Model
{
    protected $fillable = [
        'hotelier_id', 'min_km', 'max_km',
        'delivery_charge', 'estimated_time_min',
    ];

    public function hotelier()
    {
        return $this->belongsTo(HotelierProfile::class, 'hotelier_id');
    }
}
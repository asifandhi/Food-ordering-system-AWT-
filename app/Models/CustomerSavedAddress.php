<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerSavedAddress extends Model
{
    protected $fillable = [
        'user_id', 'label', 'address_line',
        'city', 'pincode', 'latitude', 'longitude', 'is_default',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
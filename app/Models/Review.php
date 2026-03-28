<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'user_id', 'hotelier_id', 'item_id', 'rating', 'comment',
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function hotelier()
    {
        return $this->belongsTo(HotelierProfile::class, 'hotelier_id');
    }
}
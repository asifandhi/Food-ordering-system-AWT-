<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $table = 'reviews';

    protected $fillable = [
        'user_id',
        'hotelier_id',
        'item_id',
        'rating',
        'comment',
    ];

    // Called as ->with('customer') in your BrowseController
    public function customer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function hotelier()
    {
        return $this->belongsTo(HotelierProfile::class, 'hotelier_id');
    }

    public function foodItem()
    {
        return $this->belongsTo(FoodItem::class, 'item_id');
    }
}
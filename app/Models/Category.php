<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'hotelier_id', 'name', 'image', 'status',
    ];

    public function hotelier()
    {
        return $this->belongsTo(HotelierProfile::class, 'hotelier_id');
    }

    public function foodItems()
    {
        return $this->hasMany(FoodItem::class, 'category_id');
    }
}
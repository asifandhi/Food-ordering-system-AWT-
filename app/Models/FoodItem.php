<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FoodItem extends Model
{
    protected $fillable = [
        'hotelier_id', 'category_id', 'name', 'description',
        'price', 'image', 'is_available', 'is_veg',
    ];

    public function hotelier()
    {
        return $this->belongsTo(HotelierProfile::class, 'hotelier_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
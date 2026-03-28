<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = 'cart';
    
    protected $fillable = [
        'user_id', 'item_id', 'quantity',
    ];

    public function foodItem()
    {
        return $this->belongsTo(FoodItem::class, 'item_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'role',
        'profile_image',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ── Relationships ──────────────────────────────────────
    public function customerProfile()
    {
        return $this->hasOne(CustomerProfile::class);
    }

    public function hotelierProfile()
    {
        return $this->hasOne(HotelierProfile::class);
    }

    // ── Role Helpers ───────────────────────────────────────
    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }

    public function isHotelier(): bool
    {
        return $this->role === 'hotelier';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function cartItems()
    {
        return $this->hasMany(Cart::class);
    }

    public function savedAddresses()
    {
        return $this->hasMany(CustomerSavedAddress::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
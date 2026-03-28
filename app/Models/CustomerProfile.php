<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class CustomerProfile extends Model
{
    protected $fillable = [
        'user_id','default_address','city','pincode',
        'latitude','longitude','loyalty_points',
        'preferred_payment','date_of_birth',
    ];
    public function user() { return $this->belongsTo(User::class); }
}
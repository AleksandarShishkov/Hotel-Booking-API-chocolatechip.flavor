<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Booking;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Customer extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        "name",
        "email",
        "phone_number",
        "password"
    ];

    protected $hidden = [
        "password",
        "remember_token"
    ];

    public function bookings() {
        return $this->hasMany(Booking::class, 'customer_id');
    }
}

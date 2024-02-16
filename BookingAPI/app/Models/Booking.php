<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Room;
use App\Models\Customer;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        "room_id",
        "customer_id",
        "check_in_date",
        "check_out_date",
        "total_price"
    ];

    public function room() {
        return $this->belongsTo(Room::class, 'room_id');
    }

    public function customer() {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function payments() {
        return $this->hasMany(Payment::class, 'booking_id');
    }
}

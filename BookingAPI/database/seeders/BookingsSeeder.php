<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Booking;

class BookingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Booking::create([
            "room_id" => 1,
            "customer_id" => 1,
            "check_in_date" => now(),
            "check_out_date" => now()->addDays(5),
            "total_price" => 152.50
        ]);
    }
}

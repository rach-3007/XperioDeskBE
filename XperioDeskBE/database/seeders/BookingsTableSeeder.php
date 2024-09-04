<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BookingsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('bookings')->insert([
            ['seat_id' => 9, 'user_id' => 2, 'booked_by' => 'Admin User', 'start_date' => now(), 'end_date' => now()->addDays(5)],
        ]);
    }
}

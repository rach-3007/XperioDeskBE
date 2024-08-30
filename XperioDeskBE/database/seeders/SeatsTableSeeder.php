<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SeatsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('seats')->insert([
            ['seat_number' => 'S1', 'module_id' => 1, 'booked_by_user_id' => 1, 'layout_entity_id' => 1],
            ['seat_number' => 'S2', 'module_id' => 2, 'booked_by_user_id' => 2, 'layout_entity_id' => 2],
        ]);
    }
}

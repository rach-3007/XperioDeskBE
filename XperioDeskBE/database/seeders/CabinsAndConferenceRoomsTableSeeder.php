<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CabinsAndConferenceRoomsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('cabins_and_conference_rooms')->insert([
            ['layout_entity_id' => 2, 'type' => 'cabin'],
        ]);
    }
}


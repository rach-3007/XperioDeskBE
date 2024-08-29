<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BuildingsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('buildings')->insert([
            ['building_name' => 'Gayatri'],
            ['building_name' => 'Tejaswini '],
        ]);
    }
}

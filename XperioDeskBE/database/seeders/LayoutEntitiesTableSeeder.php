<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LayoutEntitiesTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('layout_entities')->insert([
            ['layout_id' => 1, 'type' => 'Seat', 'x-position' => '10', 'y-position' => '20', 'rotation' => '0'],
            ['layout_id' => 2, 'type' => 'Cabin', 'x-position' => '30', 'y-position' => '40', 'rotation' => '90'],
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LayoutEntitiesTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('layout_entities')->insert([
            ['layout_id' => 5, 'type' => 'Seat', 'x_position' => '10', 'y_position' => '20', 'rotation' => '0'],
            ['layout_id' => 6, 'type' => 'Cabin', 'x_position' => '30', 'y_position' => '40', 'rotation' => '90'],
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DuTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('du')->insert([
            ['du_name' => 'DU1'],
            ['du_name' => 'DU2'],
        ]);
    }
}

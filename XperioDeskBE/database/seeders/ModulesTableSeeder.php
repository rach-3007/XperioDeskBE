<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModulesTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('modules')->insert([
            ['module_name' => 'Module 1', 'building_id' => 1],
            ['module_name' => 'Module 2', 'building_id' => 2],
        ]);
    }
}

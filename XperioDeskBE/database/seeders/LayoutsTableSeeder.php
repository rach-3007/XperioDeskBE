<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LayoutsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('layouts')->insert([
            ['module_id' => 6, 'layout_name' => 'Layout 1'],
            ['module_id' => 2, 'layout_name' => 'Layout 2'],
        ]);
    }
}

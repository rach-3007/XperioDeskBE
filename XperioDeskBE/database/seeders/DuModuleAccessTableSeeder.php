<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DuModuleAccessTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('du_module_access')->insert([
            ['du_id' => 1, 'module_id' => 1],
            ['du_id' => 2, 'module_id' => 2],
        ]);
    }
}

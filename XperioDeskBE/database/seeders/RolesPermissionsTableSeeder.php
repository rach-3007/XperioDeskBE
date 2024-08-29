<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesPermissionsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('roles_permissions')->insert([
            ['role_id' => 1, 'permission_id' => 1],
            ['role_id' => 1, 'permission_id' =>2],
            
           
        ]);
    }
}

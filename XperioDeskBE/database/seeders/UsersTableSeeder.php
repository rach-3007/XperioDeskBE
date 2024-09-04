<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Array of roles
        $roles = ['User', 'Admin', 'Privileged_User'];

        // Array of designations
        $designations = ['Manager', 'Developer', 'Designer', 'Tester', 'Support'];
        $dunames = ['du1', 'du2', 'du3', 'du4', 'du5','du6'];
       
        $duIds = [];
        foreach ($dunames as $duname) {
            $duIds[] = DB::table('du')->insertGetId([
                'du_name' => $duname,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        for ($i = 1; $i <= 15; $i++) {
            DB::table('users')->insert([
                'name' => 'User ' . $i,
                'email' => 'rachelle' . $i . '@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'designation' => $designations[array_rand($designations)],
                'role' => $roles[array_rand($roles)],
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
                'du_id' => $duIds[array_rand($duIds)],
            ]);
        }
    }
}

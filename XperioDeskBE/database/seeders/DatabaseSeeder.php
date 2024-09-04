<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesTableSeeder::class,
            PermissionsTableSeeder::class,
            UsersTableSeeder::class,
            RolesPermissionsTableSeeder::class,
            DuTableSeeder::class,
            BuildingsTableSeeder::class,
            ModulesTableSeeder::class,
            DuModuleAccessTableSeeder::class,
            LayoutsTableSeeder::class,
            LayoutEntitiesTableSeeder::class,
            SeatsTableSeeder::class,
            BookingsTableSeeder::class,
            CabinsAndConferenceRoomsTableSeeder::class,
        ]);
    }
}

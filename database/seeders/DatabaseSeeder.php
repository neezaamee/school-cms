<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
// use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            SubscriptionPackageSeeder::class,
            CountryCitySeeder::class,
            UserSeeder::class,
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\SubscriptionPackage;
use Illuminate\Database\Seeder;

class SubscriptionPackageSeeder extends Seeder
{
    public function run(): void
    {
        $packages = [
            [
                'name' => 'Free',
                'student_limit' => 100,
                'staff_limit' => 20,
                'entry_limit' => 300,
                'price' => 0.00,
                'has_tech_support' => false,
            ],
            [
                'name' => 'Plus',
                'student_limit' => 500,
                'staff_limit' => 50,
                'entry_limit' => 5000,
                'price' => 29.99,
                'has_tech_support' => true,
            ],
            [
                'name' => 'Premium',
                'student_limit' => 2000,
                'staff_limit' => 150,
                'entry_limit' => 50000,
                'price' => 99.99,
                'has_tech_support' => true,
            ],
            [
                'name' => 'Enterprise',
                'student_limit' => 10000, 
                'staff_limit' => 1000,
                'entry_limit' => 1000000,
                'price' => 249.99,
                'has_tech_support' => true,
            ],
        ];

        foreach ($packages as $package) {
            SubscriptionPackage::create($package);
        }
    }
}

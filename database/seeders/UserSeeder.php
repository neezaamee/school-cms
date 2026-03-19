<?php

namespace Database\Seeders;

use App\Models\Campus;
use App\Models\City;
use App\Models\Country;
use App\Models\SubscriptionPackage;
use App\Models\User;
use App\Models\School;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Super Admin User
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@elaftech.com',
            'password' => Hash::make('password'),
        ]);
        $superAdmin->assignRole('super admin');

        $freePackage = SubscriptionPackage::where('name', 'Free')->first();

        $school = School::create([
            'name' => 'Falcon International School',
            'slug' => 'falcon-international-school',
            'email' => 'info@falcon.com',
            'phone' => '+1234567890',
            'status' => 'active',
            'subscription_package_id' => $freePackage->id ?? null,
        ]);

        $pakistan = Country::where('name', 'Pakistan')->first();
        $islamabad = City::where('name', 'Islamabad')->first();

        Campus::create([
            'name' => 'Main Campus',
            'school_id' => $school->id,
            'country_id' => $pakistan->id ?? null,
            'city_id' => $islamabad->id ?? null,
            'address' => 'Floor 5, Blue Area, Islamabad',
            'slug' => 'falcon-main',
            'is_main' => true,
        ]);

        // 3. Create School Owner User
        $schoolOwner = User::create([
            'name' => 'John Owner',
            'email' => 'owner@elaftech.com',
            'password' => Hash::make('password'),
            'school_id' => $school->id,
        ]);
        $schoolOwner->assignRole('school owner');

        // 4. Create Teacher User
        $teacher = User::create([
            'name' => 'Jane Teacher',
            'email' => 'teacher@elaftech.com',
            'password' => Hash::make('password'),
            'school_id' => $school->id,
        ]);
        $teacher->assignRole('teacher');

        // 5. Create Student User
        $student = User::create([
            'name' => 'Sam Student',
            'email' => 'student@elaftech.com',
            'password' => Hash::make('password'),
            'school_id' => $school->id,
        ]);
        $student->assignRole('student');
    }
}

<?php

namespace Database\Seeders;

use App\Models\Campus;
use App\Models\City;
use App\Models\School;
use App\Models\StaffDesignation;
use App\Models\StaffProfile;
use App\Models\SubscriptionPackage;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class LargeScaleSchoolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cities = City::whereHas('country', function($q) {
            $q->where('name', 'Pakistan');
        })->get();

        if ($cities->isEmpty()) {
            $this->command->error('No cities found for Pakistan. Please run CountryCitySeeder first.');
            return;
        }

        $packages = SubscriptionPackage::all();
        if ($packages->isEmpty()) {
            $this->command->error('No subscription packages found. Please run SubscriptionPackageSeeder first.');
            return;
        }

        $principalDesignation = StaffDesignation::where('name', 'Principal')->first();
        $otherDesignations = StaffDesignation::where('name', '!=', 'Principal')->get();

        $schoolNames = [
            'The Educators', 'City School', 'Beaconhouse', 'Bloomfield Hall', 'Roots Millennium',
            'Allied School', 'Dar-e-Arqam', 'Lahore Grammar', 'KIPS School', 'American Lycetuff',
            'Superior School', 'Punjab School', 'Standard Higher', 'Global Vision', 'Bright Future',
            'Unity School', 'Zenith Academy', 'Prime Institute', 'Elite School', 'Noble School'
        ];

        // 50 Schools
        $this->command->info('Seeding 50 Schools...');
        for ($i = 1; $i <= 50; $i++) {
            $cityName = $cities->random()->name;
            $baseName = $schoolNames[array_rand($schoolNames)];
            $schoolName = "$baseName $cityName " . ($i > 20 ? $i : "");
            
            $school = School::create([
                'name' => trim($schoolName),
                'email' => Str::slug($schoolName) . $i . "@example.com",
                'phone' => '03' . rand(10, 49) . rand(1000000, 9999999),
                'subscription_package_id' => $packages->random()->id,
                'slug' => Str::slug($schoolName) . '-' . time() . '-' . $i,
            ]);

            // School Owner User
            $owner = User::create([
                'name' => "Owner of " . $school->name,
                'email' => "owner" . $i . "@" . Str::slug($school->name) . ".com",
                'password' => Hash::make('password'),
                'school_id' => $school->id,
            ]);
            $owner->assignRole('school owner');

            // Principal (1 per school)
            $principalUser = User::create([
                'name' => "Principal Name " . $i,
                'email' => "principal" . $i . "@" . Str::slug($school->name) . ".com",
                'password' => Hash::make('password'),
                'school_id' => $school->id,
            ]);
            $principalUser->assignRole('principal');

            StaffProfile::create([
                'user_id' => $principalUser->id,
                'school_id' => $school->id,
                'designation_id' => $principalDesignation->id,
                'name' => $principalUser->name,
                'email' => $principalUser->email,
                'phone' => '03' . rand(10, 49) . rand(1000000, 9999999),
            ]);
        }

        // 150 Campuses (3 per school)
        $this->command->info('Seeding 150 Campuses...');
        $allSchools = School::all();
        foreach ($allSchools as $school) {
            for ($j = 1; $j <= 3; $j++) {
                $city = $cities->random();
                Campus::create([
                    'name' => $school->name . " - " . $city->name . ($j === 1 ? " Main" : " Branch $j"),
                    'school_id' => $school->id,
                    'country_id' => $city->country_id,
                    'city_id' => $city->id,
                    'address' => "Address details for " . $school->name . " in " . $city->name,
                    'slug' => Str::slug($school->name . "-" . $city->name . "-" . $j) . '-' . rand(1000, 9999),
                    'is_main' => ($j === 1),
                ]);
            }
        }

        // 500 total staff. 50 are already Principals. 450 more.
        $this->command->info('Seeding 450 additional Staff Profiles...');
        $allCampuses = Campus::all();
        for ($k = 1; $k <= 450; $k++) {
            $campus = $allCampuses->random();
            $designation = $otherDesignations->random();
            StaffProfile::create([
                'school_id' => $campus->school_id,
                'campus_id' => $campus->id,
                'designation_id' => $designation->id,
                'name' => "Staff Name " . ($k + 50),
                'email' => "staff" . ($k + 50) . "@" . Str::random(5) . ".com",
                'phone' => '03' . rand(10, 49) . rand(1000000, 9999999),
            ]);
        }
        $this->command->info('Seeding completed successfully!');
    }
}

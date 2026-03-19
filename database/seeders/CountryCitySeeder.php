<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Country;
use Illuminate\Database\Seeder;

class CountryCitySeeder extends Seeder
{
    public function run(): void
    {
        $countries = [
            ['name' => 'Pakistan', 'code' => 'PK'],
            ['name' => 'United States', 'code' => 'US'],
            ['name' => 'United Kingdom', 'code' => 'GB'],
            ['name' => 'United Arab Emirates', 'code' => 'AE'],
            ['name' => 'Saudi Arabia', 'code' => 'SA'],
            ['name' => 'Canada', 'code' => 'CA'],
            ['name' => 'Australia', 'code' => 'AU'],
        ];

        foreach ($countries as $countryData) {
            $country = Country::create($countryData);

            if ($country->name === 'Pakistan') {
                $cities = [
                    'Karachi', 'Lahore', 'Faisalabad', 'Rawalpindi', 'Gujranwala', 
                    'Peshawar', 'Multan', 'Hyderabad', 'Islamabad', 'Quetta', 
                    'Bahawalpur', 'Sargodha', 'Sialkot', 'Sukkur', 'Larkana', 
                    'Sheikhupura', 'Rahim Yar Khan', 'Jhang', 'Dera Ghazi Khan', 'Gujrat'
                ];

                foreach ($cities as $cityName) {
                    City::create([
                        'name' => $cityName,
                        'country_id' => $country->id,
                    ]);
                }
            }
        }
    }
}

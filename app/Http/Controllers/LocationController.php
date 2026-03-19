<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Country;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    /**
     * Get cities for a given country.
     */
    public function getCities(Country $country)
    {
        return response()->json($country->cities);
    }
}

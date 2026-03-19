<?php

namespace App\Http\Controllers;

use App\Models\School;
use Illuminate\Http\Request;

class SchoolLandingController extends Controller
{
    /**
     * Display the school's public landing page.
     */
    public function show($slug)
    {
        $school = School::where('slug', $slug)
            ->with(['mainCampus.city', 'mainCampus.country', 'subscriptionPackage'])
            ->firstOrFail();

        return view('school.landing', compact('school'));
    }

    /**
     * Display the branded login page for a specific school.
     */
    public function login($slug)
    {
        $school = School::where('slug', $slug)->firstOrFail();

        return view('auth.school-login', compact('school'));
    }
}

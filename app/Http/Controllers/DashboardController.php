<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Redirect to the appropriate dashboard based on user role.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->hasRole('super admin')) {
            return view('dashboard.super_admin');
        } elseif ($user->hasAnyRole(['school owner', 'principal', 'school administrator', 'school manager', 'campus manager'])) {
            return view('dashboard.school_owner'); 
        } elseif ($user->hasRole('teacher')) {
            return view('dashboard.teacher');
        } elseif ($user->hasRole('student')) {
            return view('dashboard.student');
        }

        // Fallback or default dashboard
        return view('dashboard.default');
    }
}

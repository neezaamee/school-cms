<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campus;
use App\Models\School;
use App\Models\Country;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CampusController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $query = Campus::with(['school', 'city']);

        if ($user->hasRole('school owner')) {
            $query->where('school_id', $user->school_id);
        } elseif (!$user->hasRole('super admin')) {
            $query->where('id', $user->campus_id);
        }

        $campuses = $query->latest()->get();
        return view('admin.campuses.index', compact('campuses'));
    }

    public function create()
    {
        $user = Auth::user();
        
        // 10 Campus Limit for School Owners
        if ($user->hasRole('school owner')) {
            $count = Campus::where('school_id', $user->school_id)->count();
            if ($count >= 10) {
                return redirect()->route('admin.campuses.index')->with('error', 'You have reached the maximum limit of 10 campuses. Please contact support via feedback to request more.');
            }
        }

        $schools = $user->hasRole('super admin') ? School::all() : School::where('id', $user->school_id)->get();
        $countries = Country::all();
        
        return view('admin.campuses.create', compact('schools', 'countries'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        // Security check for school_id
        if (!$user->hasRole('super admin')) {
            $request->merge(['school_id' => $user->school_id]);
            
            // Re-verify limit on store
            $count = Campus::where('school_id', $user->school_id)->count();
            if ($count >= 10) {
                return redirect()->route('admin.campuses.index')->with('error', 'Limit reached.');
            }
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'school_id' => 'required|exists:schools,id',
            'country_id' => 'required|exists:countries,id',
            'city_id' => 'required|exists:cities,id',
            'address' => 'nullable|string',
            'is_main' => 'boolean',
        ]);

        // If is_main is checked, un-main other campuses of the same school
        if ($request->is_main) {
            Campus::where('school_id', $request->school_id)->update(['is_main' => false]);
        }

        Campus::create([
            'name' => $request->name,
            'school_id' => $request->school_id,
            'country_id' => $request->country_id,
            'city_id' => $request->city_id,
            'address' => $request->address,
            'slug' => Str::slug($request->name . '-' . time()),
            'is_main' => $request->is_main ?? false,
        ]);

        return redirect()->route('admin.campuses.index')->with('success', 'Campus created successfully.');
    }

    public function edit(Campus $campus)
    {
        $user = Auth::user();
        
        // Authorization
        if (!$user->hasRole('super admin') && $campus->school_id !== $user->school_id) {
            abort(403);
        }

        $schools = $user->hasRole('super admin') ? School::all() : School::where('id', $user->school_id)->get();
        $countries = Country::all();
        $cities = City::where('country_id', $campus->country_id)->get();
        
        return view('admin.campuses.edit', compact('campus', 'schools', 'countries', 'cities'));
    }

    public function update(Request $request, Campus $campus)
    {
        $user = Auth::user();
        
        if (!$user->hasRole('super admin') && $campus->school_id !== $user->school_id) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'country_id' => 'required|exists:countries,id',
            'city_id' => 'required|exists:cities,id',
            'address' => 'nullable|string',
            'is_main' => 'boolean',
        ]);

        if ($request->is_main && !$campus->is_main) {
            Campus::where('school_id', $campus->school_id)->update(['is_main' => false]);
        }

        $campus->update([
            'name' => $request->name,
            'country_id' => $request->country_id,
            'city_id' => $request->city_id,
            'address' => $request->address,
            'is_main' => $request->is_main ?? false,
        ]);

        return redirect()->route('admin.campuses.index')->with('success', 'Campus updated successfully.');
    }

    public function destroy(Campus $campus)
    {
        $user = Auth::user();
        if (!$user->hasRole('super admin') && $campus->school_id !== $user->school_id) {
            abort(403);
        }

        $campus->delete();
        return redirect()->route('admin.campuses.index')->with('success', 'Campus deleted successfully.');
    }

    public function show(Campus $campus)
    {
        $user = Auth::user();
        if (!$user->hasRole('super admin') && $campus->school_id !== $user->school_id) {
            abort(403);
        }

        $campus->load(['users', 'school', 'country', 'city']);
        return view('admin.campuses.show', compact('campus'));
    }
}

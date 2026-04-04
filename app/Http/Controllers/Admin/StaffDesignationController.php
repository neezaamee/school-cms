<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StaffDesignation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class StaffDesignationController extends Controller
{
    public function index()
    {
        $designations = StaffDesignation::all()->groupBy('category');
        return view('admin.staffs.designations.index', compact('designations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string',
        ]);

        StaffDesignation::create([
            'name' => $request->name,
            'category' => $request->category,
            'slug' => Str::slug($request->name),
        ]);

        return redirect()->route('admin.staff-designations.index')
            ->with('success', 'Designation added successfully.');
    }

    public function destroy(StaffDesignation $staffDesignation)
    {
        if ($staffDesignation->profiles()->count() > 0) {
            return back()->with('error', 'Cannot delete designation assigned to staff.');
        }
        
        $staffDesignation->delete();
        return redirect()->route('admin.staff-designations.index')
            ->with('success', 'Designation removed.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\StaffDesignation;
use App\Models\StaffProfile;
use App\Models\Campus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class StaffController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        // Use school_id from staff_profile now
        $query = StaffProfile::with(['user', 'designation', 'campus', 'school']);

        if ($user->hasRole('school owner')) {
            $query->where('school_id', $user->school_id);
        } elseif ($user->hasanyrole('principal|school manager|school administrator')) {
            $query->where('campus_id', $user->campus_id);
        } elseif (!$user->hasRole('super admin')) {
            abort(403);
        }

        $staffs = $query->latest()->get();
        return view('admin.staffs.index', compact('staffs'));
    }

    public function create()
    {
        $user = auth()->user();
        $designations = StaffDesignation::all()->groupBy('category');
        
        $campuses = Campus::query();
        if ($user->hasRole('super admin')) {
            // All campuses
        } elseif ($user->hasRole('school owner')) {
            $campuses->where('school_id', $user->school_id);
        } else {
            // Principal/Manager/Admin: Only their assigned campus
            $campuses->where('id', $user->campus_id);
        }
        $campuses = $campuses->get();
        $schools = $user->hasRole('super admin') ? \App\Models\School::all() : null;

        return view('admin.staffs.create', compact('designations', 'campuses', 'schools'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            // Email should be unique in both users and staff_profiles (optional, but safer)
            'email' => 'required|email|unique:users,email|unique:staff_profiles,email',
            'phone' => 'nullable|string|max:20',
            'emergency_phone' => 'nullable|string|max:20',
            'designation_id' => 'required|exists:staff_designations,id',
            'campus_id' => 'nullable|exists:campuses,id',
            'education_record' => 'nullable|string',
            'address' => 'nullable|string',
            'profile_photo' => 'nullable|image|max:2048',
        ]);

        $user = auth()->user();
        $schoolId = $user->hasRole('super admin') ? $request->school_id : $user->school_id;
        $campusId = $request->campus_id;

        if (!$user->hasRole('super admin') && !$user->hasRole('school owner')) {
            $campusId = $user->campus_id;
        }

        // Profile Photo
        $photoPath = null;
        if ($request->hasFile('profile_photo')) {
            $photoPath = $request->file('profile_photo')->store('staff/photos', 'public');
        }

        // Create Profile ONLY
        StaffProfile::create([
            'name' => $request->name,
            'email' => $request->email,
            'school_id' => $schoolId,
            'campus_id' => $campusId,
            'designation_id' => $request->designation_id,
            'phone' => $request->phone,
            'emergency_phone' => $request->emergency_phone,
            'education_record' => $request->education_record,
            'address' => $request->address,
            'profile_photo' => $photoPath,
        ]);

        return redirect()->route('admin.staffs.index')->with('success', 'Staff member added successfully. You can create a user account for them later.');
    }

    public function show(StaffProfile $staff)
    {
        return view('admin.staffs.show', compact('staff'));
    }

    public function edit(StaffProfile $staff)
    {
        $user = auth()->user();

        // Authorization: restrict Principal to their campus staff
        if (!$user->hasRole('super admin') && !$user->hasRole('school owner')) {
            if ($staff->campus_id !== $user->campus_id) {
                abort(403);
            }
        }

        $designations = StaffDesignation::all()->groupBy('category');
        
        $campuses = Campus::query();
        if ($user->hasRole('super admin')) {
            // All
        } elseif ($user->hasRole('school owner')) {
            $campuses->where('school_id', $user->school_id);
        } else {
            $campuses->where('id', $user->campus_id);
        }
        $campuses = $campuses->get();
        $schools = $user->hasRole('super admin') ? \App\Models\School::all() : null;

        return view('admin.staffs.edit', compact('staff', 'designations', 'campuses', 'schools'));
    }

    public function update(Request $request, StaffProfile $staff)
    {
        $user = auth()->user();

        // Authorization
        if (!$user->hasRole('super admin') && !$user->hasRole('school owner')) {
            if ($staff->campus_id !== $user->campus_id) {
                abort(403);
            }
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:staff_profiles,email,' . $staff->id,
            'phone' => 'nullable|string|max:20',
            'emergency_phone' => 'nullable|string|max:20',
            'designation_id' => 'required|exists:staff_designations,id',
            'campus_id' => 'nullable|exists:campuses,id',
            'education_record' => 'nullable|string',
            'address' => 'nullable|string',
            'profile_photo' => 'nullable|image|max:2048',
        ]);

        $campusId = $request->campus_id;
        if (!$user->hasRole('super admin') && !$user->hasRole('school owner')) {
            $campusId = $user->campus_id;
        }

        // If a User account exists, update it too
        if ($staff->user_id) {
            $staffUser = $staff->user;
            
            // Validate email uniqueness against other users
            $request->validate([
                'email' => 'unique:users,email,' . $staff->user_id,
            ]);

            $staffUser->update([
                'name' => $request->name,
                'email' => $request->email,
                'campus_id' => $campusId,
            ]);

            if ($request->filled('password')) {
                $staffUser->update(['password' => Hash::make($request->password)]);
            }

            // Sync Role based on designation
            $designation = StaffDesignation::find($request->designation_id);
            $roleName = $this->getRoleFromDesignation($designation);
            $staffUser->syncRoles([$roleName]);
        }

        // Profile Photo
        if ($request->hasFile('profile_photo')) {
            if ($staff->profile_photo) {
                Storage::disk('public')->delete($staff->profile_photo);
            }
            $staff->profile_photo = $request->file('profile_photo')->store('staff/photos', 'public');
        }

        // Update Profile
        $staff->update([
            'name' => $request->name,
            'email' => $request->email,
            'campus_id' => $campusId,
            'designation_id' => $request->designation_id,
            'phone' => $request->phone,
            'emergency_phone' => $request->emergency_phone,
            'education_record' => $request->education_record,
            'address' => $request->address,
            'profile_photo' => $staff->profile_photo,
        ]);

        return redirect()->route('admin.staffs.index')->with('success', 'Staff member updated successfully.');
    }

    public function createUserAccount(StaffProfile $staff)
    {
        $user = auth()->user();

        // Only Principals, School Owners, and Super Admins can create user accounts
        if (!$user->hasAnyRole(['super admin', 'school owner', 'principal', 'school administrator', 'school manager'])) {
            abort(403);
        }

        // Security check
        if (!$user->hasRole('super admin') && $staff->school_id !== $user->school_id) {
            abort(403);
        }

        if ($staff->user_id) {
            return redirect()->back()->with('info', 'User account already exists for this staff member.');
        }

        // Check if email is already taken in users table
        if (User::where('email', $staff->email)->exists()) {
            return redirect()->back()->with('error', 'The email address is already associated with another user account.');
        }

        // Generate Random Password
        $password = Str::random(10);

        // Create User
        $staffUser = User::create([
            'name' => $staff->name,
            'email' => $staff->email,
            'password' => Hash::make($password),
            'school_id' => $staff->school_id,
            'campus_id' => $staff->campus_id,
        ]);

        // Link User to Profile
        $staff->update(['user_id' => $staffUser->id]);

        // Assign Role
        $roleName = $this->getRoleFromDesignation($staff->designation);
        $staffUser->assignRole($roleName);

        // Send Email
        try {
            \Illuminate\Support\Facades\Mail::to($staff->email)->send(new \App\Mail\StaffCredentialsMail($staff->name, $staff->email, $password));
        } catch (\Exception $e) {
            return redirect()->back()->with('warning', 'User account created, but failed to send notification email. Password is: ' . $password);
        }

        return redirect()->back()->with('success', 'User account created successfully for ' . $staff->name);
    }

    private function getRoleFromDesignation($designation)
    {
        $roleName = 'staff'; // Default
        if (!$designation) return $roleName;

        if ($designation->category == 'Academic Staff (Teaching)') {
            $roleName = 'teacher';
        } elseif (Str::contains($designation->name, ['Principal', 'Vice Principal'])) {
            $roleName = 'principal';
        } elseif (Str::contains($designation->name, ['Campus Manager', 'Coordinator'])) {
            $roleName = 'campus manager';
        } elseif (Str::contains($designation->name, ['School Administrator', 'Section Head'])) {
            $roleName = 'school administrator';
        } elseif ($designation->name == 'Accountant') {
            $roleName = 'accountant';
        } elseif ($designation->name == 'Librarian') {
            $roleName = 'librarian';
        } elseif ($designation->name == 'Lab Assistant') {
            $roleName = 'lab assistant';
        } elseif ($designation->name == 'Data Entry Operator') {
            $roleName = 'data entry operator';
        }

        return $roleName;
    }

    public function destroy(StaffProfile $staff)
    {
        $user = auth()->user();

        // Authorization
        if (!$user->hasRole('super admin') && !$user->hasRole('school owner')) {
            if ($staff->campus_id !== $user->campus_id) {
                abort(403);
            }
        }

        if ($staff->profile_photo) {
            Storage::disk('public')->delete($staff->profile_photo);
        }

        // If user exists, delete user (which deletes profile due to cascade in migration)
        // OR delete profile directly if no user exists.
        if ($staff->user_id) {
            $staff->user->delete();
        } else {
            $staff->delete();
        }

        return redirect()->route('admin.staffs.index')->with('success', 'Staff member deleted successfully.');
    }
}

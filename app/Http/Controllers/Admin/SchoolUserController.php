<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class SchoolUserController extends Controller
{
    /**
     * Display a listing of the school's users.
     */
    public function index()
    {
        $schoolId = auth()->user()->school_id;
        
        if (!$schoolId) {
            return redirect()->route('dashboard')->with('error', 'You are not assigned to a school.');
        }

        $users = User::where('school_id', $schoolId)
            ->with('roles')
            ->get();

        return view('admin.school_users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user for the school.
     */
    public function create()
    {
        // Only allow assigning 'teacher' or 'student' roles for school owners
        $roles = Role::whereIn('name', ['teacher', 'student'])->get();
        return view('admin.school_users.create', compact('roles'));
    }

    /**
     * Store a newly created school user.
     */
    public function store(Request $request)
    {
        $schoolId = auth()->user()->school_id;

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|exists:roles,name',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'school_id' => $schoolId,
        ]);

        $user->assignRole($request->role);

        return redirect()->route('school-users.index')->with('success', 'User added to school successfully.');
    }

    /**
     * Show the form for editing a school user.
     */
    public function edit(User $user)
    {
        // Ensure the owner can only edit users from their own school
        if ($user->school_id !== auth()->user()->school_id) {
            abort(403);
        }

        $roles = Role::whereIn('name', ['teacher', 'student'])->get();
        return view('admin.school_users.edit', compact('user', 'roles'));
    }

    /**
     * Update the school user.
     */
    public function update(Request $request, User $user)
    {
        if ($user->school_id !== auth()->user()->school_id) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|exists:roles,name',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:8|confirmed']);
            $user->update(['password' => Hash::make($request->password)]);
        }

        $user->syncRoles([$request->role]);

        return redirect()->route('school-users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the school user.
     */
    public function destroy(User $user)
    {
        if ($user->school_id !== auth()->user()->school_id) {
            abort(403);
        }

        $user->delete();
        return redirect()->route('school-users.index')->with('success', 'User removed from school.');
    }
}

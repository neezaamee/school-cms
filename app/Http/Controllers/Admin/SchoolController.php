<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campus;
use App\Models\Country;
use App\Models\School;
use App\Models\SubscriptionPackage;
use App\Models\User;
use App\Mail\SchoolWelcomeMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SchoolController extends Controller
{
    /**
     * Display a listing of schools.
     */
    public function index()
    {
        $schools = School::with(['subscriptionPackage', 'mainCampus.city', 'mainCampus.country'])
            ->withCount(['users', 'staffProfiles', 'students', 'attendance', 'invoices', 'examMarks'])
            ->get();
            
        return view('admin.schools.index', compact('schools'));
    }

    /**
     * Show the form for creating a new school.
     */
    public function create()
    {
        return view('admin.schools.create');
    }

    /**
     * Store a newly created school in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            // School Profile
            'school_name' => 'required|string|max:255',
            'school_website' => 'nullable|string|max:255',
            'subscription_package_id' => 'required|exists:subscription_packages,id',
            
            // Location / Main Campus
            'campus_name' => 'required|string|max:255',
            'country_id' => 'required|exists:countries,id',
            'city_id' => 'required|exists:cities,id',
            'address' => 'required|string|max:500',
            'campus_slug' => 'required|string|unique:campuses,slug',
            'campus_email' => 'required|email',
            'campus_phone' => 'required|string|max:20',
            
            // Owner Profile
            'owner_name' => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
            'owner_email' => 'required|email|unique:users,email',
            'owner_phone' => 'required|string|max:20',
        ], [
            'owner_name.regex' => 'The owner name may only contain letters and spaces.',
        ]);

        try {
            DB::beginTransaction();

            $school = School::create([
                'name' => $request->school_name,
                'email' => $request->owner_email,
                'phone' => ($request->owner_phone_prefix ?? '') . $request->owner_phone,
                'website' => $request->school_website,
                'subscription_package_id' => $request->subscription_package_id,
                'slug' => Str::slug($request->school_name) . '-' . time(),
            ]);

            $school->save();

            // Generate a secure random temporary password
            $tempPassword = Str::random(10);

            $user = User::create([
                'name' => $request->owner_name,
                'email' => $request->owner_email,
                'password' => Hash::make($tempPassword),
                'school_id' => $school->id,
            ]);
            $user->assignRole('school owner');

            try {
                Mail::to($user->email)->send(new SchoolWelcomeMail($user, $school, $tempPassword));
            } catch (\Exception $e) {
                // Log error but continue registration to avoid blocking users on mail delivery issues
                Log::error("Failed to send welcome email to {$user->email}: " . $e->getMessage());
            }

            Campus::create([
                'name' => $request->campus_name,
                'school_id' => $school->id,
                'country_id' => $request->country_id,
                'city_id' => $request->city_id,
                'address' => $request->address,
                'email' => $request->campus_email,
                'phone' => ($request->campus_phone_prefix ?? '') . $request->campus_phone,
                'slug' => $request->campus_slug,
                'is_main' => true,
            ]);

            DB::commit();
            return redirect()->route('schools.index')->with('success', 'School registered successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified school.
     */
    public function show(School $school)
    {
        $school->load(['subscriptionPackage', 'mainCampus.city', 'mainCampus.country', 'users' => function($q) {
            $q->role('school owner');
        }]);
        
        return view('admin.schools.show', compact('school'));
    }

    /**
     * Show the form for editing the specified school.
     */
    public function edit(School $school)
    {
        $school->load(['mainCampus', 'subscriptionPackage', 'users' => function($q) {
            $q->role('school owner');
        }]);
        
        $owner = $school->users->first();
        $countries = Country::all();
        $packages = SubscriptionPackage::all();
        
        return view('admin.schools.edit', compact('school', 'owner', 'countries', 'packages'));
    }

    /**
     * Update the specified school in storage.
     */
    public function update(Request $request, School $school)
    {
        $school->load(['mainCampus', 'users' => function($q) { $q->role('school owner'); }]);
        $owner = $school->users->first();

        $request->validate([
            'school_name' => 'required|string|max:255',
            'school_website' => 'nullable|string|max:255',
            'subscription_package_id' => 'required|exists:subscription_packages,id',
            
            'campus_name' => 'required|string|max:255',
            'campus_email' => 'required|email',
            'campus_phone' => 'required|string|max:20',
            'country_id' => 'required|exists:countries,id',
            'city_id' => 'required|exists:cities,id',
            'address' => 'required|string|max:500',
            
            'owner_name' => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
            'owner_email' => 'required|email|unique:users,email,' . ($owner->id ?? 0),
            'owner_phone' => 'required|string|max:20',
        ], [
            'owner_name.regex' => 'The owner name may only contain letters and spaces.',
        ]);

        try {
            DB::beginTransaction();

            $school->update([
                'name' => $request->school_name,
                'website' => $request->school_website,
                'phone' => ($request->owner_phone_prefix ?? '') . $request->owner_phone,
                'email' => $request->owner_email,
                'subscription_package_id' => $request->subscription_package_id,
            ]);

            if ($school->mainCampus) {
                $school->mainCampus->update([
                    'name' => $request->campus_name,
                    'email' => $request->campus_email,
                    'country_id' => $request->country_id,
                    'city_id' => $request->city_id,
                    'phone' => ($request->campus_phone_prefix ?? '') . $request->campus_phone,
                    'address' => $request->address,
                ]);
            }

            if ($owner) {
                $owner->update([
                    'name' => $request->owner_name,
                    'email' => $request->owner_email,
                    'phone' => ($request->owner_phone_prefix ?? '') . $request->owner_phone,
                ]);
            }

            DB::commit();
            return redirect()->route('schools.index')->with('success', 'School updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Update failed: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified school from storage.
     */
    public function destroy(School $school)
    {
        $school->delete(); // Cascading soft delete handled in model
        return redirect()->route('schools.index')->with('success', 'School and related data soft-deleted.');
    }

    /**
     * Optimize and store uploaded image.
     */
    private function optimizeImage($file, $folder, $width, $height)
    {
        $imageInfo = getimagesize($file);
        if (!$imageInfo) return null;
        
        $mime = $imageInfo['mime'];
        
        switch ($mime) {
            case 'image/jpeg': $img = imagecreatefromjpeg($file); break;
            case 'image/png': $img = imagecreatefrompng($file); break;
            case 'image/gif': $img = imagecreatefromgif($file); break;
            case 'image/webp': $img = imagecreatefromwebp($file); break;
            default: return null;
        }

        // Resize
        $origWidth = imagesx($img);
        $origHeight = imagesy($img);
        
        // Calculate aspect ratio
        $ratio = min($width / $origWidth, $height / $origHeight);
        $newWidth = (int)round($origWidth * $ratio);
        $newHeight = (int)round($origHeight * $ratio);

        $tmpImg = imagecreatetruecolor($newWidth, $newHeight);
        
        // Handle transparency for WebP/PNG
        imagealphablending($tmpImg, false);
        imagesavealpha($tmpImg, true);
        $transparent = imagecolorallocatealpha($tmpImg, 255, 255, 255, 127);
        imagefilledrectangle($tmpImg, 0, 0, $newWidth, $newHeight, $transparent);

        imagecopyresampled($tmpImg, $img, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);

        $filename = Str::random(20) . '.webp'; 
        $storageDir = 'branding/' . $folder;
        $fullPath = storage_path('app/public/' . $storageDir);
        
        if (!file_exists($fullPath)) {
            mkdir($fullPath, 0755, true);
        }

        imagewebp($tmpImg, $fullPath . '/' . $filename, 80); 
        
        return 'storage/' . $storageDir . '/' . $filename;
    }
    /**
     * Get campuses for a specific school (AJAX).
     */
    public function getCampuses(School $school)
    {
        return response()->json($school->campuses()->select('id', 'name')->get());
    }

    /**
     * Check if slug is unique (AJAX).
     */
    public function checkSlug(Request $request)
    {
        $slug = $request->input('slug');
        $exists = Campus::where('slug', $slug)->exists();
        return response()->json(['unique' => !$exists]);
    }

    /**
     * Check if email is unique (AJAX).
     */
    public function checkEmail(Request $request)
    {
        $email = $request->input('email');
        $type = $request->input('type', 'user'); // user or school

        if ($type === 'school') {
            $exists = School::where('email', $email)->exists();
        } else {
            $exists = User::where('email', $email)->exists();
        }

        return response()->json(['unique' => !$exists]);
    }
}

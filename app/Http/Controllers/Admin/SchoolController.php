<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campus;
use App\Models\Country;
use App\Models\School;
use App\Models\SubscriptionPackage;
use App\Models\User;
use Illuminate\Http\Request;
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
            ->withCount('users')
            ->get();
            
        return view('admin.schools.index', compact('schools'));
    }

    /**
     * Show the form for creating a new school.
     */
    public function create()
    {
        $countries = Country::all();
        $packages = SubscriptionPackage::where('is_active', true)->get();
        return view('admin.schools.create', compact('countries', 'packages'));
    }

    /**
     * Store a newly created school in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            // School Profile
            'school_name' => 'required|string|max:255',
            'school_email' => 'required|email|unique:schools,email',
            'school_phone' => 'required|string|max:20',
            'subscription_package_id' => 'required|exists:subscription_packages,id',
            
            // Location / Main Campus
            'campus_name' => 'required|string|max:255',
            'country_id' => 'required|exists:countries,id',
            'city_id' => 'required|exists:cities,id',
            'address' => 'required|string|max:500',
            'campus_slug' => 'required|string|unique:campuses,slug',
            
            // Owner Profile
            'owner_name' => 'required|string|max:255',
            'owner_email' => 'required|email|unique:users,email',
            'owner_phone' => 'required|string|max:20',
            
            // Branding
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'favicon' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,ico|max:1024',
        ]);

        try {
            DB::beginTransaction();

            $school = School::create([
                'name' => $request->school_name,
                'email' => $request->school_email,
                'phone' => $request->school_phone,
                'subscription_package_id' => $request->subscription_package_id,
                'slug' => Str::slug($request->school_name) . '-' . time(),
            ]);

            // Handle Branding Uploads
            if ($request->hasFile('logo')) {
                $school->logo = $this->optimizeImage($request->file('logo'), 'logos', 400, 400);
            }
            if ($request->hasFile('favicon')) {
                $school->favicon = $this->optimizeImage($request->file('favicon'), 'favicons', 64, 64);
            }
            $school->save();

            $user = User::create([
                'name' => $request->owner_name,
                'email' => $request->owner_email,
                'password' => Hash::make('password'),
                'school_id' => $school->id,
            ]);
            $user->assignRole('school owner');

            Campus::create([
                'name' => $request->campus_name,
                'school_id' => $school->id,
                'country_id' => $request->country_id,
                'city_id' => $request->city_id,
                'address' => $request->address,
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
            'school_email' => 'required|email|unique:schools,email,' . $school->id,
            'school_phone' => 'required|string|max:20',
            'subscription_package_id' => 'required|exists:subscription_packages,id',
            
            'campus_name' => 'required|string|max:255',
            'country_id' => 'required|exists:countries,id',
            'city_id' => 'required|exists:cities,id',
            'address' => 'required|string|max:500',
            
            'owner_name' => 'required|string|max:255',
            'owner_email' => 'required|email|unique:users,email,' . ($owner->id ?? 0),

            // Branding
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'favicon' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,ico|max:1024',
        ]);

        try {
            DB::beginTransaction();

            $school->update([
                'name' => $request->school_name,
                'email' => $request->school_email,
                'phone' => $request->school_phone,
                'subscription_package_id' => $request->subscription_package_id,
            ]);

            // Handle Branding Uploads
            if ($request->hasFile('logo')) {
                $school->logo = $this->optimizeImage($request->file('logo'), 'logos', 400, 400);
            }
            if ($request->hasFile('favicon')) {
                $school->favicon = $this->optimizeImage($request->file('favicon'), 'favicons', 64, 64);
            }
            $school->save();

            if ($school->mainCampus) {
                $school->mainCampus->update([
                    'name' => $request->campus_name,
                    'country_id' => $request->country_id,
                    'city_id' => $request->city_id,
                    'address' => $request->address,
                ]);
            }

            if ($owner) {
                $owner->update([
                    'name' => $request->owner_name,
                    'email' => $request->owner_email,
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
}

<?php

namespace App\Livewire\Admin;

use App\Models\City;
use App\Models\Country;
use App\Models\School;
use App\Models\Campus;
use App\Models\SubscriptionPackage;
use App\Models\User;
use App\Mail\SchoolWelcomeMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\Attributes\Rule;

class SchoolRegistration extends Component
{
    // Owner Profile
    #[Rule('required|string|max:255|regex:/^[a-zA-Z\s]+$/')]
    public $owner_name = '';

    #[Rule('required|email|unique:users,email')]
    public $owner_email = '';

    #[Rule('required|string|max:20')]
    public $owner_phone = '';

    public $owner_phone_prefix = '+92';

    // School Info
    #[Rule('required|string|max:255')]
    public $school_name = '';

    #[Rule('required|string|unique:campuses,slug')]
    public $campus_slug = '';

    #[Rule('nullable|string|max:255|unique:schools,website')]
    public $school_website = '';

    // Main Campus Info
    #[Rule('required|string|max:255')]
    public $campus_name = 'Main Campus';

    #[Rule('required|email')]
    public $campus_email = '';

    #[Rule('required|string|max:20')]
    public $campus_phone = '';

    public $campus_phone_prefix = '+92';

    #[Rule('required|exists:countries,id')]
    public $country_id;

    #[Rule('required|exists:cities,id')]
    public $city_id;

    #[Rule('required|string|max:500')]
    public $address = '';

    // Subscription
    #[Rule('required|exists:subscription_packages,id')]
    public $subscription_package_id;

    // Lists for dropdowns
    public $countries;
    public $cities = [];
    public $packages;

    public function mount()
    {
        $this->countries = Country::all();
        $this->packages = SubscriptionPackage::where('is_active', true)->get();
        
        // Default to Free Package (ID 1 as confirmed by tinker)
        $this->subscription_package_id = 1;

        // Default Pakistan (ID 167 usually, but let's find by code)
        $pakistan = Country::where('code', 'pk')->orWhere('name', 'Pakistan')->first();
        if ($pakistan) {
            $this->country_id = $pakistan->id;
            $this->loadCities();
        }
    }

    public function updatedCountryId($value)
    {
        $this->loadCities();
        $this->city_id = null;
    }

    public function loadCities()
    {
        if ($this->country_id) {
            $this->cities = City::where('country_id', $this->country_id)->orderBy('name')->get();
        } else {
            $this->cities = [];
        }
    }

    public function updatedSchoolName($value)
    {
        $this->campus_slug = Str::slug($value);
        $this->validateOnly('campus_slug');
    }

    public function updatedSchoolWebsite($value)
    {
        if (!$value) return;

        // Intelligent URL Normalization
        // 1. Remove any existing protocols and www
        $clean = preg_replace('#^https?://#', '', $value);
        $clean = preg_replace('#^www\.#', '', $clean);
        $clean = rtrim($clean, '/');

        // 2. Check if it's a subdomain or direct domain
        // A simple check: if it has more than 1 dot before the TLD, it might be a subdomain
        // But better: if it has only one dot total, it's a direct domain -> add www.
        // Actually, many TLDs have dots (e.g. .com.pk).
        // Let's use a common approach: if it doesn't already have a subdomain-like structure, add www.
        
        $parts = explode('.', $clean);
        
        if (count($parts) === 2) {
            // e.g. domain.com -> https://www.domain.com
            $this->school_website = 'https://www.' . $clean;
        } else {
            // e.g. subdomain.domain.com or domain.co.uk -> https://clean
            // For domain.co.uk, count is 3. 
            // Better heuristic: if the first part is NOT a common subdomain prefix, 
            // and it's 2 parts or 3 parts ending in a 2-letter ccTLD.
            if (count($parts) === 3 && strlen(end($parts)) === 2) {
                 // e.g. elaf.com.pk -> https://www.elaf.com.pk
                 $this->school_website = 'https://www.' . $clean;
            } else {
                 $this->school_website = 'https://' . $clean;
            }
        }

        $this->validateOnly('school_website');
    }

    public function save()
    {
        $this->validate();

        try {
            DB::beginTransaction();

            $school = School::create([
                'name' => $this->school_name,
                'email' => $this->owner_email,
                'phone' => $this->owner_phone_prefix . $this->owner_phone,
                'website' => $this->school_website,
                'subscription_package_id' => $this->subscription_package_id,
                'slug' => Str::slug($this->school_name) . '-' . time(),
            ]);

            // Generated password for Welcome Email
            $tempPassword = Str::random(10);

            $user = User::create([
                'name' => $this->owner_name,
                'email' => $this->owner_email,
                'password' => Hash::make($tempPassword),
                'school_id' => $school->id,
                'must_change_password' => true,
            ]);
            $user->assignRole('school owner');

            Campus::create([
                'name' => $this->campus_name,
                'school_id' => $school->id,
                'country_id' => $this->country_id,
                'city_id' => $this->city_id,
                'address' => $this->address,
                'email' => $this->campus_email,
                'phone' => $this->campus_phone_prefix . $this->campus_phone,
                'slug' => $this->campus_slug,
                'is_main' => true,
            ]);

            try {
                Mail::to($user->email)->send(new SchoolWelcomeMail($user, $school, $tempPassword));
            } catch (\Exception $e) {
                Log::error("Registration Mail Error: " . $e->getMessage());
            }

            DB::commit();
            return redirect()->route('schools.index')->with('success', 'School registered successfully. Credentials sent to ' . $user->email);

        } catch (\Exception $e) {
            DB::rollBack();
            $this->addError('registration_error', 'Failed to register school: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.school-registration');
    }
}

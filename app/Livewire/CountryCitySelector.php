<?php

namespace App\Livewire;

use App\Models\City;
use App\Models\Country;
use Livewire\Component;

class CountryCitySelector extends Component
{
    public $countries;
    public $cities = [];
    public $country_id;
    public $city_id;

    public function mount($country_id = null, $city_id = null)
    {
        $this->countries = Country::all();
        
        // Handle pre-selected country (e.g. from old() or edit)
        if ($country_id) {
            $this->country_id = $country_id;
        } else {
            // Default to Pakistan if it exists
            $pakistan = Country::where('code', 'pk')->orWhere('name', 'Pakistan')->first();
            if ($pakistan) {
                $this->country_id = $pakistan->id;
            }
        }

        // Load cities for the initial country
        if ($this->country_id) {
            $this->cities = City::where('country_id', $this->country_id)->orderBy('name')->get();
        }

        // Set initial city
        $this->city_id = $city_id;
    }

    public function updatedCountryId($value)
    {
        if ($value) {
            $this->cities = City::where('country_id', $value)->orderBy('name')->get();
        } else {
            $this->cities = [];
        }
        $this->city_id = null;
        
        // Re-initialize any JS if needed by dispatching event
        $this->dispatch('country-updated');
    }

    public function render()
    {
        return view('livewire.country-city-selector');
    }
}

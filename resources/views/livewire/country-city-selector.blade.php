<div class="row g-3 mb-3" 
    x-data="{ 
        country_id: @entangle('country_id'),
        city_id: @entangle('city_id'),
        initSelect2(el, model) {
            $(el).select2({ theme: 'bootstrap-5', width: '100%' })
                .on('change', (e) => { this[model] = e.target.value; });
        }
    }" 
>
    <!-- Country Select -->
    <div class="col-md-3">
        <label class="form-label text-700 fw-bold" for="country_id">Country <span class="text-danger">*</span></label>
        <div wire:ignore>
            <select class="form-select @error('country_id') is-invalid @enderror" 
                    id="country_id" 
                    name="country_id" 
                    x-init="initSelect2($el, 'country_id')"
                    required>
                <option value="">Select Country</option>
                @foreach($countries as $country)
                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                @endforeach
            </select>
        </div>
        @error('country_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
    </div>

    <!-- City Select -->
    <div class="col-md-3">
        <label class="form-label text-700 fw-bold" for="city_id">City <span class="text-danger">*</span></label>
        <div wire:ignore>
            <select class="form-select @error('city_id') is-invalid @enderror" 
                    id="city_id" 
                    name="city_id" 
                    x-init="initSelect2($el, 'city_id')"
                    x-effect="
                        // This watcher re-populates Select2 options when Livewire updates the property
                        let options = '';
                        options += '<option value=\'\'>Select City</option>';
                        $wire.cities.forEach(city => {
                            options += `<option value='${city.id}'>${city.name}</option>`;
                        });
                        $($el).html(options).val(city_id).trigger('change.select2');
                    "
                    required>
                <option value="">Select City</option>
            </select>
        </div>
        @error('city_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
    </div>
</div>

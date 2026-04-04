<div>
    <form wire:submit.prevent="save">
        <!-- Error Alerts -->
        @if($errors->has('registration_error'))
            <div class="alert alert-danger mb-4 shadow-sm border-0 d-flex align-items-center">
                <span class="fas fa-exclamation-triangle me-2"></span>
                {{ $errors->first('registration_error') }}
            </div>
        @endif

        <!-- I. School Owner Profile -->
        <div class="mb-4">
            <div class="d-flex align-items-center mb-3">
                <span class="badge badge-subtle-primary rounded-pill me-2">I</span>
                <h6 class="text-uppercase text-700 fs-11 mb-0">School Owner Profile</h6>
            </div>
            <div class="card shadow-none border">
                <div class="card-body bg-white rounded">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label text-700 fw-bold" for="owner_name">Full Name <span class="text-danger">*</span></label>
                            <input class="form-control @error('owner_name') is-invalid @enderror" 
                                id="owner_name" 
                                wire:model.blur="owner_name" 
                                type="text" 
                                placeholder="e.g. John Doe" />
                            @error('owner_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-700 fw-bold" for="owner_email">Login Email <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-primary"><span class="fas fa-envelope"></span></span>
                                <input class="form-control @error('owner_email') is-invalid @enderror" 
                                    id="owner_email" 
                                    wire:model.blur="owner_email" 
                                    type="email" 
                                    placeholder="owner@example.com" />
                            </div>
                            @error('owner_email') <div class="text-danger fs-11 mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-700 fw-bold" for="owner_phone">Phone Number <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-primary"><span class="fas fa-phone"></span></span>
                                <select class="form-select border-end-0" style="max-width: 100px;" wire:model="owner_phone_prefix">
                                    <option value="+92">+92 (PK)</option>
                                    <option value="+966">+966 (SA)</option>
                                    <option value="+971">+971 (AE)</option>
                                    <option value="+44">+44 (UK)</option>
                                    <option value="+1">+1 (US)</option>
                                </select>
                                <input class="form-control @error('owner_phone') is-invalid @enderror" 
                                    id="owner_phone" 
                                    wire:model.blur="owner_phone" 
                                    type="text" 
                                    placeholder="3331234567" />
                            </div>
                            @error('owner_phone') <div class="text-danger fs-11 mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- II. School Information -->
        <div class="mb-4">
            <div class="d-flex align-items-center mb-3">
                <span class="badge badge-subtle-primary rounded-pill me-2">II</span>
                <h6 class="text-uppercase text-700 fs-11 mb-0">School Information</h6>
            </div>
            <div class="card shadow-none border">
                <div class="card-body bg-white rounded">
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label text-700 fw-bold" for="school_name">School Name <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-primary"><span class="fas fa-university"></span></span>
                                <input class="form-control @error('school_name') is-invalid @enderror" 
                                    id="school_name" 
                                    wire:model.live.debounce.500ms="school_name" 
                                    type="text" 
                                    placeholder="Falcon International School" />
                            </div>
                            @error('school_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-700 fw-bold" for="campus_slug">Subdomain / Slug <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-200">elaf.com/s/</span>
                                <input class="form-control @error('campus_slug') is-invalid @enderror" 
                                    id="campus_slug" 
                                    wire:model.live.debounce.500ms="campus_slug" 
                                    type="text" />
                                <span class="input-group-text bg-white" wire:loading wire:target="campus_slug">
                                    <span class="spinner-border spinner-border-sm text-primary" role="status"></span>
                                </span>
                            </div>
                            @error('campus_slug') <div class="text-danger fs-11 mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-700 fw-bold" for="school_website">Official Website</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-primary"><span class="fas fa-globe"></span></span>
                                <input class="form-control @error('school_website') is-invalid @enderror" 
                                    id="school_website" 
                                    wire:model.blur="school_website" 
                                    type="text" 
                                    placeholder="www.school.com" />
                            </div>
                            <div class="text-muted fs-11 mt-1">Normalizes to https://www.yoursite.com</div>
                            @error('school_website') <div class="text-danger fs-11 mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- III. Main Campus -->
        <div class="mb-4">
            <div class="d-flex align-items-center mb-3">
                <span class="badge badge-subtle-primary rounded-pill me-2">III</span>
                <h6 class="text-uppercase text-700 fs-11 mb-0">Main Campus</h6>
            </div>
            <div class="card shadow-none border">
                <div class="card-body bg-white rounded">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-700 fw-bold" for="campus_name">Main Campus Name <span class="text-danger">*</span></label>
                            <input class="form-control @error('campus_name') is-invalid @enderror" 
                                id="campus_name" 
                                wire:model.blur="campus_name" 
                                type="text" />
                            @error('campus_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-700 fw-bold" for="campus_email">Campus Contact Email <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-primary"><span class="fas fa-envelope"></span></span>
                                <input class="form-control @error('campus_email') is-invalid @enderror" 
                                    id="campus_email" 
                                    wire:model.blur="campus_email" 
                                    type="email" />
                            </div>
                            @error('campus_email') <div class="text-danger fs-11 mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    
                    <div class="row g-3 mb-3">
                        <div class="col-md-3">
                            <label class="form-label text-700 fw-bold" for="country_id">Country <span class="text-danger">*</span></label>
                            <select class="form-select @error('country_id') is-invalid @enderror" wire:model.live="country_id" id="country_id">
                                <option value="">Select Country</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                                @endforeach
                            </select>
                            @error('country_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-700 fw-bold" for="city_id">City <span class="text-danger">*</span></label>
                            <select class="form-select @error('city_id') is-invalid @enderror" wire:model.live="city_id" id="city_id">
                                <option value="">Select City</option>
                                @foreach($cities as $city)
                                    <option value="{{ $city->id }}">{{ $city->name }}</option>
                                @endforeach
                            </select>
                            @error('city_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-700 fw-bold" for="campus_phone">Campus Phone <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-primary"><span class="fas fa-phone"></span></span>
                                <select class="form-select border-end-0" style="max-width: 100px;" wire:model="campus_phone_prefix">
                                    <option value="+92">+92 (PK)</option>
                                    <option value="+966">+966 (SA)</option>
                                </select>
                                <input class="form-control @error('campus_phone') is-invalid @enderror" 
                                    wire:model.blur="campus_phone" 
                                    type="text" />
                            </div>
                            @error('campus_phone') <div class="text-danger fs-11 mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label text-700 fw-bold" for="address">Full Address <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-primary"><span class="fas fa-map-marker-alt"></span></span>
                                <input class="form-control @error('address') is-invalid @enderror" 
                                    id="address" 
                                    wire:model.blur="address" 
                                    type="text" />
                            </div>
                            @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- IV. Subscription Package -->
        <div class="mb-4">
            <div class="d-flex align-items-center mb-3">
                <span class="badge badge-subtle-primary rounded-pill me-2">IV</span>
                <h6 class="text-uppercase text-700 fs-11 mb-0">Subscription Package</h6>
            </div>
            <div class="row g-3">
                @foreach($packages as $package)
                <div class="col-md-3">
                    <div class="card h-100 border pointer-event {{ $subscription_package_id == $package->id ? 'border-primary shadow-sm bg-subtle-primary' : '' }}" 
                        style="cursor: pointer" 
                        wire:click="$set('subscription_package_id', {{ $package->id }})">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="mb-0 fw-bold">{{ $package->name }}</h6>
                                <span class="badge rounded-pill {{ $package->name == 'Premium' ? 'badge-subtle-warning' : 'badge-subtle-primary' }} fs-11">
                                    ${{ number_format($package->price, 0) }}
                                </span>
                            </div>
                            <ul class="list-unstyled fs-11 mb-0">
                                <li class="mb-1"><span class="fas fa-check text-success me-1"></span>{{ $package->student_limit }} Students</li>
                                <li class="mb-1"><span class="fas fa-check text-success me-1"></span>{{ $package->staff_limit }} Staff</li>
                                <li class="mb-1"><span class="fas fa-check text-success me-1"></span>{{ number_format($package->entry_limit) }} Entries</li>
                            </ul>
                        </div>
                        <div class="card-footer p-2 text-center border-top-0">
                            <div class="form-check mb-0 d-inline-block">
                                <input class="form-check-input ms-0" type="radio" 
                                    value="{{ $package->id }}" 
                                    wire:model="subscription_package_id">
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @error('subscription_package_id') <div class="text-danger fs-11 mt-1">{{ $message }}</div> @enderror
        </div>

        <div class="mt-5 border-top pt-3 d-flex justify-content-between align-items-center">
            <a href="{{ route('schools.index') }}" class="btn btn-falcon-default">Cancel Registration</a>
            <button class="btn btn-primary px-5 fw-bold" type="submit" wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="save">
                    <span class="fas fa-check-circle me-2"></span>Complete Registration
                </span>
                <span wire:loading wire:target="save">
                    <span class="spinner-border spinner-border-sm me-2" role="status"></span>Processing...
                </span>
            </button>
        </div>
    </form>
</div>

@extends('layouts.admin')

@section('title', 'Edit School - ' . $school->name)

@section('content')
<div class="card mb-3">
    <div class="card-header bg-body-tertiary">
        <h5 class="mb-0">Edit School: {{ $school->name }}</h5>
    </div>
    <div class="card-body bg-body-tertiary">
        <form action="{{ route('schools.update', $school->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <!-- School Profile Section -->
            <div class="mb-4">
                <h6 class="text-uppercase text-700 fs-11 mb-3">I. School Profile & Subscription</h6>
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label text-700" for="school_name">School Name</label>
                        <input class="form-control @error('school_name') is-invalid @enderror" id="school_name" name="school_name" type="text" value="{{ old('school_name', $school->name) }}" required />
                        @error('school_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-700" for="school_phone">Phone</label>
                        <input class="form-control @error('school_phone') is-invalid @enderror" id="school_phone" name="school_phone" type="text" value="{{ old('school_phone', $school->phone) }}" required />
                        @error('school_phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-700" for="school_email">Official Email</label>
                        <input class="form-control @error('school_email') is-invalid @enderror" id="school_email" name="school_email" type="email" value="{{ old('school_email', $school->email) }}" required />
                        @error('school_email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                <div class="col-md-12">
                    <label class="form-label text-700 mb-3">IV. Subscription Package</label>
                    <input type="hidden" name="subscription_package_id" id="selected_package_id" value="{{ old('subscription_package_id', $school->subscription_package_id) }}" required>
                    <div class="row g-3">
                        @foreach($packages as $package)
                        <div class="col-md-3">
                            <div class="card h-100 subscription-card cursor-pointer border {{ old('subscription_package_id', $school->subscription_package_id) == $package->id ? 'border-primary shadow-sm bg-subtle-primary' : '' }}" data-package-id="{{ $package->id }}">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="mb-0 fw-bold">{{ $package->name }}</h6>
                                        <span class="badge rounded-pill {{ $package->name == 'Premium' ? 'badge-subtle-warning' : 'badge-subtle-primary' }} fs-11">${{ number_format($package->price, 0) }}</span>
                                    </div>
                                    <ul class="list-unstyled fs-11 mb-0">
                                        <li class="mb-1"><span class="fas fa-check text-success me-1"></span>{{ $package->student_limit }} Students</li>
                                        <li class="mb-1"><span class="fas fa-check text-success me-1"></span>{{ $package->staff_limit }} Staff</li>
                                        <li class="mb-1"><span class="fas fa-check text-success me-1"></span>{{ number_format($package->entry_limit) }} Entries</li>
                                        <li class="mb-0"><span class="fas {{ $package->has_tech_support ? 'fa-check text-success' : 'fa-times text-danger' }} me-1"></span>Tech Support</li>
                                    </ul>
                                </div>
                                <div class="card-footer p-2 text-center border-top-0">
                                    <div class="form-check mb-0 d-inline-block">
                                        <input class="form-check-input ms-0" type="radio" name="pkg_radio" id="pkg_{{ $package->id }}" value="{{ $package->id }}" {{ old('subscription_package_id', $school->subscription_package_id) == $package->id ? 'checked' : '' }}>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @error('subscription_package_id') <div class="text-danger fs-11 mt-2 text-center">{{ $message }}</div> @enderror
                </div>
                </div>
            </div>

            <hr class="my-4 text-300" />

            <!-- Location / Main Campus Section -->
            <div class="mb-4">
                <h6 class="text-uppercase text-700 fs-11 mb-3">II. Main Campus Details</h6>
                @if($school->mainCampus)
                <div class="row g-3 mb-3">
                    <div class="col-md-12">
                        <label class="form-label text-700" for="campus_name">Main Campus Name</label>
                        <input class="form-control @error('campus_name') is-invalid @enderror" id="campus_name" name="campus_name" type="text" value="{{ old('campus_name', $school->mainCampus->name) }}" required />
                    </div>
                </div>
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label text-700" for="country_id">Country</label>
                        <select class="form-select select2 @error('country_id') is-invalid @enderror" id="country_id" name="country_id" required>
                            @foreach($countries as $country)
                                <option value="{{ $country->id }}" {{ old('country_id', $school->mainCampus->country_id) == $country->id ? 'selected' : '' }}>{{ $country->name }}</option>
                            @endforeach
                        </select>
                        @error('country_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-700" for="city_id">City</label>
                        <select class="form-select select2 @error('city_id') is-invalid @enderror" id="city_id" name="city_id" required>
                            <option value="{{ $school->mainCampus->city_id }}">{{ $school->mainCampus->city->name ?? 'Select City' }}</option>
                        </select>
                        @error('city_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-700" for="address">Full Address</label>
                        <input class="form-control @error('address') is-invalid @enderror" id="address" name="address" type="text" value="{{ old('address', $school->mainCampus->address) }}" required />
                    </div>
                </div>
                @else
                <p class="text-warning">Main campus not found. Please contact support.</p>
                @endif
            </div>

            <hr class="my-4 text-300" />

            <!-- Owner Profile Section -->
            <div class="mb-4">
                <h6 class="text-uppercase text-700 fs-11 mb-3">III. Owner Account Details</h6>
                @if($owner)
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label text-700" for="owner_name">Full Name</label>
                        <input class="form-control @error('owner_name') is-invalid @enderror" id="owner_name" name="owner_name" type="text" value="{{ old('owner_name', $owner->name) }}" required />
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-700" for="owner_email">Login Email</label>
                        <input class="form-control @error('owner_email') is-invalid @enderror" id="owner_email" name="owner_email" type="email" value="{{ old('owner_email', $owner->email) }}" required />
                    </div>
                </div>
                @else
                <p class="text-warning">Owner profile not found.</p>
                @endif
            </div>

            <hr class="my-4 text-300" />

            <!-- Branding Section -->
            <div class="mb-4">
                <h6 class="text-uppercase text-700 fs-11 mb-3">IV. Branding Assets</h6>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label text-700" for="logo">School Logo</label>
                        @if($school->logo)
                            <div class="mb-2">
                                <img src="{{ asset($school->logo) }}" alt="Logo" class="img-thumbnail" style="height: 50px;">
                            </div>
                        @endif
                        <input class="form-control @error('logo') is-invalid @enderror" id="logo" name="logo" type="file" accept="image/*" />
                        <div class="fs-11 text-600 mt-1">Recommended: 400x400px. Max 2MB. (Optimized to 20-50KB)</div>
                        @error('logo') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-700" for="favicon">Favicon</label>
                        @if($school->favicon)
                            <div class="mb-2">
                                <img src="{{ asset($school->favicon) }}" alt="Favicon" class="img-thumbnail" style="height: 32px;">
                            </div>
                        @endif
                        <input class="form-control @error('favicon') is-invalid @enderror" id="favicon" name="favicon" type="file" accept="image/*" />
                        <div class="fs-11 text-600 mt-1">Recommended: 64x64px. Max 1MB.</div>
                        @error('favicon') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            <div class="mt-5 border-top pt-3 text-end">
                <a href="{{ route('schools.index') }}" class="btn btn-falcon-default me-2">Cancel</a>
                <button class="btn btn-primary" type="submit">Update School Details</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // Country to City AJAX with Select2 support
        $('#country_id').on('change', function() {
            const countryId = $(this).val();
            const $citySelect = $('#city_id');
            
            $citySelect.html('<option value="">Loading...</option>').trigger('change');

            if (countryId) {
                $.getJSON(`/countries/${countryId}/cities`, function(data) {
                    let options = '<option value="">Select City</option>';
                    data.forEach(city => {
                        options += `<option value="${city.id}">${city.name}</option>`;
                    });
                    $citySelect.html(options).trigger('change');
                }).fail(function() {
                    $citySelect.html('<option value="">Error loading cities</option>').trigger('change');
                });
            } else {
                $citySelect.html('<option value="">Select Country First</option>').trigger('change');
            }
        });

        // Subscription card selection
        $('.subscription-card').on('click', function() {
            $('.subscription-card').removeClass('border-primary shadow-sm bg-subtle-primary');
            $('.subscription-card input[type="radio"]').prop('checked', false);
            
            $(this).addClass('border-primary shadow-sm bg-subtle-primary');
            const pkgId = $(this).data('package-id');
            $('#selected_package_id').val(pkgId);
            $(this).find('input[type="radio"]').prop('checked', true);
        });
    });
</script>
<style>
    .cursor-pointer { cursor: pointer; }
    .subscription-card:hover { border-color: var(--falcon-primary) !important; }
</style>
@endpush
@endsection

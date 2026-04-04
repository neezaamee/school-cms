@extends('layouts.admin')

@section('title', 'Edit School - ' . $school->name)

@push('css')
<style>
    .subscription-card { cursor: pointer; transition: all 0.2s; }
    .subscription-card:hover { border-color: var(--falcon-primary) !important; transform: translateY(-2px); }
    
    /* Professional Phone Input Styling */
    .phone-prefix-select { 
        max-width: 120px !important; 
        border-top-right-radius: 0 !important;
        border-bottom-right-radius: 0 !important;
    }
    .phone-number-input {
        border-top-left-radius: 0 !important;
        border-bottom-left-radius: 0 !important;
    }
    .choices[data-type*='select-one'] .choices__inner {
        padding-bottom: 2px !important;
        min-height: 38px !important;
    }
    .choices__list--single {
        padding: 0 !important;
    }
</style>
@endpush

@section('content')
@php
    function splitPhone($fullPhone, $prefixes) {
        $fullPhone = trim($fullPhone ?? '');
        if (!$fullPhone) return ['prefix' => '+92', 'number' => ''];
        foreach ($prefixes as $p) {
            if (str_starts_with($fullPhone, $p)) {
                return ['prefix' => $p, 'number' => substr($fullPhone, strlen($p))];
            }
        }
        return ['prefix' => '+92', 'number' => $fullPhone];
    }

    $validPrefixes = ['+92', '+966', '+971', '+44', '+1', '+61', '+974', '+968', '+965', '+973', '+90', '+60', '+65'];
    
    $ownerPhoneData = splitPhone($owner->phone ?? '', $validPrefixes);
    $campusPhoneData = splitPhone($school->mainCampus->phone ?? '', $validPrefixes);
@endphp

<div class="card mb-3">
    <div class="card-header bg-body-tertiary d-flex justify-content-between align-items-center">
        <h5 class="mb-0 text-primary"><span class="fas fa-edit me-2"></span>Edit School: {{ $school->name }}</h5>
        <a href="{{ route('schools.index') }}" class="btn btn-falcon-default btn-sm"><span class="fas fa-arrow-left me-1"></span> Back to List</a>
    </div>
    <div class="card-body bg-body-tertiary">
        <form action="{{ route('schools.update', $school->id) }}" method="POST" enctype="multipart/form-data" id="schoolEditForm">
            @csrf
            @method('PUT')
            
            <!-- I. School Owner Profile -->
            <div class="mb-4">
                <div class="d-flex align-items-center mb-3">
                    <span class="badge badge-subtle-primary rounded-pill me-2">I</span>
                    <h6 class="text-uppercase text-700 fs-11 mb-0">School Owner Profile</h6>
                </div>
                <div class="card shadow-none">
                    <div class="card-body bg-white border rounded">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label text-700 fw-bold" for="owner_name">Full Name <span class="text-danger">*</span></label>
                                <input class="form-control @error('owner_name') is-invalid @enderror" id="owner_name" name="owner_name" type="text" value="{{ old('owner_name', $owner->name ?? '') }}" required />
                                @error('owner_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-700 fw-bold" for="owner_email">Login Email <span class="text-danger">*</span></label>
                                <input class="form-control @error('owner_email') is-invalid @enderror" id="owner_email" name="owner_email" type="email" value="{{ old('owner_email', $owner->email ?? '') }}" required />
                                @error('owner_email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-700 fw-bold" for="owner_phone">Phone Number <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <select class="form-select phone-prefix" name="owner_phone_prefix" data-choices='{"searchEnabled":true,"itemSelectText":""}'>
                                        @foreach($validPrefixes as $pref)
                                            <option value="{{ $pref }}" {{ (old('owner_phone_prefix', $ownerPhoneData['prefix']) == $pref) ? 'selected' : '' }}>{{ $pref }}</option>
                                        @endforeach
                                    </select>
                                    <input class="form-control phone-number-input @error('owner_phone') is-invalid @enderror" id="owner_phone" name="owner_phone" type="text" value="{{ old('owner_phone', $ownerPhoneData['number']) }}" required />
                                </div>
                                @error('owner_phone') <div class="text-danger fs-11 mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="fs-11 text-600 mt-2">Note: This contact information is linked as the primary contact for this school.</div>
                    </div>
                </div>
            </div>

            <!-- II. School Information -->
            <div class="mb-4">
                <div class="d-flex align-items-center mb-3">
                    <span class="badge badge-subtle-primary rounded-pill me-2">II</span>
                    <h6 class="text-uppercase text-700 fs-11 mb-0">School Information</h6>
                </div>
                <div class="card shadow-none">
                    <div class="card-body bg-white border rounded">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label text-700 fw-bold" for="school_name">School Name <span class="text-danger">*</span></label>
                                <input class="form-control @error('school_name') is-invalid @enderror" id="school_name" name="school_name" type="text" value="{{ old('school_name', $school->name) }}" required />
                                @error('school_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-700 fw-bold" for="school_website">Official Website</label>
                                <input class="form-control mask-url @error('school_website') is-invalid @enderror" id="school_website" name="school_website" type="text" value="{{ old('school_website', $school->website) }}" placeholder="https://www.school.com" />
                                @error('school_website') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
                <div class="card shadow-none">
                    <div class="card-body bg-white border rounded">
                        @if($school->mainCampus)
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label text-700 fw-bold" for="campus_name">Main Campus Name <span class="text-danger">*</span></label>
                                <input class="form-control @error('campus_name') is-invalid @enderror" id="campus_name" name="campus_name" type="text" value="{{ old('campus_name', $school->mainCampus->name) }}" required />
                                @error('campus_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-700 fw-bold" for="campus_email">Campus Contact Email <span class="text-danger">*</span></label>
                                <input class="form-control @error('campus_email') is-invalid @enderror" id="campus_email" name="campus_email" type="email" value="{{ old('campus_email', $school->mainCampus->email) }}" required />
                                @error('campus_email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        @livewire('country-city-selector', [
                            'country_id' => old('country_id', $school->mainCampus->country_id ?? ''),
                            'city_id' => old('city_id', $school->mainCampus->city_id ?? '')
                        ])
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label text-700 fw-bold" for="campus_phone">Campus Phone <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <select class="form-select phone-prefix" id="campus_phone_prefix" name="campus_phone_prefix" data-choices='{"searchEnabled":true,"itemSelectText":""}'>
                                        @foreach($validPrefixes as $pref)
                                            <option value="{{ $pref }}" {{ (old('campus_phone_prefix', $campusPhoneData['prefix']) == $pref) ? 'selected' : '' }}>{{ $pref }}</option>
                                        @endforeach
                                    </select>
                                    <input class="form-control phone-number-input @error('campus_phone') is-invalid @enderror" id="campus_phone" name="campus_phone" type="text" value="{{ old('campus_phone', $campusPhoneData['number']) }}" required />
                                </div>
                                @error('campus_phone') <div class="text-danger fs-11 mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label text-700 fw-bold" for="address">Full Address <span class="text-danger">*</span></label>
                                <input class="form-control @error('address') is-invalid @enderror" id="address" name="address" type="text" value="{{ old('address', $school->mainCampus->address) }}" required />
                                @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- IV. Subscription Package -->
            <div class="mb-4">
                <div class="d-flex align-items-center mb-3">
                    <span class="badge badge-subtle-primary rounded-pill me-2">IV</span>
                    <h6 class="text-uppercase text-700 fs-11 mb-0">Subscription Package</h6>
                </div>
                <input type="hidden" name="subscription_package_id" id="selected_package_id" value="{{ old('subscription_package_id', $school->subscription_package_id) }}" required>
                <div class="row g-3">
                    @foreach($packages as $package)
                    <div class="col-md-3">
                        <div class="card h-100 subscription-card border {{ old('subscription_package_id', $school->subscription_package_id) == $package->id ? 'border-primary shadow-sm bg-subtle-primary' : '' }}" data-package-id="{{ $package->id }}">
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
            </div>

            <div class="mt-5 border-top pt-3 text-end">
                <button class="btn btn-primary px-5 fw-bold" type="submit"><span class="fas fa-save me-2"></span>Update Settings</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize Choices.js
        document.querySelectorAll('.phone-prefix').forEach(select => {
            new Choices(select, { searchEnabled: true, itemSelectText: '', shouldSort: false, allowHTML: true });
        });

        // URL Masking
        $(".mask-url").inputmask({
            mask: "http[s]://{+}",
            placeholder: "",
            greedy: false,
            definitions: {
                '+': {
                    validator: "[0-9a-zA-Z\\+\\-\\.\\_\\~\\!\\$\\&\\'\\(\\)\\*\\,\\;\\=\\?]",
                    cardinality: 1
                }
            }
        });

        // Alpha only
        $('#owner_name').on('input', function() { this.value = this.value.replace(/[^a-zA-Z\s]/g, ''); });


        // Subscription Card
        $('.subscription-card').on('click', function() {
            $('.subscription-card').removeClass('border-primary shadow-sm bg-subtle-primary');
            $(this).addClass('border-primary shadow-sm bg-subtle-primary');
            $('#selected_package_id').val($(this).data('package-id'));
            $(this).find('input[type="radio"]').prop('checked', true);
        });
    });
</script>
@endpush
@endsection

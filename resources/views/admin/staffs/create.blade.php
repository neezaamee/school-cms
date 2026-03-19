@extends('layouts.admin')

@section('content')
<div class="card mb-3">
    <div class="card-header">
        <h5 class="mb-0">Add New Staff Member</h5>
    </div>
    <div class="card-body bg-body-tertiary">
        <form action="{{ route('admin.staffs.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="row g-3">
                <div class="col-lg-6">
                    <div class="card shadow-none">
                        <div class="card-header bg-body-secondary py-2">
                            <h6 class="mb-0">Basic Account Information</h6>
                        </div>
                        <div class="card-body border-top">
                            <div class="mb-3">
                                <label class="form-label" for="name">Full Name</label>
                                <input class="form-control @error('name') is-invalid @enderror" id="name" name="name" type="text" value="{{ old('name') }}" required />
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="email">Email Address</label>
                                <input class="form-control @error('email') is-invalid @enderror" id="email" name="email" type="email" value="{{ old('email') }}" required />
                                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                             <div class="mb-3">
                                <p class="text-700 fs-10 mb-0"><small><span class="fas fa-info-circle me-1"></span>Staff members are created as profiles first. A user login account can be created later by an authorized administrator.</small></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card shadow-none">
                        <div class="card-body border-top">
                            @if(auth()->user()->hasRole('super admin'))
                                <div class="mb-3">
                                    <label class="form-label" for="school_id">School</label>
                                    <select class="form-select @error('school_id') is-invalid @enderror" id="school_id" name="school_id" required>
                                        <option value="">Select School</option>
                                        @foreach($schools as $school)
                                            <option value="{{ $school->id }}" {{ old('school_id') == $school->id ? 'selected' : '' }}>{{ $school->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('school_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            @endif
                            <div class="mb-3">
                                <label class="form-label" for="designation_id">Designation</label>
                                <select class="form-select select2 @error('designation_id') is-invalid @enderror" id="designation_id" name="designation_id" data-placeholder="Choose Designations" required>
                                    <option value=""></option>
                                    @foreach($designations as $category => $titles)
                                        <optgroup label="{{ $category }}">
                                            @foreach($titles as $title)
                                                <option value="{{ $title->id }}" {{ old('designation_id') == $title->id ? 'selected' : '' }}>{{ $title->name }}</option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                                @error('designation_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="campus_id">Campus</label>
                                <select class="form-select @error('campus_id') is-invalid @enderror" id="campus_id" name="campus_id">
                                    <option value="">Select Campus</option>
                                    @foreach($campuses as $campus)
                                        <option value="{{ $campus->id }}" {{ old('campus_id') == $campus->id ? 'selected' : '' }}>{{ $campus->name }}</option>
                                    @endforeach
                                </select>
                                @error('campus_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="profile_photo">Profile Photo</label>
                                <input class="form-control @error('profile_photo') is-invalid @enderror" id="profile_photo" name="profile_photo" type="file" accept="image/*" />
                                @error('profile_photo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="card shadow-none">
                        <div class="card-header bg-body-secondary py-2">
                            <h6 class="mb-0">Contact & Education Information</h6>
                        </div>
                        <div class="card-body border-top">
                            <div class="row g-3">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="phone">Phone Number</label>
                                    <input class="form-control" id="phone" name="phone" type="text" value="{{ old('phone') }}" />
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="emergency_phone">Emergency Phone</label>
                                    <input class="form-control" id="emergency_phone" name="emergency_phone" type="text" value="{{ old('emergency_phone') }}" />
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label" for="address">Address</label>
                                    <input class="form-control" id="address" name="address" type="text" value="{{ old('address') }}" />
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label" for="education_record">Education Record</label>
                                    <textarea class="form-control" id="education_record" name="education_record" rows="3">{{ old('education_record') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-3 text-end">
                <a class="btn btn-falcon-default me-2" href="{{ route('admin.staffs.index') }}">Cancel</a>
                <button class="btn btn-primary" type="submit">Create Staff Member</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('.select2').select2({
            theme: 'bootstrap-5',
            allowClear: true
        });

        // Dynamic Campus Loading
        $('#school_id').on('change', function() {
            const schoolId = $(this).val();
            const campusSelect = $('#campus_id');
            
            campusSelect.html('<option value="">Loading...</option>');
            
            if (!schoolId) {
                campusSelect.html('<option value="">Select Campus</option>');
                return;
            }

            fetch(`/schools/${schoolId}/campuses`)
                .then(response => response.json())
                .then(data => {
                    campusSelect.html('<option value="">Select Campus</option>');
                    data.forEach(campus => {
                        campusSelect.append(`<option value="${campus.id}">${campus.name}</option>`);
                    });
                })
                .catch(error => {
                    console.error('Error fetching campuses:', error);
                    campusSelect.html('<option value="">Error loading campuses</option>');
                });
        });
    });
</script>
@endpush

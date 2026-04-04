@extends('layouts.admin')

@section('content')
<div class="card mb-3">
    <div class="card-header">
        <h5 class="mb-0">Edit Staff Member: {{ $staff->name }}</h5>
    </div>
    <div class="card-body bg-body-tertiary">
        <form action="{{ route('admin.staffs.update', $staff->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="row g-3">
                <div class="col-lg-6">
                    <div class="card shadow-none">
                        <div class="card-header bg-body-secondary py-2">
                            <h6 class="mb-0">Account Information</h6>
                        </div>
                        <div class="card-body border-top">
                            <div class="mb-3 text-center">
                                <div class="avatar avatar-4xl mb-2">
                                    <img class="rounded-circle" src="{{ $staff->profile_photo ? asset('storage/' . $staff->profile_photo) : asset('assets/img/team/avatar.png') }}" alt="" />
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="name">Full Name</label>
                                <input class="form-control @error('name') is-invalid @enderror" id="name" name="name" type="text" value="{{ old('name', $staff->name) }}" required />
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="email">Email Address</label>
                                <input class="form-control @error('email') is-invalid @enderror" id="email" name="email" type="email" value="{{ old('email', $staff->email) }}" required />
                                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            @if($staff->user_id)
                                <div class="mb-3 border-top pt-3">
                                    <label class="form-label text-warning" for="password">New Password (Leave blank to keep current)</label>
                                    <input class="form-control @error('password') is-invalid @enderror" id="password" name="password" type="password" />
                                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            @else
                                <div class="mb-3 border-top pt-3">
                                    <p class="text-700 fs-10 mb-0"><small><span class="fas fa-info-circle me-1"></span>This staff member does not have a user account yet. Use the "Create User Account" button on the list page to generate one.</small></p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card shadow-none">
                        <div class="card-header bg-body-secondary py-2">
                            <h6 class="mb-0">Professional Details</h6>
                        </div>
                        <div class="card-body border-top">
                            @if(auth()->user()->hasRole('super admin'))
                                <div class="mb-3">
                                    <label class="form-label" for="school_id">School</label>
                                    <select class="form-select @error('school_id') is-invalid @enderror" id="school_id" name="school_id" required>
                                        <option value="">Select School</option>
                                        @foreach($schools as $school)
                                            <option value="{{ $school->id }}" {{ old('school_id', $staff->school_id) == $school->id ? 'selected' : '' }}>{{ $school->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('school_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            @endif
                            <div class="mb-3">
                                <label class="form-label" for="designation_id">Designation</label>
                                <select class="form-select select2 @error('designation_id') is-invalid @enderror" id="designation_id" name="designation_id" required>
                                    @foreach($designations as $category => $titles)
                                        <optgroup label="{{ $category }}">
                                            @foreach($titles as $title)
                                                <option value="{{ $title->id }}" {{ old('designation_id', $staff->designation_id) == $title->id ? 'selected' : '' }}>{{ $title->name }}</option>
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
                                        <option value="{{ $campus->id }}" {{ old('campus_id', $staff->campus_id) == $campus->id ? 'selected' : '' }}>{{ $campus->name }}</option>
                                    @endforeach
                                </select>
                                @error('campus_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="profile_photo">Change Profile Photo</label>
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
                                    <input class="form-control mask-phone" id="phone" name="phone" type="text" value="{{ old('phone', $staff->phone) }}" />
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="emergency_phone">Emergency Phone</label>
                                    <input class="form-control mask-phone" id="emergency_phone" name="emergency_phone" type="text" value="{{ old('emergency_phone', $staff->emergency_phone) }}" />
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label" for="address">Address</label>
                                    <input class="form-control" id="address" name="address" type="text" value="{{ old('address', $staff->address) }}" />
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label" for="education_record">Education Record</label>
                                    <textarea class="form-control" id="education_record" name="education_record" rows="3">{{ old('education_record', $staff->education_record) }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-3 text-end">
                <a class="btn btn-falcon-default me-2" href="{{ route('admin.staffs.index') }}">Cancel</a>
                <button class="btn btn-primary" type="submit">Update Staff Member</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('.select2').select2({
            theme: 'bootstrap-5'
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

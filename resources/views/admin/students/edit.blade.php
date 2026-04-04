@extends('layouts.admin')

@section('content')
<div class="card mb-3">
    <div class="card-header">
        <h5 class="mb-0">Edit Student Record: {{ $student->full_name }}</h5>
    </div>
    <div class="card-body bg-body-tertiary">
        <form action="{{ route('admin.students.update', $student->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="row g-3">
                {{-- Student Information --}}
                <div class="col-12">
                    <div class="card shadow-none">
                        <div class="card-header bg-body-secondary py-2">
                            <h6 class="mb-0">1. Student Information</h6>
                        </div>
                        <div class="card-body border-top">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label" for="admission_no">Admission Number <span class="text-danger">*</span></label>
                                    <input class="form-control @error('admission_no') is-invalid @enderror" id="admission_no" name="admission_no" type="text" value="{{ old('admission_no', $student->admission_no) }}" required />
                                    @error('admission_no') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="roll_no">Roll Number</label>
                                    <input class="form-control @error('roll_no') is-invalid @enderror" id="roll_no" name="roll_no" type="text" value="{{ old('roll_no', $student->roll_no) }}" />
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="campus_id">Campus <span class="text-danger">*</span></label>
                                    <select class="form-select @error('campus_id') is-invalid @enderror" id="campus_id" name="campus_id" required>
                                        <option value="">Select Campus</option>
                                        @foreach($campuses as $campus)
                                            <option value="{{ $campus->id }}" {{ old('campus_id', $student->campus_id) == $campus->id ? 'selected' : '' }}>{{ $campus->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('campus_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label" for="first_name">First Name <span class="text-danger">*</span></label>
                                    <input class="form-control @error('first_name') is-invalid @enderror" id="first_name" name="first_name" type="text" value="{{ old('first_name', $student->first_name) }}" required />
                                    @error('first_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="last_name">Last Name</label>
                                    <input class="form-control @error('last_name') is-invalid @enderror" id="last_name" name="last_name" type="text" value="{{ old('last_name', $student->last_name) }}" />
                                </div>
                                
                                <div class="col-md-3">
                                    <label class="form-label" for="dob">Date of Birth <span class="text-danger">*</span></label>
                                    <input class="form-control datetimepicker mask-date @error('dob') is-invalid @enderror" id="dob" name="dob" type="text" placeholder="dd/mm/yyyy" data-options='{"disableMobile":true,"dateFormat":"d/m/Y"}' value="{{ old('dob', $student->dob ? \Carbon\Carbon::parse($student->dob)->format('d/m/Y') : '') }}" required />
                                    @error('dob') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label" for="gender">Gender <span class="text-danger">*</span></label>
                                    <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender" required>
                                        <option value="">Select Gender</option>
                                        <option value="Male" {{ old('gender', $student->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                                        <option value="Female" {{ old('gender', $student->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                                        <option value="Other" {{ old('gender', $student->gender) == 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label" for="blood_group">Blood Group</label>
                                    <select class="form-select @error('blood_group') is-invalid @enderror" id="blood_group" name="blood_group">
                                        <option value="">Select Group</option>
                                        @foreach(['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'] as $bg)
                                            <option value="{{ $bg }}" {{ old('blood_group', $student->blood_group) == $bg ? 'selected' : '' }}>{{ $bg }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label" for="status">Status <span class="text-danger">*</span></label>
                                    <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                        @foreach(['Active', 'Inactive', 'Graduated', 'Dropout'] as $st)
                                            <option value="{{ $st }}" {{ old('status', $student->status) == $st ? 'selected' : '' }}>{{ $st }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 text-center mt-4">
                                    <div class="avatar avatar-4xl border border-dashed rounded-3">
                                        <img src="{{ $student->photo ? asset('storage/' . $student->photo) : asset('assets/img/team/avatar.png') }}" class="rounded-circle" width="100" />
                                    </div>
                                    <div class="mt-2">
                                        <label class="form-label" for="photo">Update Photo</label>
                                        <input class="form-control" id="photo" name="photo" type="file" accept="image/*" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Guardian Information --}}
                <div class="col-12">
                    <div class="card shadow-none">
                        <div class="card-header bg-body-secondary py-2">
                            <h6 class="mb-0">2. Parent / Guardian Information</h6>
                        </div>
                        <div class="card-body border-top">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label" for="father_name">Father's Name</label>
                                    <input class="form-control" id="father_name" name="father_name" type="text" value="{{ old('father_name', $student->guardian->father_name ?? '') }}" />
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="mother_name">Mother's Name</label>
                                    <input class="form-control" id="mother_name" name="mother_name" type="text" value="{{ old('mother_name', $student->guardian->mother_name ?? '') }}" />
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="guardian_relation">Relation with Guardian <span class="text-danger">*</span></label>
                                    <select class="form-select @error('guardian_relation') is-invalid @enderror" id="guardian_relation" name="guardian_relation" required>
                                        <option value="Father" {{ old('guardian_relation', $student->guardian->guardian_relation ?? '') == 'Father' ? 'selected' : '' }}>Father</option>
                                        <option value="Mother" {{ old('guardian_relation', $student->guardian->guardian_relation ?? '') == 'Mother' ? 'selected' : '' }}>Mother</option>
                                        <option value="Brother" {{ old('guardian_relation', $student->guardian->guardian_relation ?? '') == 'Brother' ? 'selected' : '' }}>Brother</option>
                                        <option value="Sister" {{ old('guardian_relation', $student->guardian->guardian_relation ?? '') == 'Sister' ? 'selected' : '' }}>Sister</option>
                                        <option value="Other" {{ old('guardian_relation', $student->guardian->guardian_relation ?? '') == 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label" for="guardian_name">Guardian Name <span class="text-danger">*</span></label>
                                    <input class="form-control @error('guardian_name') is-invalid @enderror" id="guardian_name" name="guardian_name" type="text" value="{{ old('guardian_name', $student->guardian->guardian_name ?? '') }}" required />
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="guardian_phone">Guardian Phone <span class="text-danger">*</span></label>
                                    <input class="form-control mask-phone @error('guardian_phone') is-invalid @enderror" id="guardian_phone" name="guardian_phone" type="text" value="{{ old('guardian_phone', $student->guardian->guardian_phone ?? '') }}" required />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Enrollment Management --}}
                <div class="col-12">
                    <div class="card shadow-none">
                        <div class="card-header bg-body-secondary py-2">
                            <h6 class="mb-0">3. Active Enrollment Management</h6>
                        </div>
                        <div class="card-body border-top">
                            @php $en = $student->enrollments->where('is_active', true)->first(); @endphp
                            @if($en)
                                    <div class="col-md-4">
                                        <label class="form-label" for="grade_level_id">Current Grade Level <span class="text-danger">*</span></label>
                                        <select class="form-select @error('grade_level_id') is-invalid @enderror" id="grade_level_id" name="grade_level_id" required>
                                            <option value="">Select Grade Level</option>
                                            @foreach($gradeLevels as $gradeLevel)
                                                <option value="{{ $gradeLevel->id }}" {{ old('grade_level_id', $en->grade_level_id) == $gradeLevel->id ? 'selected' : '' }}>{{ $gradeLevel->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('grade_level_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label" for="section_id">Section <span class="text-danger">*</span></label>
                                        <select class="form-select @error('section_id') is-invalid @enderror" id="section_id" name="section_id" required>
                                            <option value="">Select Section</option>
                                            @foreach($sections as $section)
                                                <option value="{{ $section->id }}" 
                                                    data-grade="{{ $section->grade_level_id }}" 
                                                    data-campus="{{ $section->campus_id }}"
                                                    {{ old('section_id', $en->section_id) == $section->id ? 'selected' : '' }}>
                                                    {{ $section->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('section_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label" for="session_year">Session Year <span class="text-danger">*</span></label>
                                        <input class="form-control" id="session_year" name="session_year" type="text" value="{{ old('session_year', $en->session_year) }}" required />
                                    </div>
                                    <div class="col-12 mt-2">
                                        <p class="fs-11 text-info"><span class="fas fa-info-circle me-1"></span>Editing these fields will update the current active enrollment record.</p>
                                    </div>
                                </div>
                            @else
                                <p class="text-danger">No active enrollment found for this student. <a href="#!" class="btn btn-link p-0">Enroll Now</a></p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            @push('scripts')
            <script>
                $(document).ready(function() {
                    const gradeSelect = $('#grade_level_id');
                    const campusSelect = $('#campus_id');
                    const sectionSelect = $('#section_id');
                    const allSections = sectionSelect.find('option').clone();

                    function filterSections() {
                        const gradeId = gradeSelect.val();
                        const campusId = campusSelect.val();
                        const currentSectionId = sectionSelect.val();

                        sectionSelect.find('option:not(:first)').remove();
                        
                        allSections.each(function() {
                            const opt = $(this);
                            if (opt.val() === '') return;
                            
                            const matchesGrade = !gradeId || opt.data('grade') == gradeId;
                            const matchesCampus = !campusId || opt.data('campus') == campusId;
                            
                            if (matchesGrade && matchesCampus) {
                                sectionSelect.append(opt);
                            }
                        });

                        // Reselect previously selected value if it still exists
                        sectionSelect.val(currentSectionId);
                    }

                    gradeSelect.on('change', filterSections);
                    campusSelect.on('change', filterSections);
                    
                    // Initial filter
                    if(gradeSelect.val() || campusSelect.val()) {
                        filterSections();
                    }
                });
            </script>
            @endpush

            <div class="mt-4 text-end border-top pt-3">
                <a class="btn btn-falcon-default me-2" href="{{ route('admin.students.show', $student->id) }}">Cancel</a>
                <button class="btn btn-primary px-5" type="submit">Update Student Record</button>
            </div>
        </form>
    </div>
</div>
@endsection

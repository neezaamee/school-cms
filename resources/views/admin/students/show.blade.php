@extends('layouts.admin')

@section('content')
<div class="row g-3">
    <div class="col-12">
        <div class="card mb-3">
            <div class="card-header position-relative min-vh-25 mb-7">
                <div class="bg-holder rounded-3 rounded-bottom-0" style="background-image:url({{ asset('assets/img/generic/4.jpg') }});"></div>
                <!--/.bg-holder-->
                <div class="avatar avatar- profile shadow-sm img-thumbnail rounded-circle" style="width: 151px; height: 151px; left: 24px; bottom: -75px; position: absolute;">
                    <img class="rounded-circle" src="{{ $student->photo ? asset('storage/' . $student->photo) : asset('assets/img/team/avatar.png') }}" width="151" height="151" alt="" />
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-8">
                        <h4 class="mb-1">
                            {{ $student->full_name }}
                            @if($student->first_name_ur) <span class="ms-2 fw-normal text-600" dir="rtl">({{ $student->first_name_ur }})</span> @endif
                            <span data-bs-toggle="tooltip" data-bs-placement="right" title="Verified"><small class="fa fa-check-circle text-primary ps-1" style="font-size: 15px;"></small></span>
                        </h4>
                        <h5 class="fs-10 fw-normal">Student ID: {{ $student->admission_no }} | Roll No: {{ $student->roll_no ?? 'N/A' }} | B-Form: {{ $student->b_form ?? 'N/A' }}</h5>
                        <p class="text-500">{{ $student->school->name }} - {{ $student->campus->name }}</p>
                        <div class="mb-3">
                            <span class="badge rounded-pill badge-subtle-success">{{ $student->status }}</span>
                            <span class="badge rounded-pill badge-subtle-primary">{{ $student->gender }}</span>
                            @if($student->blood_group)<span class="badge rounded-pill badge-subtle-danger">{{ $student->blood_group }}</span>@endif
                            @if($student->is_hafiz_e_quran)<span class="badge rounded-pill badge-subtle-info">Hafiz-e-Quran</span>@endif
                            @if($student->is_pwd)<span class="badge rounded-pill badge-subtle-warning">PWD</span>@endif
                        </div>
                    </div>
                    <div class="col-lg-4 text-end">
                        <a class="btn btn-falcon-default btn-sm px-3" href="{{ route('admin.students.edit', $student->id) }}">
                            <span class="fas fa-edit me-1"></span>Edit Profile
                        </a>
                        <button class="btn btn-falcon-primary btn-sm px-3 ms-2">
                            <span class="fas fa-print me-1"></span>ID Card
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-8">
        {{-- Academic & Personal --}}
        <div class="card mb-3">
            <div class="card-header bg-body-tertiary">
                <h6 class="mb-0">Academic & Personal Details</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="text-500 fs-11 fw-bold text-uppercase">Current Enrollment</label>
                        @php $en = $student->enrollments->where('is_active', true)->first(); @endphp
                        @if($en)
                            <p class="fw-bold mb-0">
                                {{ $en->gradeLevel->name ?? 'N/A' }} 
                                <span class="badge badge-subtle-primary ms-1">{{ $en->section->name ?? 'N/A' }}</span>
                            </p>
                            <p class="text-500 fs-11">Session: {{ $en->session_year }}</p>
                        @else
                            <p class="text-danger fw-bold mb-0">Not Enrolled</p>
                        @endif
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-500 fs-11 fw-bold text-uppercase">Date of Birth</label>
                        <p class="fw-bold mb-0">{{ \Carbon\Carbon::parse($student->dob)->format('d M, Y') }}</p>
                        <p class="text-500 fs-11">Age: {{ \Carbon\Carbon::parse($student->dob)->age }} Years</p>
                    </div>
                    
                    @if($student->academicSystem)
                    <div class="col-md-6 mb-3">
                        <label class="text-500 fs-11 fw-bold text-uppercase">Academic System</label>
                        <p class="mb-0"><strong>Board:</strong> {{ $student->academicSystem->board_type ?? 'N/A' }}</p>
                        <p class="mb-0"><strong>Medium:</strong> {{ $student->academicSystem->medium ?? 'N/A' }}</p>
                        <p class="mb-0"><strong>Group:</strong> {{ $student->academicSystem->academic_group ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-500 fs-11 fw-bold text-uppercase">Previous Education</label>
                        <p class="mb-0"><strong>School:</strong> {{ $student->academicSystem->previous_school ?? 'N/A' }}</p>
                        <p class="mb-0"><strong>Class:</strong> {{ $student->academicSystem->previous_class ?? 'N/A' }}</p>
                        <p class="mb-0"><strong>Shift:</strong> {{ $student->academicSystem->shift ?? 'N/A' }}</p>
                    </div>
                    @endif

                    <div class="col-12 mb-3">
                        <label class="text-500 fs-11 fw-bold text-uppercase">Selected Subjects</label>
                        <div class="mt-1">
                            @if($student->academicSystem && $student->academicSystem->subjects_selected)
                                @php 
                                    $subjectIds = $student->academicSystem->subjects_selected;
                                    $subjects = \App\Models\Subject::whereIn('id', $subjectIds)->pluck('name');
                                @endphp
                                @foreach($subjects as $s)
                                    <span class="badge border text-primary bg-light me-1 mb-1">{{ $s }}</span>
                                @endforeach
                            @else
                                <span class="text-500">No subjects selected.</span>
                            @endif
                        </div>
                    </div>
                    
                    @if($student->medical_notes)
                    <div class="col-12 mb-3">
                        <label class="text-500 fs-11 fw-bold text-uppercase text-danger">Medical Notes</label>
                        <p class="mb-0 text-danger fw-bold">{{ $student->medical_notes }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Parent Details --}}
        <div class="card mb-3">
            <div class="card-header bg-body-tertiary">
                <h6 class="mb-0">Parent Information</h6>
            </div>
            <div class="card-body">
                @if($student->parentDetails)
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <div class="d-flex align-items-center mb-2">
                            <div class="avatar avatar-xl me-2">
                                <div class="avatar-name rounded-circle bg-primary-subtle text-primary"><span>F</span></div>
                            </div>
                            <div>
                                <h6 class="mb-0">Father: {{ $student->parentDetails->father_name }}</h6>
                                <small class="text-500">{{ $student->parentDetails->father_profession }}</small>
                            </div>
                        </div>
                        <p class="mb-1 fs-11"><strong>CNIC:</strong> {{ $student->parentDetails->father_cnic }}</p>
                        <p class="mb-1 fs-11"><strong>Mobile:</strong> {{ $student->parentDetails->father_mobile }}</p>
                        <p class="mb-1 fs-11"><strong>Email:</strong> {{ $student->parentDetails->father_email ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="d-flex align-items-center mb-2">
                            <div class="avatar avatar-xl me-2">
                                <div class="avatar-name rounded-circle bg-danger-subtle text-danger"><span>M</span></div>
                            </div>
                            <div>
                                <h6 class="mb-0">Mother: {{ $student->parentDetails->mother_name ?? 'N/A' }}</h6>
                                <small class="text-500">{{ $student->parentDetails->mother_profession ?? 'N/A' }}</small>
                            </div>
                        </div>
                        <p class="mb-1 fs-11"><strong>CNIC:</strong> {{ $student->parentDetails->mother_cnic ?? 'N/A' }}</p>
                        <p class="mb-1 fs-11"><strong>Income:</strong> {{ $student->parentDetails->monthly_income ?? 'N/A' }}</p>
                    </div>
                    <div class="col-12">
                        <label class="text-500 fs-11 fw-bold text-uppercase">Home Address</label>
                        <p class="mb-0">{{ $student->parentDetails->home_address }}</p>
                    </div>
                </div>
                @else
                <p class="text-center py-3">No parent details found.</p>
                @endif
            </div>
        </div>

        {{-- Guardian Details --}}
        @if($student->guardianDetails)
        <div class="card mb-3">
            <div class="card-header bg-body-tertiary">
                <h6 class="mb-0">Guardian Information (Alternative)</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="mb-1">{{ $student->guardianDetails->name }}</h6>
                        <p class="text-500 fs-11 mb-2">Relation: {{ $student->guardianDetails->relation }}</p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <p class="mb-0 fs-11"><strong>CNIC:</strong> {{ $student->guardianDetails->cnic }}</p>
                        <p class="mb-0 fs-11"><strong>Mobile:</strong> {{ $student->guardianDetails->mobile }}</p>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <div class="col-lg-4">
        {{-- Fee Config --}}
        <div class="card mb-3 bg-primary text-white border-0">
            <div class="card-body">
                <h5 class="text-white mb-2">Fee Configuration</h5>
                <hr class="opacity-25" />
                @if($student->feeConfig)
                    <div class="mb-2 d-flex justify-content-between">
                        <small class="opacity-75">Transport Fee:</small>
                        <span class="fw-bold">Rs. {{ number_format($student->feeConfig->transport_fee, 2) }}</span>
                    </div>
                    <div class="mb-2 d-flex justify-content-between">
                        <small class="opacity-75">Hostel Fee:</small>
                        <span class="fw-bold">Rs. {{ number_format($student->feeConfig->hostel_fee, 2) }}</span>
                    </div>
                    <div class="mb-2 d-flex justify-content-between">
                        <small class="opacity-75">Discount:</small>
                        <span class="fw-bold">{{ $student->feeConfig->discount_percentage }}%</span>
                    </div>
                @else
                    <p class="fs-11 text-white-50">Fee details not configured.</p>
                @endif
                <div class="display-6 fw-normal text-white mt-3">Rs. 0.00</div>
                <small class="opacity-75">Est. Monthly Total (Dues Pending)</small>
                <a href="#!" class="btn btn-light btn-sm w-100 mt-3">Manage Ledger</a>
            </div>
        </div>

        {{-- Documents / Attachments --}}
        <div class="card mb-3">
            <div class="card-header bg-body-tertiary">
                <h6 class="mb-0">Documents & Attachments</h6>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush pt-0">
                    @forelse($student->attachments as $atch)
                    <li class="list-group-item d-flex justify-content-between align-items-center fs-11">
                        <div>
                            <span class="fas fa-file-pdf me-2 text-primary"></span>
                            <span class="fw-bold text-uppercase">{{ $atch->attachment_type }}</span>
                        </div>
                        <a href="{{ asset('storage/' . $atch->file_path) }}" target="_blank" class="btn btn-link btn-sm p-0">Download</a>
                    </li>
                    @empty
                    <li class="list-group-item text-center py-3 text-500">No attachments found.</li>
                    @endforelse
                </ul>
            </div>
        </div>

        {{-- Monthly Attendance Summary --}}
        <div class="card">
            <div class="card-header bg-body-tertiary">
                <h6 class="mb-0">Month Summary</h6>
            </div>
            <div class="card-body">
                @php 
                    $monthAtt = $student->attendance()->whereMonth('attendance_date', date('m'))->get();
                    $present = $monthAtt->where('status', 'Present')->count();
                    $absent = $monthAtt->where('status', 'Absent')->count();
                    $late = $monthAtt->where('status', 'Late')->count();
                @endphp
                <div class="row text-center g-2">
                    <div class="col-4">
                        <div class="bg-success-subtle p-2 rounded">
                            <h4 class="text-success mb-0">{{ $present }}</h4>
                            <small class="fs-11 text-success">Present</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="bg-danger-subtle p-2 rounded">
                            <h4 class="text-danger mb-0">{{ $absent }}</h4>
                            <small class="fs-11 text-danger">Absent</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="bg-warning-subtle p-2 rounded">
                            <h4 class="text-warning mb-0">{{ $late }}</h4>
                            <small class="fs-11 text-warning">Late</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

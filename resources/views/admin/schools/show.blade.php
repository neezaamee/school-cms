@extends('layouts.admin')

@section('title', 'School Details - ' . $school->name)

@section('content')
<div class="card mb-3">
    <div class="card-header">
        <div class="row flex-between-center">
            <div class="col-auto">
                <h5 class="mb-0">School Details: {{ $school->name }}</h5>
            </div>
            <div class="col-auto">
                <a class="btn btn-falcon-default btn-sm" href="{{ route('schools.index') }}"><span class="fas fa-arrow-left me-1"></span>Back</a>
                <a class="btn btn-falcon-success btn-sm ms-2" href="{{ route('school.landing', $school->slug) }}" target="_blank"><span class="fas fa-external-link-alt me-1"></span>Public Page</a>
                <a class="btn btn-falcon-primary btn-sm ms-2" href="{{ route('schools.edit', $school->id) }}"><span class="fas fa-edit me-1"></span>Edit Details</a>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-8">
        <div class="card mb-3">
            <div class="card-header bg-body-tertiary">
                <h6 class="mb-0">Profile & Configuration</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="text-700 fs-11 text-uppercase fw-bold">School Name</label>
                        <p class="mb-0 fw-semi-bold">{{ $school->name }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-700 fs-11 text-uppercase fw-bold">Subscription Package</label>
                        <p class="mb-0">
                            <span class="badge badge-subtle-primary fs-11">{{ $school->subscriptionPackage->name ?? 'N/A' }}</span>
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-700 fs-11 text-uppercase fw-bold">Official Email</label>
                        <p class="mb-0">{{ $school->email }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-700 fs-11 text-uppercase fw-bold">Phone Number</label>
                        <p class="mb-0">{{ $school->phone }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-700 fs-11 text-uppercase fw-bold">Status</label>
                        <p class="mb-0 text-capitalize"><span class="badge rounded-pill badge-subtle-success">{{ $school->status }}</span></p>
                    </div>
                    <div class="col-md-12 mb-3 mt-2">
                        <label class="text-700 fs-11 text-uppercase fw-bold d-block mb-2">Branding Assets</label>
                        <div class="d-flex align-items-center">
                            @if($school->logo)
                                <div class="me-4 text-center">
                                    <img src="{{ asset($school->logo) }}" alt="Logo" class="img-thumbnail" style="height: 60px;">
                                    <div class="fs-11 text-500 mt-1">Logo</div>
                                </div>
                            @endif
                            @if($school->favicon)
                                <div class="text-center">
                                    <img src="{{ asset($school->favicon) }}" alt="Favicon" class="img-thumbnail" style="height: 40px;">
                                    <div class="fs-11 text-500 mt-1">Favicon</div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header bg-body-tertiary">
                <h6 class="mb-0">Main Campus Location</h6>
            </div>
            <div class="card-body">
                @if($school->mainCampus)
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="text-700 fs-11 text-uppercase fw-bold">Campus Name</label>
                        <p class="mb-0 fw-semi-bold">{{ $school->mainCampus->name }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-700 fs-11 text-uppercase fw-bold">Country</label>
                        <p class="mb-0">{{ $school->mainCampus->country->name ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-700 fs-11 text-uppercase fw-bold">City</label>
                        <p class="mb-0">{{ $school->mainCampus->city->name ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-12">
                        <label class="text-700 fs-11 text-uppercase fw-bold">Address</label>
                        <p class="mb-0">{{ $school->mainCampus->address }}</p>
                    </div>
                </div>
                @else
                <p class="text-500 mb-0">No main campus information found.</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card mb-3">
            <div class="card-header bg-body-tertiary">
                <h6 class="mb-0">Owner Profile</h6>
            </div>
            <div class="card-body">
                @php $owner = $school->users->first(); @endphp
                @if($owner)
                <div class="d-flex align-items-center mb-3">
                    <div class="avatar avatar-3xl">
                        <div class="avatar-name rounded-circle"><span>{{ substr($owner->name, 0, 1) }}</span></div>
                    </div>
                    <div class="ms-3">
                        <h6 class="mb-0">{{ $owner->name }}</h6>
                        <p class="text-500 mb-0 fs-11">School Owner</p>
                    </div>
                </div>
                <label class="text-700 fs-11 text-uppercase fw-bold">Login Email</label>
                <p class="mb-3">{{ $owner->email }}</p>
                @else
                <p class="text-500 mb-0">No owner assigned.</p>
                @endif
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header bg-body-tertiary">
                <h6 class="mb-0">Subscription Limits</h6>
            </div>
            <div class="card-body">
                @if($school->subscriptionPackage)
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <label class="fs-11 text-700 mb-0">Students</label>
                        <span class="fs-11 text-700">Limit: {{ $school->subscriptionPackage->student_limit }}</span>
                    </div>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <label class="fs-11 text-700 mb-0">Staff</label>
                        <span class="fs-11 text-700">Limit: {{ $school->subscriptionPackage->staff_limit }}</span>
                    </div>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

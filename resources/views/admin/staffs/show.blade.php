@extends('layouts.admin')

@section('content')
<div class="row g-3">
  <div class="col-lg-8">
    <div class="card mb-3">
      <div class="card-header bg-body-tertiary">
        <h5 class="mb-0">Education Record</h5>
      </div>
      <div class="card-body">
        <p class="mb-0">{{ $staff->education_record ?? 'No education record provided.' }}</p>
      </div>
    </div>
    <div class="card mb-3">
      <div class="card-header bg-body-tertiary">
        <h5 class="mb-0">Contact Details</h5>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-6 mb-3">
            <h6 class="fw-semi-bold">Phone</h6>
            <p class="text-700 mb-0">{{ $staff->phone ?? 'N/A' }}</p>
          </div>
          <div class="col-md-6 mb-3">
            <h6 class="fw-semi-bold">Emergency Phone</h6>
            <p class="text-700 mb-0">{{ $staff->emergency_phone ?? 'N/A' }}</p>
          </div>
          <div class="col-12">
            <h6 class="fw-semi-bold">Address</h6>
            <p class="text-700 mb-0">{{ $staff->address ?? 'N/A' }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-4">
    <div class="card mb-3">
      <div class="card-body text-center">
        <div class="avatar avatar-5xl mb-3">
          <img class="rounded-circle" src="{{ $staff->profile_photo ? asset('storage/' . $staff->profile_photo) : asset('assets/img/team/avatar.png') }}" alt="" />
        </div>
        <h4 class="mb-1">{{ $staff->name }}</h4>
        <h5 class="fs-10 fw-normal text-600 mb-3">{{ $staff->designation->name }}</h5>
        <div class="dropdown font-sans-serif d-inline-block">
          <a class="btn btn-falcon-default btn-sm" href="{{ route('admin.staffs.edit', $staff->id) }}">
            <span class="fas fa-edit me-1" data-fa-transform="shrink-3"></span>Edit Profile
          </a>
          @if(!$staff->user_id && auth()->user()->hasAnyRole(['super admin', 'school owner', 'principal', 'school administrator', 'school manager']))
            <form action="{{ route('admin.staffs.create-user', $staff->id) }}" method="POST" class="d-inline-block ms-1">
              @csrf
              <button class="btn btn-falcon-primary btn-sm" type="submit">
                <span class="fas fa-user-plus me-1" data-fa-transform="shrink-3"></span>Create Account
              </button>
            </form>
          @endif
        </div>
      </div>
    </div>
    <div class="card mb-3">
      <div class="card-header bg-body-tertiary">
        <h5 class="mb-0">Quick Info</h5>
      </div>
      <div class="card-body">
        <div class="d-flex align-items-center mb-2">
          <span class="fas fa-envelope text-600 me-2"></span>
          <div class="flex-1"><a href="mailto:{{ $staff->email }}">{{ $staff->email }}</a></div>
        </div>
        <div class="d-flex align-items-center mb-2">
          <span class="fas fa-university text-600 me-2"></span>
          <div class="flex-1 text-700">{{ $staff->campus ? $staff->campus->name : 'N/A' }}</div>
        </div>
        <div class="d-flex align-items-center">
          <span class="fas fa-layer-group text-600 me-2"></span>
          <div class="flex-1 text-700">{{ $staff->designation->category }}</div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

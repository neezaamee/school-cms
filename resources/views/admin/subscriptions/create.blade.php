@extends('layouts.admin')

@section('title', 'New Subscription Package')

@section('content')
<div class="card mb-3">
    <div class="card-header">
        <div class="row flex-between-center">
            <div class="col-auto">
                <h5 class="mb-0">New Subscription Package</h5>
            </div>
            <div class="col-auto">
                <a class="btn btn-falcon-default btn-sm" href="{{ route('subscription-packages.index') }}"><span class="fas fa-arrow-left me-1"></span>Back</a>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body bg-body-tertiary">
        <form action="{{ route('subscription-packages.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label" for="name">Package Name</label>
                    <input class="form-control @error('name') is-invalid @enderror" id="name" name="name" type="text" value="{{ old('name') }}" required placeholder="e.g. Bronze, Silver" />
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="price">Monthly Price ($)</label>
                    <input class="form-control @error('price') is-invalid @enderror" id="price" name="price" type="number" step="0.01" value="{{ old('price') }}" required />
                    @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label" for="student_limit">Student Limit</label>
                    <input class="form-control @error('student_limit') is-invalid @enderror" id="student_limit" name="student_limit" type="number" value="{{ old('student_limit') }}" required />
                    @error('student_limit') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label" for="staff_limit">Staff Limit</label>
                    <input class="form-control @error('staff_limit') is-invalid @enderror" id="staff_limit" name="staff_limit" type="number" value="{{ old('staff_limit') }}" required />
                    @error('staff_limit') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label" for="user_limit">User Limit (Admin)</label>
                    <input class="form-control @error('user_limit') is-invalid @enderror" id="user_limit" name="user_limit" type="number" value="{{ old('user_limit') }}" required />
                    @error('user_limit') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label" for="entry_limit">Entry Limit</label>
                    <input class="form-control @error('entry_limit') is-invalid @enderror" id="entry_limit" name="entry_limit" type="number" value="{{ old('entry_limit') }}" required />
                    @error('entry_limit') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <div class="form-check form-switch mt-2">
                        <input class="form-check-input" id="has_tech_support" name="has_tech_support" type="checkbox" {{ old('has_tech_support') ? 'checked' : '' }} />
                        <label class="form-check-label" for="has_tech_support">Enable Technical Support Included</label>
                    </div>
                </div>
                <div class="col-12 text-end mt-4">
                    <button class="btn btn-primary" type="submit">Create Package</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@extends('layouts.admin')

@section('title', 'Student Dashboard')

@section('content')
<div class="card mb-3">
    <div class="card-body">
        <div class="row flex-between-center">
            <div class="col-md">
                <h5 class="mb-2 mb-md-0">Student Dashboard</h5>
                <p class="mb-0 text-600">Welcome, <strong>{{ auth()->user()->name }}</strong>!</p>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-6 col-xxl-3">
        <div class="card h-md-100">
            <div class="card-header pb-0">
                <h6 class="mb-0 mt-2">My Subjects</h6>
            </div>
            <div class="card-body d-flex flex-column justify-content-end">
                <div class="row">
                    <div class="col">
                        <p class="font-sans-serif lh-1 mb-1 fs-5">0</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

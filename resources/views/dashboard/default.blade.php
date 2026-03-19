@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="card mb-3">
    <div class="card-body">
        <div class="row flex-between-center">
            <div class="col-md">
                <h5 class="mb-2 mb-md-0">Dashboard</h5>
                <p class="mb-0 text-600">Welcome to ElafTech School Services, <strong>{{ auth()->user()->name }}</strong>!</p>
            </div>
        </div>
    </div>
</div>

@if(auth()->user()->roles->isEmpty())
<div class="alert alert-info border-2 d-flex align-items-center" role="alert">
    <div class="bg-info me-3 icon-item"><span class="fas fa-info-circle text-white fs-8"></span></div>
    <p class="mb-0 flex-1">Your account is active, but you don't have a specific role assigned yet. Please contact your administrator.</p>
</div>
@endif
@endsection

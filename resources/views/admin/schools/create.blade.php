@extends('layouts.admin')

@section('title', 'Register New School')

@push('css')
<style>
    .subscription-card { cursor: pointer; transition: all 0.2s; }
    .subscription-card:hover { border-color: var(--falcon-primary) !important; transform: translateY(-2px); }
</style>
@endpush

@section('content')
<div class="card mb-3">
    <div class="card-header bg-body-tertiary">
        <h5 class="mb-0 text-primary"><span class="fas fa-university me-2"></span>Register New School / ElafTech Service</h5>
    </div>
    <div class="card-body bg-body-tertiary">
        @livewire('admin.school-registration')
    </div>
</div>
@endsection

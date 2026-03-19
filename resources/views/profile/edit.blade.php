@extends('layouts.admin')

@section('title', 'Profile')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="mb-0">Profile Management</h5>
            </div>
            <div class="card-body bg-body-tertiary">
                <div class="row g-4">
                    <div class="col-lg-6">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                    <div class="col-lg-6">
                        @include('profile.partials.update-password-form')
                    </div>
                    <div class="col-12 mt-4 text-center">
                        <hr class="my-4">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

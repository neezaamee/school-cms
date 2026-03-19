@extends('layouts.school_landing')

@section('content')
<!-- Hero Section -->
<section class="hero-section text-center">
    <div class="container">
        @if($school->logo)
            <img src="{{ asset($school->logo) }}" alt="{{ $school->name }}" class="school-logo-lg img-fluid">
        @endif
        <h1 class="display-3 fw-bold text-white mb-3">Welcome to {{ $school->name }}</h1>
        <p class="lead text-300 mb-5">Excellence in Education, Managed with Innovation.</p>
        <div class="d-flex justify-content-center">
            <a class="btn btn-primary btn-lg px-5 me-3" href="#about">Explore More</a>
            <a class="btn btn-outline-light btn-lg px-5" href="#contact">Contact Us</a>
        </div>
    </div>
</section>

<!-- Stats / Info Section -->
<section class="py-5 bg-white" id="about">
    <div class="container h-100">
        <div class="row align-items-center">
            <div class="col-lg-6 pe-lg-5">
                <h2 class="fw-bold mb-4">About Our Institution</h2>
                <p class="text-700 mb-4">Located in the heart of {{ $school->mainCampus->city->name ?? 'our city' }}, {{ $school->name }} has been a beacon of knowledge. We pride ourselves on providing a standard-setting curriculum supported by state-of-the-art management systems provided by ElafTech.</p>
                <div class="card bg-subtle-primary border-0 rounded-3">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <span class="fas fa-university fa-2x text-primary me-3"></span>
                            <h5 class="mb-0">Main Campus</h5>
                        </div>
                        <p class="text-800 fs-10 mb-0">{{ $school->mainCampus->address }}</p>
                        <p class="text-800 fs-10">{{ $school->mainCampus->city->name ?? '' }}, {{ $school->mainCampus->country->name ?? '' }}</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mt-5 mt-lg-0">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="bg-light p-4 rounded-3 text-center border">
                            <h3 class="fw-bold text-primary mb-1">Standard</h3>
                            <p class="text-600 fs-11 mb-0 text-uppercase">Curriculum</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="bg-light p-4 rounded-3 text-center border">
                            <h3 class="fw-bold text-primary mb-1">Modern</h3>
                            <p class="text-600 fs-11 mb-0 text-uppercase">Facilities</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="bg-light p-4 rounded-3 text-center border">
                            <h3 class="fw-bold text-primary mb-1">Secure</h3>
                            <p class="text-600 fs-11 mb-0 text-uppercase">Environment</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="bg-light p-4 rounded-3 text-center border">
                            <h3 class="fw-bold text-primary mb-1">Elite</h3>
                            <p class="text-600 fs-11 mb-0 text-uppercase">Management</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section class="py-5 bg-light" id="contact">
    <div class="container text-center">
        <h2 class="fw-bold mb-5">Get in Touch</h2>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-5">
                        <div class="row g-4 text-start">
                            <div class="col-md-4">
                                <div class="mb-3"><span class="fas fa-phone-alt text-primary fs-2"></span></div>
                                <h6 class="fw-bold mb-1">Phone Number</h6>
                                <p class="text-700 fs-11">{{ $school->phone }}</p>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3"><span class="fas fa-envelope text-primary fs-2"></span></div>
                                <h6 class="fw-bold mb-1">Email Address</h6>
                                <p class="text-700 fs-11">{{ $school->email }}</p>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3"><span class="fas fa-map-marked-alt text-primary fs-2"></span></div>
                                <h6 class="fw-bold mb-1">Location</h6>
                                <p class="text-700 fs-11">{{ $school->mainCampus->city->name ?? 'Multiple Cities' }}</p>
                            </div>
                        </div>
                        <div class="mt-5">
                            <a href="mailto:{{ $school->email }}" class="btn btn-primary btn-lg px-5">Send us a Message</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@extends('layouts.admin')

@section('title', "What's New")

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card mb-3">
            <div class="card-body">
                <div class="row justify-content-between align-items-center">
                    <div class="col-md">
                        <h5 class="mb-2 mb-md-0">ElafTech School Services Updates</h5>
                    </div>
                    <div class="col-auto">
                        <h6 class="text-primary mb-0">Current Version: v{{ config('app.version', '1.1.0') }}</h6>
                    </div>
                </div>
            </div>
        </div>

        @forelse($changelogs as $log)
        <div class="card mb-3 overflow-hidden">
            <div class="card-header bg-body-tertiary">
                <div class="row flex-between-center g-2 text-start">
                    <div class="col-auto">
                        <span class="badge rounded-pill 
                            {{ [
                                'feature' => 'badge-subtle-success',
                                'improvement' => 'badge-subtle-info',
                                'bugfix' => 'badge-subtle-warning',
                                'security' => 'badge-subtle-danger'
                            ][$log->type] ?? 'badge-subtle-secondary' }} 
                            text-uppercase px-3">
                            {{ $log->type }}
                        </span>
                        <span class="ms-2 text-primary fw-bold">v{{ $log->version ?: 'Update' }}</span>
                    </div>
                    <div class="col-auto text-600 fs-11">
                        {{ $log->release_date ? $log->release_date->format('M d, Y') : $log->created_at->format('M d, Y') }}
                    </div>
                </div>
            </div>
            <div class="card-body">
                <h5 class="mb-3 text-start">{{ $log->title }}</h5>
                <div class="text-700 text-start">
                    {!! nl2br(e($log->description)) !!}
                </div>
            </div>
        </div>
        @empty
        <div class="card">
            <div class="card-body py-5 text-center">
                <span class="fas fa-info-circle fs-6 text-primary mb-3"></span>
                <h5>No updates recorded yet.</h5>
                <p class="mb-0">Keep an eye on this space for new features and improvements!</p>
            </div>
        </div>
        @endforelse
    </div>
</div>
@endsection

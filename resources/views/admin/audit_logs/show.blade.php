@extends('layouts.admin')

@section('title', 'Log Detail')

@section('content')
<div class="card mb-3">
    <div class="card-header bg-body-tertiary">
        <div class="row flex-between-center">
            <div class="col-auto">
                <h5 class="mb-0">Audit Log Details #{{ $auditLog->id }}</h5>
            </div>
            <div class="col-auto">
                <a class="btn btn-falcon-default btn-sm" href="{{ route('audit-logs.index') }}">
                    <span class="fas fa-arrow-left me-1"></span>Back to Logs
                </a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-4">
                <div class="bg-light p-3 rounded-3 border">
                    <h6 class="text-uppercase text-700 fs-11 fw-bold mb-2">Event Information</h6>
                    <p class="mb-1 d-flex justify-content-between">
                        <span class="text-600">Action:</span>
                        <span class="badge badge-subtle-primary">{{ $auditLog->action }}</span>
                    </p>
                    <p class="mb-1 d-flex justify-content-between">
                        <span class="text-600">Module:</span>
                        <span class="fw-semi-bold">{{ $auditLog->model ?: 'System' }}</span>
                    </p>
                    <p class="mb-1 d-flex justify-content-between">
                        <span class="text-600">Target ID:</span>
                        <span class="fw-semi-bold">{{ $auditLog->model_id ?: 'N/A' }}</span>
                    </p>
                    <p class="mb-0 d-flex justify-content-between">
                        <span class="text-600">Timestamp:</span>
                        <span class="text-800">{{ $auditLog->created_at->format('M d, Y H:i:s') }}</span>
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="bg-light p-3 rounded-3 border h-100">
                    <h6 class="text-uppercase text-700 fs-11 fw-bold mb-2">Actor Identity</h6>
                    <div class="d-flex align-items-center mt-3">
                        <div class="avatar avatar-xl me-2">
                            <div class="avatar-name rounded-circle"><span>{{ substr($auditLog->user->name ?? 'S', 0, 1) }}</span></div>
                        </div>
                        <div class="flex-1">
                            <h6 class="mb-1">{{ $auditLog->user->name ?? 'System Process' }}</h6>
                            <p class="text-600 fs-11 mb-0">{{ $auditLog->user->email ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="mt-3">
                        <p class="mb-0 fs-11 text-600">IP: <code>{{ $auditLog->ip_address }}</code></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="bg-light p-3 rounded-3 border h-100">
                    <h6 class="text-uppercase text-700 fs-11 fw-bold mb-2">Context Information</h6>
                    <p class="fs-11 text-700 overflow-hidden text-break">{{ $auditLog->user_agent }}</p>
                </div>
            </div>
            <div class="col-md-12 mt-4">
                <div class="card border border-200 shadow-none">
                    <div class="card-header bg-body-tertiary">
                        <h6 class="mb-0">Payload Details (JSON)</h6>
                    </div>
                    <div class="card-body bg-dark">
                        <pre class="text-success mb-0"><code>{{ json_encode($auditLog->details, JSON_PRETTY_PRINT) }}</code></pre>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.admin')

@section('title', 'Administrative Dashboard')

@section('content')
<livewire:admin.dashboard.onboarding-wizard />

<div class="row g-3 mb-3">
    <div class="col-md-6 col-xxl-3">
        <div class="card h-md-100">
            <div class="card-header pb-0">
                <h6 class="mb-0 mt-2 text-primary fas fa-users me-2">Active Students</h6>
            </div>
            <div class="card-body d-flex flex-column justify-content-end">
                <div class="row align-items-center">
                    <div class="col">
                        <p class="font-sans-serif lh-1 mb-1 fs-5 fw-bold">{{ $stats['active_students'] }}</p>
                        <p class="fs-11 text-600 mb-0">Total: {{ $stats['total_students'] }}</p>
                    </div>
                    <div class="col-auto">
                        <div class="badge badge-subtle-success rounded-pill">+{{ round(($stats['active_students'] / max(1, $stats['total_students'])) * 100) }}%</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xxl-3">
        <div class="card h-md-100">
            <div class="card-header pb-0">
                <h6 class="mb-0 mt-2 text-info fas fa-user-tie me-2">Staff Members</h6>
            </div>
            <div class="card-body d-flex flex-column justify-content-end">
                <div class="row">
                    <div class="col">
                        <p class="font-sans-serif lh-1 mb-1 fs-5 fw-bold">{{ $stats['total_staff'] }}</p>
                        <p class="fs-11 text-600 mb-0">Across all campuses</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xxl-3">
        <div class="card h-md-100">
            <div class="card-header pb-0">
                <h6 class="mb-0 mt-2 text-warning fas fa-calendar-check me-2">Attendance Today</h6>
            </div>
            <div class="card-body d-flex flex-column justify-content-end">
                <div class="row">
                    <div class="col">
                        <p class="font-sans-serif lh-1 mb-1 fs-5 fw-bold">{{ $stats['attendance_percentage'] }}%</p>
                        <div class="progress mt-2" style="height: 5px;">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $stats['attendance_percentage'] }}%" aria-valuenow="{{ $stats['attendance_percentage'] }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xxl-3">
        <div class="card h-md-100">
            <div class="card-header pb-0">
                <h6 class="mb-0 mt-2 text-success fas fa-money-bill-wave me-2">Fee Collection</h6>
            </div>
            <div class="card-body d-flex flex-column justify-content-end">
                <div class="row">
                    <div class="col">
                        <p class="font-sans-serif lh-1 mb-1 fs-5 fw-bold">Rs. {{ number_format($stats['total_collected'], 0) }}</p>
                        <p class="fs-11 text-600 mb-0">Goal: Rs. {{ number_format($stats['total_invoiced'], 0) }} ({{ $stats['collection_percentage'] }}%)</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-xxl-8 col-lg-7">
        <div class="card h-100">
            <div class="card-header bg-body-tertiary">
                <div class="row align-items-center">
                    <div class="col">
                        <h6 class="mb-0">Recent Fee Collections</h6>
                    </div>
                    <div class="col-auto">
                        <a class="btn btn-falcon-default btn-sm" href="{{ route('admin.payments.index') }}">View All</a>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive scrollbar">
                    <table class="table table-sm table-striped fs-11 mb-0">
                        <thead class="bg-200">
                            <tr>
                                <th class="ps-3 py-2">Student</th>
                                <th>Amount</th>
                                <th>Recorded By</th>
                                <th class="text-end pe-3">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($stats['recent_payments'] as $pay)
                            <tr>
                                <td class="ps-3 py-2">
                                    <div class="fw-bold">{{ $pay->invoice->student->full_name }}</div>
                                    <div class="fs-10 text-500">PSID: {{ $pay->invoice->psid }}</div>
                                </td>
                                <td class="text-success fw-bold">Rs. {{ number_format($pay->amount, 2) }}</td>
                                <td>{{ $pay->collector->name ?? 'System' }}</td>
                                <td class="text-end pe-3 text-500">{{ $pay->created_at->diffForHumans() }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-500">No recent payments recorded.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xxl-4 col-lg-5">
        <div class="card h-100">
            <div class="card-header bg-body-tertiary">
                <h6 class="mb-0">Quick Management</h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-6 position-relative">
                        @if(!$readiness['is_ready'])
                        <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center rounded-3 bg-dark bg-opacity-10" style="z-index: 2; pointer-events: none;">
                            <span class="badge badge-subtle-warning shadow-sm"><span class="fas fa-lock me-1"></span>Setup Required</span>
                        </div>
                        @endif
                        <a class="btn btn-app btn-falcon-primary w-100 {{ !$readiness['is_ready'] ? 'bg-200 border-200 text-400' : '' }}" href="{{ route('admin.students.create') }}">
                            <span class="fas fa-user-plus d-block fs-3 mb-2"></span>Admission
                        </a>
                    </div>
                    <div class="col-6">
                        <a class="btn btn-app btn-falcon-info w-100" href="{{ route('admin.attendance.index') }}">
                            <span class="fas fa-calendar-alt d-block fs-3 mb-2"></span>Attendance
                        </a>
                    </div>
                    <div class="col-6">
                        <a class="btn btn-app btn-falcon-success w-100" href="{{ route('admin.payments.index') }}">
                            <span class="fas fa-cash-register d-block fs-3 mb-2"></span>Collect Fee
                        </a>
                    </div>
                    <div class="col-6">
                        <a class="btn btn-app btn-falcon-warning w-100" href="{{ route('admin.invoices.index') }}">
                            <span class="fas fa-file-invoice-dollar d-block fs-3 mb-2"></span>Invoices
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.admin')

@section('title', 'Campus Details')

@section('content')
<div class="card mb-3">
    <div class="card-header bg-body-tertiary">
        <div class="row flex-between-center">
            <div class="col-auto text-start">
                <h5 class="mb-0">{{ $campus->name }}</h5>
                <p class="fs-11 mb-0">{{ $campus->school->name }}</p>
            </div>
            <div class="col-auto">
                <a class="btn btn-falcon-default btn-sm" href="{{ route('admin.campuses.edit', $campus->id) }}"><span class="fas fa-edit me-1"></span>Edit</a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6 text-start">
                <h6 class="text-500 fs-11 text-uppercase">Activity Status</h6>
                @php $status = $campus->activity_status; @endphp
                @if($status['is_low'])
                    <div class="alert alert-warning p-2 mb-0 border-dashed border-warning">
                        <span class="fas fa-exclamation-triangle me-1"></span>{{ $status['message'] }}
                    </div>
                @else
                    <div class="alert alert-success p-2 mb-0 border-dashed border-success">
                        <span class="fas fa-check-circle me-1"></span>Normal Activity ({{ $status['count'] }} users)
                    </div>
                @endif
            </div>
            
            <div class="col-md-3 text-start">
                <h6 class="text-500 fs-11 text-uppercase">Total Students/Staff</h6>
                <div class="fs-6 fw-bold text-primary">{{ $status['count'] }}</div>
            </div>
            
            <div class="col-md-3 text-start">
                <h6 class="text-500 fs-11 text-uppercase">Created At</h6>
                <div class="fw-semi-bold">{{ $campus->created_at->format('M d, Y') }}</div>
            </div>

            <hr class="my-4">

            <div class="col-md-12 text-start">
                <h6 class="text-500 fs-11 text-uppercase mb-2">Detailed Information</h6>
                <div class="row">
                    <div class="col-md-4">
                        <p class="mb-1 text-500">Location:</p>
                        <p class="fw-semi-bold">{{ $campus->city->name }}, {{ $campus->country->name }}</p>
                    </div>
                    <div class="col-md-8">
                        <p class="mb-1 text-500">Full Address:</p>
                        <p class="fw-semi-bold">{{ $campus->address ?: 'No address specified' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header bg-body-tertiary">
        <h5 class="mb-0">Recent Users in this Campus</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive scrollbar">
            <table class="table table-sm table-striped fs-10 mb-0">
                <thead class="bg-200 text-900">
                    <tr>
                        <th class="sort ps-3">Name</th>
                        <th class="sort">Email</th>
                        <th class="sort">Role</th>
                        <th class="sort text-end pe-3">Joined</th>
                    </tr>
                </thead>
                <tbody class="list">
                    @forelse($campus->users()->latest()->limit(10)->get() as $user)
                    <tr>
                        <td class="align-middle white-space-nowrap ps-3 fw-semi-bold">{{ $user->name }}</td>
                        <td class="align-middle white-space-nowrap">{{ $user->email }}</td>
                        <td class="align-middle white-space-nowrap">
                            @foreach($user->roles as $role)
                                <span class="badge badge-subtle-secondary">{{ $role->name }}</span>
                            @endforeach
                        </td>
                        <td class="align-middle white-space-nowrap text-end pe-3">{{ $user->created_at->format('M d, Y') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-4 text-500">No users found in this campus.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

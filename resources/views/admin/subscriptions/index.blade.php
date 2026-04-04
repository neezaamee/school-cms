@extends('layouts.admin')

@section('title', 'Subscription Packages')

@section('content')
<div class="card mb-3">
    <div class="card-header">
        <div class="row flex-between-center">
            <div class="col-4 col-sm-auto">
                <h5 class="fs-9 mb-0 text-nowrap py-2 py-xl-0">Subscription Packages Management</h5>
            </div>
            <div class="col-8 col-sm-auto text-end ps-2">
                <a href="{{ route('subscription-packages.create') }}" class="btn btn-falcon-default btn-sm">
                    <span class="fas fa-plus" data-fa-transform="shrink-3"></span>
                    <span class="d-none d-sm-inline-block ms-1">New Package</span>
                </a>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="falcon-data-table">
            <table class="table table-sm table-striped fs-10 mb-0 data-table" data-datatables='{"paging":true,"searching":true,"responsive":true,"pageLength":10,"order":[[0,"asc"]],"info":true,"lengthChange":true,"dom":"<\"row mx-1\"<\"col-sm-12 col-md-6\"l><\"col-sm-12 col-md-6\"f>><\"table-responsive scrollbar\"tr><\"row g-0 align-items-center justify-content-center justify-content-sm-between\"<\"col-auto mb-2 mb-sm-0 px-3\"i><\"col-auto px-3\"p>>","language":{"paginate":{"next":"<span class=\"fas fa-chevron-right\"></span>","previous":"<span class=\"fas fa-chevron-left\"></span>"}}}'>
                <thead class="bg-200 text-900">
                    <tr>
                        <th class="white-space-nowrap align-middle">Package Name</th>
                        <th class="white-space-nowrap align-middle text-center">Price</th>
                        <th class="white-space-nowrap align-middle text-center">User Limit (Admin)</th>
                        <th class="white-space-nowrap align-middle text-center">Student Limit</th>
                        <th class="white-space-nowrap align-middle text-center">Staff Limit</th>
                        <th class="white-space-nowrap align-middle text-center">Entry Limit</th>
                        <th class="white-space-nowrap align-middle text-center">Support</th>
                        <th class="white-space-nowrap align-middle text-end px-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="list">
                    @foreach($packages as $package)
                    <tr class="btn-reveal-trigger">
                        <td class="align-middle white-space-nowrap fw-semi-bold">{{ $package->name }}</td>
                        <td class="align-middle text-center">${{ number_format($package->price, 2) }}</td>
                        <td class="align-middle text-center">{{ number_format($package->user_limit) }}</td>
                        <td class="align-middle text-center">{{ number_format($package->student_limit) }}</td>
                        <td class="align-middle text-center">{{ number_format($package->staff_limit) }}</td>
                        <td class="align-middle text-center">{{ number_format($package->entry_limit) }}</td>
                        <td class="align-middle text-center">
                            @if($package->has_tech_support)
                                <span class="badge badge-subtle-success"><span class="fas fa-check me-1"></span>Yes</span>
                            @else
                                <span class="badge badge-subtle-secondary"><span class="fas fa-times me-1"></span>No</span>
                            @endif
                        </td>
                        <td class="align-middle white-space-nowrap text-end px-3">
                            <div class="dropdown font-sans-serif btn-reveal-trigger">
                                <button class="btn btn-link text-600 btn-sm dropdown-toggle dropdown-caret-none btn-reveal" type="button" data-bs-toggle="dropdown" data-bs-boundary="viewport"><span class="fas fa-ellipsis-h fs-11"></span></button>
                                <div class="dropdown-menu dropdown-menu-end border py-2">
                                    <a class="dropdown-item" href="{{ route('subscription-packages.edit', $package->id) }}">Edit Package</a>
                                    <div class="dropdown-divider"></div>
                                    <form action="{{ route('subscription-packages.destroy', $package->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="dropdown-item text-danger">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

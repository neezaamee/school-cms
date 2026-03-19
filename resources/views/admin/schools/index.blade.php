@extends('layouts.admin')

@section('title', 'School Management')

@section('content')
<div class="card mb-3">
    <div class="card-header">
        <div class="row flex-between-center">
            <div class="col-4 col-sm-auto d-flex align-items-center pe-0">
                <h5 class="fs-9 mb-0 text-nowrap py-2 py-xl-0">Schools Management</h5>
            </div>
            <div class="col-8 col-sm-auto text-end ps-2">
                <a href="{{ route('schools.create') }}" class="btn btn-falcon-default btn-sm" type="button">
                    <span class="fas fa-plus" data-fa-transform="shrink-3"></span>
                    <span class="d-none d-sm-inline-block ms-1">New School</span>
                </a>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="falcon-data-table">
            <table class="table table-sm table-striped fs-10 mb-0 data-table" data-datatables='{"paging":true,"searching":true,"responsive":true,"pageLength":10,"order":[[0,"asc"]],"info":true,"lengthChange":true,"dom":"<\"row mx-1\"<\"col-sm-12 col-md-6\"l><\"col-sm-12 col-md-6\"f>><\"table-responsive scrollbar\"tr><\"row g-0 align-items-center justify-content-center justify-content-sm-between\"<\"col-auto mb-2 mb-sm-0 px-3\"i><\"col-auto px-3\"p>>","language":{"paginate":{"next":"<span class=\"fas fa-chevron-right\"></span>","previous":"<span class=\"fas fa-chevron-left\"></span>"}}}'>
                <thead class="bg-200 text-900">
                    <tr>
                        <th class="sort pe-1 align-middle white-space-nowrap">School Name</th>
                        <th class="sort pe-1 align-middle white-space-nowrap">Location (City, Country)</th>
                        <th class="sort pe-1 align-middle white-space-nowrap text-center">Package</th>
                        <th class="sort pe-1 align-middle white-space-nowrap text-center">Users</th>
                        <th class="align-middle no-sort text-end px-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="list">
                    @foreach($schools as $school)
                    <tr class="btn-reveal-trigger">
                        <td class="align-middle white-space-nowrap">
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-xl me-2">
                                    @if($school->logo)
                                        <img class="rounded-circle" src="{{ asset($school->logo) }}" alt="" />
                                    @else
                                        <div class="avatar-name rounded-circle"><span>{{ substr($school->name, 0, 1) }}</span></div>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <h5 class="mb-0 fs-10 text-primary">{{ $school->name }}</h5>
                                    <div class="fs-11 text-500">{{ $school->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle white-space-nowrap">
                            <span class="fw-semi-bold">{{ $school->mainCampus->city->name ?? 'N/A' }}</span>, 
                            <span class="text-500 fs-11">{{ $school->mainCampus->country->name ?? 'N/A' }}</span>
                        </td>
                        <td class="align-middle text-center">
                            @if($school->subscriptionPackage)
                                <span class="badge badge-subtle-primary">{{ $school->subscriptionPackage->name }}</span>
                            @else
                                <span class="badge badge-subtle-secondary">No Package</span>
                            @endif
                        </td>
                        <td class="align-middle text-center">{{ $school->users_count }}</td>
                        <td class="align-middle white-space-nowrap text-end px-3">
                            <div class="dropdown font-sans-serif btn-reveal-trigger">
                                <button class="btn btn-link text-600 btn-sm dropdown-toggle dropdown-caret-none btn-reveal" type="button" id="school-dropdown-{{ $school->id }}" data-bs-toggle="dropdown" data-bs-boundary="viewport" aria-haspopup="true" aria-expanded="false"><span class="fas fa-ellipsis-h fs-11"></span></button>
                                <div class="dropdown-menu dropdown-menu-end border py-2" aria-labelledby="school-dropdown-{{ $school->id }}">
                                    <a class="dropdown-item" href="{{ route('schools.show', $school->id) }}">View Details</a>
                                    <a class="dropdown-item" href="{{ route('schools.edit', $school->id) }}">Edit School</a>
                                    <a class="dropdown-item" href="{{ route('school.landing', $school->slug) }}" target="_blank">Public Landing Page</a>
                                    <div class="dropdown-divider"></div>
                                    <form action="{{ route('schools.destroy', $school->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to soft-delete this school?')">
                                        @csrf
                                        @method('DELETE')
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

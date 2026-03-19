@extends('layouts.admin')

@section('title', 'Manage Campuses')

@section('content')
<div class="card mb-3">
    <div class="card-header bg-body-tertiary">
        <div class="row flex-between-center">
            <div class="col-4 col-sm-auto">
                <h5 class="fs-9 mb-0 text-nowrap py-2 py-xl-0">Manage Campuses</h5>
            </div>
            <div class="col-8 col-sm-auto text-end ps-2">
                <a href="{{ route('admin.campuses.create') }}" class="btn btn-falcon-default btn-sm">
                    <span class="fas fa-plus" data-fa-transform="shrink-3"></span>
                    <span class="ms-1">Add Campus</span>
                </a>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="falcon-data-table">
            <table class="table table-sm table-striped fs-10 mb-0 data-table" data-datatables='{"paging":true,"searching":true,"responsive":true,"pageLength":10,"order":[[0,"asc"]],"info":true,"lengthChange":true,"dom":"<\"row mx-1\"<\"col-sm-12 col-md-6\"l><\"col-sm-12 col-md-6\"f>><\"table-responsive scrollbar\"tr><\"row g-0 align-items-center justify-content-center justify-content-sm-between\"<\"col-auto mb-2 mb-sm-0 px-3\"i><\"col-auto px-3\"p>>","language":{"paginate":{"next":"<span class=\"fas fa-chevron-right\"></span>","previous":"<span class=\"fas fa-chevron-left\"></span>"}}}'>
                <thead class="bg-200 text-900">
                    <tr>
                        <th class="sort pe-1 align-middle white-space-nowrap">Name</th>
                        @role('super admin')
                        <th class="sort pe-1 align-middle white-space-nowrap">School</th>
                        @endrole
                        <th class="sort pe-1 align-middle white-space-nowrap">Location</th>
                        <th class="sort pe-1 align-middle white-space-nowrap text-center">Status</th>
                        <th class="sort pe-1 align-middle white-space-nowrap text-center">Users</th>
                        <th class="align-middle no-sort text-end px-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="list">
                    @foreach($campuses as $campus)
                    <tr>
                        <td class="align-middle white-space-nowrap fw-semi-bold">
                            {{ $campus->name }}
                            @if($campus->is_main)
                                <span class="badge rounded-pill badge-subtle-primary ms-1">Main</span>
                            @endif
                        </td>
                        @role('super admin')
                        <td class="align-middle white-space-nowrap">{{ $campus->school->name }}</td>
                        @endrole
                        <td class="align-middle white-space-nowrap">
                            {{ $campus->city->name ?? 'N/A' }}
                            <div class="text-500 fs-11">{{ Str::limit($campus->address, 30) }}</div>
                        </td>
                        <td class="align-middle text-center">
                            @php
                                $status = $campus->activity_status;
                            @endphp
                            @if($status['is_low'])
                                @role('super admin')
                                    <span class="badge badge-subtle-danger" data-bs-toggle="tooltip" title="{{ $status['message'] }}">
                                        <span class="fas fa-exclamation-triangle me-1"></span>Low Activity
                                    </span>
                                @else
                                    <span class="badge badge-subtle-success">Active</span>
                                @endrole
                            @else
                                <span class="badge badge-subtle-success">Active</span>
                            @endif
                        </td>
                        <td class="align-middle text-center">
                            <span class="fw-semi-bold">{{ $status['count'] }}</span>
                        </td>
                        <td class="align-middle white-space-nowrap text-end px-3">
                            <a class="btn btn-link link-600 btn-sm" href="{{ route('admin.campuses.show', $campus->id) }}"><span class="fas fa-eye"></span></a>
                            <a class="btn btn-link link-600 btn-sm" href="{{ route('admin.campuses.edit', $campus->id) }}"><span class="fas fa-edit"></span></a>
                            <form action="{{ route('admin.campuses.destroy', $campus->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this campus and all associated data?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-link link-danger btn-sm"><span class="fas fa-trash-alt"></span></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

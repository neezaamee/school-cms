@extends('layouts.admin')

@section('content')
<div class="card mb-3">
    <div class="card-header">
        <div class="row flex-between-end">
            <div class="col-auto align-self-center">
                <h5 class="mb-0">Staff Members</h5>
                <p class="mb-0 pt-1 fs-11">Manage all staff members of your school</p>
            </div>
            <div class="col-auto ms-auto">
                <a class="btn btn-falcon-default btn-sm" href="{{ route('admin.staffs.create') }}">
                    <span class="fas fa-plus me-1" data-fa-transform="shrink-3"></span>Add Staff
                </a>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="falcon-data-table">
            <table class="table table-sm table-striped fs-10 mb-0 data-table" data-datatables='{"paging":true,"searching":true,"responsive":true,"pageLength":10,"order":[[0,"asc"]],"info":true,"lengthChange":true,"dom":"<\"row mx-1\"<\"col-sm-12 col-md-6\"l><\"col-sm-12 col-md-6\"f>><\"table-responsive scrollbar\"tr><\"row g-0 align-items-center justify-content-center justify-content-sm-between\"<\"col-auto mb-2 mb-sm-0 px-3\"i><\"col-auto px-3\"p>>","language":{"paginate":{"next":"<span class=\"fas fa-chevron-right\"></span>","previous":"<span class=\"fas fa-chevron-left\"></span>"}}}'>
                <thead class="bg-200 text-900">
                    <tr>
                        <th class="sort">Name</th>
                        <th class="sort">Email</th>
                        <th class="sort">Designation</th>
                        <th class="sort">Campus</th>
                        <th class="sort">Account Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody class="list">
                    @foreach($staffs as $staff)
                    <tr>
                        <td class="align-middle white-space-nowrap">
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-xl me-2">
                                    <img class="rounded-circle" src="{{ $staff->profile_photo ? asset('storage/' . $staff->profile_photo) : asset('assets/img/team/avatar.png') }}" alt="" />
                                </div>
                                <div class="flex-1">
                                    <h6 class="mb-0 ps-2 truncate">{{ $staff->name }}</h6>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle white-space-nowrap">{{ $staff->email }}</td>
                        <td class="align-middle white-space-nowrap">
                            <span class="badge badge-subtle-primary">{{ $staff->designation->name }}</span>
                        </td>
                        <td class="align-middle white-space-nowrap">
                            {{ $staff->campus ? $staff->campus->name : 'N/A' }}
                        </td>
                        <td class="align-middle white-space-nowrap">
                            @if($staff->user_id)
                                <span class="badge rounded-pill badge-subtle-success">
                                    <span class="fas fa-check me-1"></span>Connected
                                </span>
                            @else
                                <span class="badge rounded-pill badge-subtle-secondary">
                                    <span class="fas fa-user-slash me-1"></span>No Account
                                </span>
                            @endif
                        </td>
                        <td class="align-middle white-space-nowrap text-end">
                            <div class="dropdown font-sans-serif btn-reveal-trigger">
                                <button class="btn btn-link text-600 btn-sm dropdown-toggle dropdown-caret-none btn-reveal" type="button" id="staff-dropdown-{{ $staff->id }}" data-bs-toggle="dropdown" data-boundary="viewport" aria-haspopup="true" aria-expanded="false">
                                    <span class="fas fa-ellipsis-h fs-11"></span>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end border py-2" aria-labelledby="staff-dropdown-{{ $staff->id }}">
                                    @if(!$staff->user_id && auth()->user()->hasAnyRole(['super admin', 'school owner', 'principal', 'school administrator', 'school manager']))
                                        <form action="{{ route('admin.staffs.create-user', $staff->id) }}" method="POST">
                                            @csrf
                                            <button class="dropdown-item text-primary" type="submit">Create User Account</button>
                                        </form>
                                        <div class="dropdown-divider"></div>
                                    @endif
                                    <a class="dropdown-item" href="{{ route('admin.staffs.show', $staff->id) }}">View Profile</a>
                                    <a class="dropdown-item" href="{{ route('admin.staffs.edit', $staff->id) }}">Edit</a>
                                    <div class="dropdown-divider"></div>
                                    <form action="{{ route('admin.staffs.destroy', $staff->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="dropdown-item text-danger" type="submit">Delete</button>
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

@extends('layouts.admin')

@section('title', 'Role Management')

@section('content')
<div class="card mb-3">
    <div class="card-header">
        <div class="row flex-between-center">
            <div class="col-4 col-sm-auto d-flex align-items-center pe-0">
                <h5 class="fs-9 mb-0 text-nowrap py-2 py-xl-0">Role Management</h5>
            </div>
            <div class="col-8 col-sm-auto text-end ps-2">
                <a href="{{ route('roles.create') }}" class="btn btn-falcon-default btn-sm" type="button">
                    <span class="fas fa-plus" data-fa-transform="shrink-3"></span>
                    <span class="d-none d-sm-inline-block ms-1">New Role</span>
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
                        <th class="sort pe-1 align-middle white-space-nowrap">Permissions</th>
                        <th class="align-middle no-sort text-end px-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="list" id="table-roles-body">
                    @foreach($roles as $role)
                    <tr class="btn-reveal-trigger">
                        <td class="name align-middle white-space-nowrap">{{ $role->name }}</td>
                        <td class="permissions align-middle">
                            @foreach($role->permissions as $permission)
                                <span class="badge badge-subtle-success">{{ $permission->name }}</span>
                            @endforeach
                        </td>
                        <td class="align-middle white-space-nowrap text-end px-3">
                            <div class="dropdown font-sans-serif btn-reveal-trigger">
                                <button class="btn btn-link text-600 btn-sm dropdown-toggle dropdown-caret-none btn-reveal" type="button" id="role-dropdown-{{ $role->id }}" data-bs-toggle="dropdown" data-bs-boundary="viewport" aria-haspopup="true" aria-expanded="false"><span class="fas fa-ellipsis-h fs-11"></span></button>
                                <div class="dropdown-menu dropdown-menu-end border py-2" aria-labelledby="role-dropdown-{{ $role->id }}">
                                    <a class="dropdown-item" href="{{ route('roles.edit', $role->id) }}">Edit</a>
                                    <div class="dropdown-divider"></div>
                                    <form action="{{ route('roles.destroy', $role->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
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

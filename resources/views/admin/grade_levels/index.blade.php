@extends('layouts.admin')

@section('title', 'Grade Levels')

@section('content')
<div class="card mb-3">
    <div class="card-header bg-body-tertiary">
        <div class="row flex-between-center">
            <div class="col-4 col-sm-auto">
                <h5 class="fs-9 mb-0 text-nowrap py-2 py-xl-0">Grade Levels</h5>
            </div>
            <div class="col-8 col-sm-auto text-end ps-2">
                <a href="{{ route('admin.grade-levels.create') }}" class="btn btn-falcon-default btn-sm">
                    <span class="fas fa-plus" data-fa-transform="shrink-3"></span>
                    <span class="ms-1">Add Grade Level</span>
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
                        <th class="sort pe-1 align-middle white-space-nowrap">Code</th>
                        <th class="sort pe-1 align-middle white-space-nowrap text-center">Status</th>
                        <th class="align-middle no-sort text-end px-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="list">
                    @foreach($gradeLevels as $gradeLevel)
                    <tr>
                        <td class="align-middle white-space-nowrap fw-semi-bold">{{ $gradeLevel->name }}</td>
                        <td class="align-middle white-space-nowrap">{{ $gradeLevel->code ?? 'N/A' }}</td>
                        <td class="align-middle text-center">
                            <span class="badge badge-subtle-{{ $gradeLevel->status === 'active' ? 'success' : 'danger' }}">
                                {{ ucfirst($gradeLevel->status) }}
                            </span>
                        </td>
                        <td class="align-middle white-space-nowrap text-end px-3">
                            <a class="btn btn-link link-600 btn-sm" href="{{ route('admin.grade-levels.edit', $gradeLevel->id) }}"><span class="fas fa-edit"></span></a>
                            <form action="{{ route('admin.grade-levels.destroy', $gradeLevel->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this grade level and all its sections/subjects?')">
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

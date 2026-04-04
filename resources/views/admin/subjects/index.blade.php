@extends('layouts.admin')

@section('title', 'Manage Subjects')

@section('content')
<div class="card mb-3">
    <div class="card-header bg-body-tertiary">
        <div class="row flex-between-center">
            <div class="col-4 col-sm-auto">
                <h5 class="fs-9 mb-0 text-nowrap py-2 py-xl-0">Manage Subjects</h5>
            </div>
            <div class="col-8 col-sm-auto text-end ps-2">
                <a href="{{ route('admin.subjects.create') }}" class="btn btn-falcon-default btn-sm">
                    <span class="fas fa-plus" data-fa-transform="shrink-3"></span>
                    <span class="ms-1">Add Subject</span>
                </a>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="falcon-data-table">
            <table class="table table-sm table-striped fs-10 mb-0 data-table" data-datatables='{"paging":true,"searching":true,"responsive":true,"pageLength":10,"order":[[0,"asc"]],"info":true,"lengthChange":true,"dom":"<\"row mx-1\"<\"col-sm-12 col-md-6\"l><\"col-sm-12 col-md-6\"f>><\"table-responsive scrollbar\"tr><\"row g-0 align-items-center justify-content-center justify-content-sm-between\"<\"col-auto mb-2 mb-sm-0 px-3\"i><\"col-auto px-3\"p>>","language":{"paginate":{"next":"<span class=\"fas fa-chevron-right\"></span>","previous":"<span class=\"fas fa-chevron-left\"></span>"}}}'>
                <thead class="bg-200 text-900">
                    <tr>
                        <th class="sort pe-1 align-middle white-space-nowrap">Subject Name</th>
                        <th class="sort pe-1 align-middle white-space-nowrap">Code</th>
                        <th class="sort pe-1 align-middle white-space-nowrap">Linked Classes & Type</th>
                        <th class="align-middle no-sort text-end px-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="list">
                    @foreach($subjects as $subject)
                    <tr>
                        <td class="align-middle white-space-nowrap">
                            <div class="fw-semi-bold">{{ $subject->name }}</div>
                            <div class="text-500 fs-11">{{ Str::limit($subject->description, 50) }}</div>
                        </td>
                        <td class="align-middle white-space-nowrap">{{ $subject->code ?? 'N/A' }}</td>
                        <td class="align-middle">
                            @forelse($subject->gradeLevels as $gl)
                                <span class="badge badge-subtle-{{ $gl->pivot->is_elective ? 'warning' : 'info' }} me-1" title="{{ $gl->pivot->is_elective ? 'Elective' : 'Compulsory' }} in {{ $gl->name }}">
                                    {{ $gl->name }} {{ $gl->pivot->is_elective ? '(E)' : '(C)' }}
                                </span>
                            @empty
                                <span class="text-400 italic fs-11">Unassigned (Pool Only)</span>
                            @endforelse
                        </td>
                        <td class="align-middle white-space-nowrap text-end px-3">
                            <a class="btn btn-link link-600 btn-sm" href="{{ route('admin.subjects.edit', $subject->id) }}"><span class="fas fa-edit"></span></a>
                            <form action="{{ route('admin.subjects.destroy', $subject->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this subject?')">
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

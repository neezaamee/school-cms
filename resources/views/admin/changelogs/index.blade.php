@extends('layouts.admin')

@section('title', 'Manage Changelogs')

@section('content')
<div class="card mb-3">
    <div class="card-header">
        <div class="row flex-between-center">
            <div class="col-4 col-sm-auto">
                <h5 class="fs-9 mb-0 text-nowrap py-2 py-xl-0">Manage Application Updates</h5>
            </div>
            <div class="col-8 col-sm-auto text-end ps-2">
                <a href="{{ route('admin.changelogs.create') }}" class="btn btn-falcon-default btn-sm">
                    <span class="fas fa-plus" data-fa-transform="shrink-3"></span>
                    <span class="ms-1">New Entry</span>
                </a>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="falcon-data-table">
            <table class="table table-sm table-striped fs-10 mb-0 data-table" data-datatables='{"paging":true,"searching":true,"responsive":true,"pageLength":10,"order":[[4,"desc"]],"info":true,"lengthChange":true,"dom":"<\"row mx-1\"<\"col-sm-12 col-md-6\"l><\"col-sm-12 col-md-6\"f>><\"table-responsive scrollbar\"tr><\"row g-0 align-items-center justify-content-center justify-content-sm-between\"<\"col-auto mb-2 mb-sm-0 px-3\"i><\"col-auto px-3\"p>>","language":{"paginate":{"next":"<span class=\"fas fa-chevron-right\"></span>","previous":"<span class=\"fas fa-chevron-left\"></span>"}}}'>
                <thead class="bg-200 text-900">
                    <tr>
                        <th class="sort pe-1 align-middle white-space-nowrap">Version</th>
                        <th class="sort pe-1 align-middle white-space-nowrap">Title</th>
                        <th class="sort pe-1 align-middle white-space-nowrap text-center">Type</th>
                        <th class="sort pe-1 align-middle white-space-nowrap text-center">Status</th>
                        <th class="sort pe-1 align-middle white-space-nowrap">Release Date</th>
                        <th class="align-middle no-sort text-end px-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="list">
                    @foreach($changelogs as $log)
                    <tr>
                        <td class="align-middle white-space-nowrap fw-semi-bold text-primary">v{{ $log->version ?: 'N/A' }}</td>
                        <td class="align-middle white-space-nowrap fw-semi-bold">{{ $log->title }}</td>
                        <td class="align-middle text-center">
                            @php
                                $badgeClass = [
                                    'feature' => 'badge-subtle-success',
                                    'improvement' => 'badge-subtle-info',
                                    'bugfix' => 'badge-subtle-warning',
                                    'security' => 'badge-subtle-danger'
                                ][$log->type] ?? 'badge-subtle-secondary';
                            @endphp
                            <span class="badge {{ $badgeClass }} text-uppercase">{{ $log->type }}</span>
                        </td>
                        <td class="align-middle text-center">
                            @if($log->is_published)
                                <span class="badge rounded-pill badge-subtle-success">Published</span>
                            @else
                                <span class="badge rounded-pill badge-subtle-secondary">Draft</span>
                            @endif
                        </td>
                        <td class="align-middle white-space-nowrap">{{ $log->release_date ? $log->release_date->format('M d, Y') : 'TBA' }}</td>
                        <td class="align-middle white-space-nowrap text-end px-3">
                            <a class="btn btn-link link-600 btn-sm" href="{{ route('admin.changelogs.edit', $log->id) }}"><span class="fas fa-edit"></span></a>
                            <form action="{{ route('admin.changelogs.destroy', $log->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this entry?')">
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

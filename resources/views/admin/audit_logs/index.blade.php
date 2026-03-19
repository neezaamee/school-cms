@extends('layouts.admin')

@section('title', 'System Audit Logs')

@section('content')
<div class="card mb-3">
    <div class="card-header">
        <div class="row flex-between-center">
            <div class="col-4 col-sm-auto">
                <h5 class="fs-9 mb-0 text-nowrap py-2 py-xl-0">System Audit Logs (View Only)</h5>
            </div>
            <div class="col-8 col-sm-auto text-end ps-2">
                <button class="btn btn-falcon-default btn-sm" onclick="window.location.reload()">
                    <span class="fas fa-sync" data-fa-transform="shrink-3"></span>
                    <span class="ms-1">Refresh</span>
                </button>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="falcon-data-table">
            <table class="table table-sm table-striped fs-10 mb-0 data-table" data-datatables='{"paging":true,"searching":true,"responsive":true,"pageLength":10,"order":[[0,"desc"]],"info":true,"lengthChange":true,"dom":"<\"row mx-1\"<\"col-sm-12 col-md-6\"l><\"col-sm-12 col-md-6\"f>><\"table-responsive scrollbar\"tr><\"row g-0 align-items-center justify-content-center justify-content-sm-between\"<\"col-auto mb-2 mb-sm-0 px-3\"i><\"col-auto px-3\"p>>","language":{"paginate":{"next":"<span class=\"fas fa-chevron-right\"></span>","previous":"<span class=\"fas fa-chevron-left\"></span>"}}}'>
                <thead class="bg-200 text-900">
                    <tr>
                        <th class="sort pe-1 align-middle white-space-nowrap">Timestamp</th>
                        <th class="sort pe-1 align-middle white-space-nowrap">User</th>
                        <th class="sort pe-1 align-middle white-space-nowrap">Action</th>
                        <th class="sort pe-1 align-middle white-space-nowrap">Module</th>
                        <th class="sort pe-1 align-middle white-space-nowrap">IP Address</th>
                        <th class="align-middle no-sort text-end px-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="list">
                    @foreach($logs as $log)
                    <tr>
                        <td class="align-middle white-space-nowrap text-700 fw-semi-bold">
                            {{ $log->created_at->format('Y-m-d H:i:s') }}
                        </td>
                        <td class="align-middle white-space-nowrap">
                            @if($log->user)
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-s me-2">
                                        <div class="avatar-name rounded-circle"><span>{{ substr($log->user->name, 0, 1) }}</span></div>
                                    </div>
                                    <div class="flex-1">
                                        <h6 class="mb-0 fs-11 text-900">{{ $log->user->name }}</h6>
                                    </div>
                                </div>
                            @else
                                <span class="text-500">System</span>
                            @endif
                        </td>
                        <td class="align-middle white-space-nowrap">
                            <span class="badge badge-subtle-info text-uppercase fw-bold">{{ $log->action }}</span>
                        </td>
                        <td class="align-middle white-space-nowrap">
                            <span class="text-700">{{ $log->model ?: 'Global' }}</span>
                        </td>
                        <td class="align-middle white-space-nowrap font-sans-serif">
                            <code>{{ $log->ip_address }}</code>
                        </td>
                        <td class="align-middle white-space-nowrap text-end px-3">
                            <a class="btn btn-link link-600 btn-sm" href="{{ route('audit-logs.show', $log->id) }}">
                                <span class="fas fa-eye text-primary"></span>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

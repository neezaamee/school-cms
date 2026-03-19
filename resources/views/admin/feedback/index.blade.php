@extends('layouts.admin')

@section('title', 'User Feedback')

@section('content')
<div class="card mb-3">
    <div class="card-header">
        <div class="row flex-between-center">
            <div class="col-4 col-sm-auto">
                <h5 class="fs-9 mb-0 text-nowrap py-2 py-xl-0">User Feedback & Suggestions</h5>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="falcon-data-table">
            <table class="table table-sm table-striped fs-10 mb-0 data-table" data-datatables='{"paging":true,"searching":true,"responsive":true,"pageLength":10,"order":[[4,"desc"]],"info":true,"lengthChange":true,"dom":"<\"row mx-1\"<\"col-sm-12 col-md-6\"l><\"col-sm-12 col-md-6\"f>><\"table-responsive scrollbar\"tr><\"row g-0 align-items-center justify-content-center justify-content-sm-between\"<\"col-auto mb-2 mb-sm-0 px-3\"i><\"col-auto px-3\"p>>","language":{"paginate":{"next":"<span class=\"fas fa-chevron-right\"></span>","previous":"<span class=\"fas fa-chevron-left\"></span>"}}}'>
                <thead class="bg-200 text-900">
                    <tr>
                        <th class="sort pe-1 align-middle white-space-nowrap">Submitted By</th>
                        <th class="sort pe-1 align-middle white-space-nowrap">Subject</th>
                        <th class="sort pe-1 align-middle white-space-nowrap text-center">Type</th>
                        <th class="sort pe-1 align-middle white-space-nowrap text-center">Status</th>
                        <th class="sort pe-1 align-middle white-space-nowrap">Date</th>
                        <th class="align-middle no-sort text-end px-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="list">
                    @foreach($feedbacks as $feedback)
                    <tr>
                        <td class="align-middle white-space-nowrap fw-semi-bold">
                            {{ $feedback->user->name }}
                            <div class="text-500 fs-11">{{ $feedback->user->email }}</div>
                        </td>
                        <td class="align-middle white-space-nowrap fw-semi-bold">{{ $feedback->subject }}</td>
                        <td class="align-middle text-center">
                            @php
                                $typeClass = [
                                    'bug' => 'badge-subtle-danger',
                                    'suggestion' => 'badge-subtle-info',
                                    'request' => 'badge-subtle-primary',
                                    'other' => 'badge-subtle-secondary'
                                ][$feedback->type] ?? 'badge-subtle-secondary';
                            @endphp
                            <span class="badge {{ $typeClass }} text-uppercase">{{ $feedback->type }}</span>
                        </td>
                        <td class="align-middle text-center">
                            @php
                                $statusClass = [
                                    'new' => 'badge-subtle-warning',
                                    'in_review' => 'badge-subtle-primary',
                                    'resolved' => 'badge-subtle-success',
                                    'closed' => 'badge-subtle-secondary'
                                ][$feedback->status] ?? 'badge-subtle-secondary';
                            @endphp
                            <span class="badge {{ $statusClass }} text-uppercase">{{ str_replace('_', ' ', $feedback->status) }}</span>
                        </td>
                        <td class="align-middle white-space-nowrap">{{ $feedback->created_at->format('M d, Y') }}</td>
                        <td class="align-middle white-space-nowrap text-end px-3">
                            <a class="btn btn-link link-600 btn-sm" href="{{ route('admin.feedback.show', $feedback->id) }}"><span class="fas fa-eye"></span></a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

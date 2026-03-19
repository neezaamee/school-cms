@extends('layouts.admin')

@section('title', 'My Feedback')

@section('content')
<div class="card mb-3">
    <div class="card-header bg-primary text-white py-3">
        <div class="row flex-between-center">
            <div class="col-auto text-start">
                <h5 class="mb-0 text-white">Your Feedback History</h5>
                <p class="fs-11 mb-0 opacity-75 text-white">Track the status of your suggestions and issues</p>
            </div>
            <div class="col-auto">
                <a href="{{ route('user.feedback.create') }}" class="btn btn-falcon-default btn-sm">
                    <span class="fas fa-plus me-1"></span>Submit New
                </a>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        @if($feedbacks->isEmpty())
            <div class="text-center py-5">
                <span class="fas fa-comments fs-4 text-300 mb-3"></span>
                <h5>No feedback submitted yet.</h5>
                <p class="text-600 mb-3">Your voice helps us improve. Share your thoughts with us!</p>
                <a class="btn btn-primary btn-sm" href="{{ route('user.feedback.create') }}">Submit Your First Feedback</a>
            </div>
        @else
            <div class="table-responsive scrollbar">
                <table class="table table-sm table-striped fs-10 mb-0">
                    <thead class="bg-200 text-900">
                        <tr>
                            <th class="align-middle white-space-nowrap text-start ps-3">Date</th>
                            <th class="align-middle white-space-nowrap text-start">Subject</th>
                            <th class="align-middle text-center">Type</th>
                            <th class="align-middle text-center">Status</th>
                            <th class="align-middle text-end pe-3">Action</th>
                        </tr>
                    </thead>
                    <tbody class="list">
                        @foreach($feedbacks as $feedback)
                        <tr>
                            <td class="align-middle white-space-nowrap text-start ps-3">{{ $feedback->created_at->format('M d, Y') }}</td>
                            <td class="align-middle white-space-nowrap text-start pe-3">
                                <div class="fw-semi-bold">{{ $feedback->subject }}</div>
                                <div class="text-500 fs-11 text-truncate" style="max-width: 300px;">{{ $feedback->message }}</div>
                            </td>
                            <td class="align-middle text-center">
                                <span class="badge badge-subtle-info text-uppercase">{{ $feedback->type }}</span>
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
                            <td class="align-middle text-end pe-3">
                                <button class="btn btn-link link-600 btn-sm" data-bs-toggle="modal" data-bs-target="#feedbackModal{{ $feedback->id }}">
                                    <span class="fas fa-eye"></span>
                                </button>
                            </td>
                        </tr>

                        <!-- Details Modal -->
                        <div class="modal fade" id="feedbackModal{{ $feedback->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content text-start">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Feedback Detail</h5>
                                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <h6 class="text-600 mb-1">Your Message ({{ $feedback->created_at->format('M d, Y') }})</h6>
                                        <p class="fw-semi-bold mb-3">{{ $feedback->subject }}</p>
                                        <div class="bg-light p-3 rounded-2 border mb-4">
                                            {{ $feedback->message }}
                                        </div>

                                        @if($feedback->admin_remark)
                                            <h6 class="text-primary mb-1"><span class="fas fa-reply me-1"></span>Admin Response:</h6>
                                            <div class="bg-primary-subtle p-3 rounded-2 border border-primary-subtle">
                                                {{ $feedback->admin_remark }}
                                            </div>
                                        @else
                                            <div class="text-center py-3 bg-light rounded-2 border border-dashed text-500">
                                                <span class="fas fa-clock me-1"></span> Pending admin review...
                                            </div>
                                        @endif
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-secondary btn-sm" type="button" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection

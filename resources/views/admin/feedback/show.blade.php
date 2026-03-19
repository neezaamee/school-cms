@extends('layouts.admin')

@section('title', 'Feedback Detail')

@section('content')
<div class="row g-3">
    <div class="col-lg-8">
        <div class="card mb-3">
            <div class="card-header bg-body-tertiary">
                <div class="row flex-between-center">
                    <div class="col-auto">
                        <h5 class="mb-0">Feedback from {{ $feedback->user->name }}</h5>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <h6 class="text-700">Subject:</h6>
                <p class="fw-semi-bold fs-8">{{ $feedback->subject }}</p>
                
                <h6 class="text-700 mt-4">Message:</h6>
                <div class="bg-light p-3 rounded-3 border">
                    {{ $feedback->message }}
                </div>
                
                <div class="row mt-4">
                    <div class="col-6 col-md-3">
                        <h6 class="text-500 fs-11 text-uppercase">Type</h6>
                        <p class="fw-semi-bold">{{ ucfirst($feedback->type) }}</p>
                    </div>
                    <div class="col-6 col-md-3">
                        <h6 class="text-500 fs-11 text-uppercase">Submitted on</h6>
                        <p class="fw-semi-bold">{{ $feedback->created_at->format('M d, Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card mb-3">
            <div class="card-header bg-body-tertiary">
                <h5 class="mb-0">Management Options</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.feedback.update', $feedback->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3 text-start">
                        <label class="form-label" for="status">Update Status</label>
                        <select class="form-select" name="status" id="status">
                            <option value="new" {{ $feedback->status == 'new' ? 'selected' : '' }}>New</option>
                            <option value="in_review" {{ $feedback->status == 'in_review' ? 'selected' : '' }}>In Review</option>
                            <option value="resolved" {{ $feedback->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                            <option value="closed" {{ $feedback->status == 'closed' ? 'selected' : '' }}>Closed</option>
                        </select>
                    </div>
                    
                    <div class="mb-3 text-start">
                        <label class="form-label" for="admin_remark">Admin Remark (Internal)</label>
                        <textarea class="form-control" name="admin_remark" id="admin_remark" rows="5" placeholder="Internal notes mapping this to a task or bug ID...">{{ $feedback->admin_remark }}</textarea>
                    </div>
                    
                    <button class="btn btn-primary w-100" type="submit">Update Feedback</button>
                </form>
                
                <hr class="my-4">
                
                <form action="{{ route('admin.feedback.destroy', $feedback->id) }}" method="POST" onsubmit="return confirm('Archive/Delete this feedback permanently?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-falcon-danger btn-sm w-100" type="submit text-start">
                        <span class="fas fa-trash-alt me-1"></span>Delete Feedback
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

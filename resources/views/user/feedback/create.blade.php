@extends('layouts.admin')

@section('title', 'Submit Feedback')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-primary text-white py-4 text-start">
                <h4 class="mb-1 text-white">We Value Your Feedback!</h4>
                <p class="mb-0 opacity-75">Help us make ElafTech Schools even better.</p>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('user.feedback.store') }}" method="POST">
                    @csrf
                    <div class="mb-3 text-start">
                        <label class="form-label fw-bold" for="type">What is this about?</label>
                        <select class="form-select" name="type" id="type" required>
                            <option value="suggestion" selected>I have a suggestion/idea</option>
                            <option value="bug">I found a bug/issue</option>
                            <option value="request">I'm requesting a new feature</option>
                            <option value="other">Something else</option>
                        </select>
                    </div>
                    
                    <div class="mb-3 text-start">
                        <label class="form-label fw-bold" for="subject">Subject</label>
                        <input class="form-control" id="subject" name="subject" type="text" placeholder="Short summary of your feedback" required />
                    </div>
                    
                    <div class="mb-3 text-start">
                        <label class="form-label fw-bold" for="message">Your Message</label>
                        <textarea class="form-control" id="message" name="message" rows="8" placeholder="Tell us more details..." required></textarea>
                        <div class="form-text mt-2">Please be as specific as possible if reporting a bug.</div>
                    </div>
                    
                    <div class="mt-4 pt-3 border-top d-flex justify-content-between">
                        <a class="btn btn-falcon-default" href="{{ route('dashboard') }}">Cancel</a>
                        <button class="btn btn-primary px-5" type="submit">Submit Feedback</button>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="mt-4 text-center">
            <p class="text-600 fs-11">By submitting feedback, you agree that we may use your suggestions to improve our services.</p>
        </div>
    </div>
</div>
@endsection

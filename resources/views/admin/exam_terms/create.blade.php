@extends('layouts.admin')

@section('content')
<div class="card mb-3">
    <div class="card-header bg-body-tertiary">
        <h5 class="mb-0">Setup New Examination Term</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.exam-terms.store') }}" method="POST">
            @csrf
            
            @include('admin.exam_terms.partials.form')

            <div class="mt-4 text-end border-top pt-3">
                <a class="btn btn-falcon-default me-2" href="{{ route('admin.exam-terms.index') }}">Cancel</a>
                <button class="btn btn-primary px-5" type="submit">Create Term</button>
            </div>
        </form>
    </div>
</div>
@endsection

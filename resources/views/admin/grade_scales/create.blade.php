@extends('layouts.admin')

@section('content')
<div class="card mb-3">
    <div class="card-header bg-body-tertiary">
        <h5 class="mb-0">Create New Grade Scale</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.grade-scales.store') }}" method="POST">
            @csrf
            
            @include('admin.grade_scales.partials.form')

            <div class="mt-4 text-end border-top pt-3">
                <a class="btn btn-falcon-default me-2" href="{{ route('admin.grade-scales.index') }}">Cancel</a>
                <button class="btn btn-primary px-5" type="submit">Save Grade Scale</button>
            </div>
        </form>
    </div>
</div>
@endsection

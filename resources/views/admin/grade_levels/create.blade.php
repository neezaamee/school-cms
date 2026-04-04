@extends('layouts.admin')

@section('title', 'Add Grade Level')

@section('content')
<div class="card mb-3">
    <div class="card-header bg-body-tertiary shadow-sm">
        <h5 class="mb-0">Add New Grade Level</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.grade-levels.store') }}" method="POST">
            @csrf
            @include('admin.grade_levels.partials.form')
            <div class="mt-3 text-end">
                <a href="{{ route('admin.grade-levels.index') }}" class="btn btn-link link-600">Cancel</a>
                <button class="btn btn-primary px-5" type="submit">Create Grade Level</button>
            </div>
        </form>
    </div>
</div>
@endsection

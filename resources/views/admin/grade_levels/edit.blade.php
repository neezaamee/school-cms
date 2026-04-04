@extends('layouts.admin')

@section('title', 'Edit Grade Level')

@section('content')
<div class="card mb-3">
    <div class="card-header bg-body-tertiary">
        <h5 class="mb-0">Edit Grade Level: {{ $gradeLevel->name }}</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.grade-levels.update', $gradeLevel->id) }}" method="POST">
            @csrf
            @method('PUT')
            @include('admin.grade_levels.partials.form')
            <div class="mt-3 text-end">
                <a href="{{ route('admin.grade-levels.index') }}" class="btn btn-link link-600">Cancel</a>
                <button class="btn btn-primary px-5" type="submit">Update Grade Level</button>
            </div>
        </form>
    </div>
</div>
@endsection

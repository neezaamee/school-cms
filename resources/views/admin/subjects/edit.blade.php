@extends('layouts.admin')

@section('title', 'Edit Subject')

@section('content')
<div class="card mb-3">
    <div class="card-header bg-body-tertiary">
        <h5 class="mb-0">Edit Subject: {{ $subject->name }}</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.subjects.update', $subject->id) }}" method="POST">
            @csrf
            @method('PUT')
            @include('admin.subjects.partials.form')
            <div class="mt-3 text-end">
                <a href="{{ route('admin.subjects.index') }}" class="btn btn-link link-600">Cancel</a>
                <button class="btn btn-primary px-5" type="submit">Update Subject</button>
            </div>
        </form>
    </div>
</div>
@endsection

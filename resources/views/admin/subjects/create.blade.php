@extends('layouts.admin')

@section('title', 'Add Subject')

@section('content')
<div class="card mb-3">
    <div class="card-header bg-body-tertiary shadow-sm">
        <h5 class="mb-0">Add New Subject</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.subjects.store') }}" method="POST">
            @csrf
            @include('admin.subjects.partials.form')
            <div class="mt-3 text-end">
                <a href="{{ route('admin.subjects.index') }}" class="btn btn-link link-600">Cancel</a>
                <button class="btn btn-primary px-5" type="submit">Create Subject</button>
            </div>
        </form>
    </div>
</div>
@endsection

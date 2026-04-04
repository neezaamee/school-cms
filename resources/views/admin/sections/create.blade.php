@extends('layouts.admin')

@section('title', 'Add Section')

@section('content')
<div class="card mb-3">
    <div class="card-header bg-body-tertiary">
        <h5 class="mb-0">Add New Section</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.sections.store') }}" method="POST">
            @csrf
            @include('admin.sections.partials.form')
            <div class="mt-3 text-end">
                <a href="{{ route('admin.sections.index') }}" class="btn btn-link link-600">Cancel</a>
                <button class="btn btn-primary px-5" type="submit">Create Section</button>
            </div>
        </form>
    </div>
</div>
@endsection

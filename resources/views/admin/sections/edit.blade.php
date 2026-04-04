@extends('layouts.admin')

@section('title', 'Edit Section')

@section('content')
<div class="card mb-3">
    <div class="card-header bg-body-tertiary">
        <h5 class="mb-0">Edit Section: {{ $section->name }}</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.sections.update', $section->id) }}" method="POST">
            @csrf
            @method('PUT')
            @include('admin.sections.partials.form')
            <div class="mt-3 text-end">
                <a href="{{ route('admin.sections.index') }}" class="btn btn-link link-600">Cancel</a>
                <button class="btn btn-primary px-5" type="submit">Update Section</button>
            </div>
        </form>
    </div>
</div>
@endsection

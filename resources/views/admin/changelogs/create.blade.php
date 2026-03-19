@extends('layouts.admin')

@section('title', isset($changelog) ? 'Edit Changelog' : 'New Changelog')

@section('content')
<div class="card mb-3">
    <div class="card-header bg-body-tertiary">
        <h5 class="mb-0">{{ isset($changelog) ? 'Edit Changelog Entry' : 'Create New Changelog Entry' }}</h5>
    </div>
    <div class="card-body">
        <form action="{{ isset($changelog) ? route('admin.changelogs.update', $changelog->id) : route('admin.changelogs.store') }}" method="POST">
            @csrf
            @if(isset($changelog)) @method('PUT') @endif

            <div class="row g-3">
                <div class="col-md-6 text-start">
                    <label class="form-label" for="title">Update Title</label>
                    <input class="form-control" id="title" name="title" type="text" value="{{ old('title', $changelog->title ?? '') }}" required placeholder="e.g., Integrated Advanced DataTables" />
                </div>
                <div class="col-md-3 text-start">
                    <label class="form-label" for="version">Version Number</label>
                    <input class="form-control" id="version" name="version" type="text" value="{{ old('version', $changelog->version ?? '') }}" placeholder="e.g., 1.1.0" />
                </div>
                <div class="col-md-3 text-start">
                    <label class="form-label" for="type">Update Type</label>
                    <select class="form-select" id="type" name="type">
                        <option value="feature" {{ (old('type', $changelog->type ?? '') == 'feature') ? 'selected' : '' }}>New Feature</option>
                        <option value="improvement" {{ (old('type', $changelog->type ?? '') == 'improvement') ? 'selected' : '' }}>Improvement</option>
                        <option value="bugfix" {{ (old('type', $changelog->type ?? '') == 'bugfix') ? 'selected' : '' }}>Bug Fix</option>
                        <option value="security" {{ (old('type', $changelog->type ?? '') == 'security') ? 'selected' : '' }}>Security Update</option>
                    </select>
                </div>
                <div class="col-md-4 text-start">
                    <label class="form-label" for="release_date">Release Date</label>
                    <input class="form-control" id="release_date" name="release_date" type="date" value="{{ old('release_date', isset($changelog->release_date) ? $changelog->release_date->format('Y-m-d') : now()->format('Y-m-d')) }}" />
                </div>
                <div class="col-md-4 d-flex align-items-end mb-1">
                    <div class="form-check form-switch mb-0">
                        <input type="hidden" name="is_published" value="0">
                        <input class="form-check-input" id="is_published" name="is_published" type="checkbox" value="1" {{ old('is_published', $changelog->is_published ?? 1) ? 'checked' : '' }} />
                        <label class="form-check-label mb-0" for="is_published">Publish Immediately</label>
                    </div>
                </div>
                <div class="col-12 text-start">
                    <label class="form-label" for="description">Detailed Description</label>
                    <textarea class="form-control" id="description" name="description" rows="10" required placeholder="Describe what has been added or changed...">{{ old('description', $changelog->description ?? '') }}</textarea>
                </div>
            </div>

            <div class="mt-4 border-top pt-3">
                <button class="btn btn-primary" type="submit">{{ isset($changelog) ? 'Update Entry' : 'Create Entry' }}</button>
                <a class="btn btn-falcon-default ms-2" href="{{ route('admin.changelogs.index') }}">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

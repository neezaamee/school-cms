@extends('layouts.admin')

@section('title', 'Edit Permission')

@section('content')
<div class="card mb-3">
    <div class="card-header">
        <h5 class="mb-0">Edit Permission: {{ $permission->name }}</h5>
    </div>
    <div class="card-body bg-body-tertiary">
        <form action="{{ route('permissions.update', $permission->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label" for="name">Permission Name</label>
                <input class="form-control @error('name') is-invalid @enderror" id="name" name="name" type="text" placeholder="e.g. edit students" value="{{ old('name', $permission->name) }}" required />
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            
            <button class="btn btn-primary" type="submit">Update Permission</button>
            <a href="{{ route('permissions.index') }}" class="btn btn-link">Cancel</a>
        </form>
    </div>
</div>
@endsection

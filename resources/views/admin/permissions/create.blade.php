@extends('layouts.admin')

@section('title', 'Create Permission')

@section('content')
<div class="card mb-3">
    <div class="card-header">
        <h5 class="mb-0">Create New Permission</h5>
    </div>
    <div class="card-body bg-body-tertiary">
        <form action="{{ route('permissions.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label" for="name">Permission Name</label>
                <input class="form-control @error('name') is-invalid @enderror" id="name" name="name" type="text" placeholder="e.g. edit students" value="{{ old('name') }}" required />
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            
            <button class="btn btn-primary" type="submit">Create Permission</button>
            <a href="{{ route('permissions.index') }}" class="btn btn-link">Cancel</a>
        </form>
    </div>
</div>
@endsection

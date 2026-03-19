@extends('layouts.admin')

@section('title', 'Create Role')

@section('content')
<div class="card mb-3">
    <div class="card-header">
        <h5 class="mb-0">Create New Role</h5>
    </div>
    <div class="card-body bg-body-tertiary">
        <form action="{{ route('roles.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label" for="name">Role Name</label>
                <input class="form-control @error('name') is-invalid @enderror" id="name" name="name" type="text" placeholder="e.g. academic editor" value="{{ old('name') }}" required />
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            
            <div class="mb-3">
                <label class="form-label">Assign Permissions</label>
                <div class="row">
                    @foreach($permissions as $permission)
                    <div class="col-sm-4 col-md-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permission->name }}" id="perm-{{ $permission->id }}" {{ (is_array(old('permissions')) && in_array($permission->name, old('permissions'))) ? 'checked' : '' }}>
                            <label class="form-check-label fs-10" for="perm-{{ $permission->id }}">
                                {{ $permission->name }}
                            </label>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <button class="btn btn-primary" type="submit">Create Role</button>
            <a href="{{ route('roles.index') }}" class="btn btn-link">Cancel</a>
        </form>
    </div>
</div>
@endsection

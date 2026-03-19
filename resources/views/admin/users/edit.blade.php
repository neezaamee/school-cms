@extends('layouts.admin')

@section('title', 'Edit User')

@section('content')
<div class="card mb-3">
    <div class="card-header">
        <h5 class="mb-0">Edit User: {{ $user->name }}</h5>
    </div>
    <div class="card-body bg-body-tertiary">
        <form action="{{ route('users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row gx-2">
                <div class="col-sm-6 mb-3">
                    <label class="form-label" for="name">Full Name</label>
                    <input class="form-control @error('name') is-invalid @enderror" id="name" name="name" type="text" placeholder="Full Name" value="{{ old('name', $user->name) }}" required />
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-sm-6 mb-3">
                    <label class="form-label" for="email">Email Address</label>
                    <input class="form-control @error('email') is-invalid @enderror" id="email" name="email" type="email" placeholder="Email Address" value="{{ old('email', $user->email) }}" required />
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="row gx-2">
                <div class="col-sm-6 mb-3">
                    <label class="form-label" for="password">Password (Leave blank to keep current)</label>
                    <input class="form-control @error('password') is-invalid @enderror" id="password" name="password" type="password" placeholder="New Password" />
                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-sm-6 mb-3">
                    <label class="form-label" for="password_confirmation">Confirm New Password</label>
                    <input class="form-control" id="password_confirmation" name="password_confirmation" type="password" placeholder="Confirm New Password" />
                </div>
            </div>
            <div class="row gx-2">
                <div class="col-sm-6 mb-3">
                    <label class="form-label" for="school_id">School</label>
                    <select class="form-select @error('school_id') is-invalid @enderror" id="school_id" name="school_id">
                        <option value="">None (Global Admin)</option>
                        @foreach($schools as $school)
                            <option value="{{ $school->id }}" {{ old('school_id', $user->school_id) == $school->id ? 'selected' : '' }}>{{ $school->name }}</option>
                        @endforeach
                    </select>
                    @error('school_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-sm-6 mb-3">
                    <label class="form-label" for="roles">Roles</label>
                    <select class="form-select @error('roles') is-invalid @enderror" id="roles" name="roles[]" multiple required>
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}" {{ (is_array(old('roles', $user->roles->pluck('name')->toArray())) && in_array($role->name, old('roles', $user->roles->pluck('name')->toArray()))) ? 'selected' : '' }}>{{ $role->name }}</option>
                        @endforeach
                    </select>
                    @error('roles') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <button class="btn btn-primary" type="submit">Update User</button>
            <a href="{{ route('users.index') }}" class="btn btn-link">Cancel</a>
        </form>
    </div>
</div>
@endsection

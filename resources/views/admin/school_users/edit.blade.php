@extends('layouts.admin')

@section('title', 'Edit Personnel')

@section('content')
<div class="card mb-3">
    <div class="card-header bg-body-tertiary">
        <h5 class="mb-0">Edit Personnel: {{ $user->name }}</h5>
    </div>
    <div class="card-body bg-body-tertiary">
        <form action="{{ route('school-users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-3">
                <label class="form-label text-700" for="name">Full Name</label>
                <input class="form-control @error('name') is-invalid @enderror" id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required />
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label text-700" for="email">Email Address</label>
                <input class="form-control @error('email') is-invalid @enderror" id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required />
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label text-700" for="password">Password (Leave blank to keep current)</label>
                    <input class="form-control @error('password') is-invalid @enderror" id="password" name="password" type="password" />
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label text-700" for="password_confirmation">Confirm Password</label>
                    <input class="form-control" id="password_confirmation" name="password_confirmation" type="password" />
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label text-700" for="role">Role</label>
                <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}" {{ (old('role') ?? $user->roles->first()->name ?? '') == $role->name ? 'selected' : '' }}>{{ ucfirst($role->name) }}</option>
                    @endforeach
                </select>
                @error('role')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mt-4">
                <button class="btn btn-primary" type="submit">Update</button>
                <a href="{{ route('school-users.index') }}" class="btn btn-falcon-default ms-2">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

<section>
    <header>
        <h5 class="mb-1 text-900">{{ __('Update Password') }}</h5>
        <p class="fs-10 text-600">{{ __('Ensure your account is using a long, random password to stay secure.') }}</p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-4">
        @csrf
        @method('put')

        <div class="mb-3">
            <label class="form-label text-700" for="update_password_current_password">{{ __('Current Password') }}</label>
            <input class="form-control @error('current_password', 'updatePassword') is-invalid @enderror" id="update_password_current_password" name="current_password" type="password" autocomplete="current-password" />
            @error('current_password', 'updatePassword') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label text-700" for="update_password_password">{{ __('New Password') }}</label>
            <input class="form-control @error('password', 'updatePassword') is-invalid @enderror" id="update_password_password" name="password" type="password" autocomplete="new-password" />
            @error('password', 'updatePassword') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label text-700" for="update_password_password_confirmation">{{ __('Confirm Password') }}</label>
            <input class="form-control" id="update_password_password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" />
        </div>

        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-primary" type="submit">{{ __('Save Changes') }}</button>
            @if (session('status') === 'password-updated')
                <p class="mb-0 fs-10 text-success">{{ __('Password updated successfully.') }}</p>
            @endif
        </div>
    </form>
</section>

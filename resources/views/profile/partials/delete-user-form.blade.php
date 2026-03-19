<section class="mt-4">
    <header>
        <h5 class="mb-1 text-danger">{{ __('Delete Account') }}</h5>
        <p class="fs-10 text-600">{{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}</p>
    </header>

    <button class="btn btn-danger" 
            data-bs-toggle="modal" 
            data-bs-target="#confirmUserDeletionModal">
        {{ __('Delete Account') }}
    </button>

    <!-- Modal -->
    <div class="modal fade" id="confirmUserDeletionModal" tabindex="-1" role="dialog" aria-labelledby="confirmUserDeletionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0">
                <form method="post" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('delete')
                    
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title text-white" id="confirmUserDeletionModalLabel">{{ __('Confirm Account Deletion') }}</h5>
                        <button class="btn-close btn-close-white" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    
                    <div class="modal-body p-4">
                        <p class="text-700">{{ __('Are you sure you want to delete your account?') }}</p>
                        <p class="fs-10 text-600 mb-3">{{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}</p>
                        
                        <div class="mb-3 text-start">
                            <label class="form-label sr-only" for="password">{{ __('Password') }}</label>
                            <input class="form-control @error('password', 'userDeletion') is-invalid @enderror" 
                                   id="password" 
                                   name="password" 
                                   type="password" 
                                   placeholder="{{ __('Password') }}" 
                                   required />
                            @error('password', 'userDeletion') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <button class="btn btn-falcon-default" type="button" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        <button class="btn btn-danger" type="submit">{{ __('Delete Account') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

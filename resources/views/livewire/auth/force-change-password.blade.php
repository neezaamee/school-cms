<div class="container py-5 d-flex align-items-center justify-content-center" style="min-height: 100vh;">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow-lg border-0 rounded-3 overflow-hidden">
            <div class="card-header bg-primary py-4 text-center">
                <div class="mb-3">
                    <span class="fas fa-shield-alt fa-3x text-white"></span>
                </div>
                <h4 class="text-white fw-bold mb-0">Secure Your Account</h4>
                <p class="text-white-50 mb-0 small">Please update your system-generated password to continue.</p>
            </div>
            <div class="card-body p-4 p-lg-5">
                <form wire:submit.prevent="updatePassword">
                    <div class="mb-4">
                        <label class="form-label text-700 fw-bold" for="password">New Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-primary"><span class="fas fa-lock"></span></span>
                            <input class="form-control @error('password') is-invalid @enderror" 
                                id="password" 
                                type="password" 
                                wire:model="password" 
                                placeholder="Enter min 8 characters" />
                        </div>
                        @error('password') <div class="text-danger fs-11 mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-5">
                        <label class="form-label text-700 fw-bold" for="password_confirmation">Confirm Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-primary"><span class="fas fa-check-double"></span></span>
                            <input class="form-control" 
                                id="password_confirmation" 
                                type="password" 
                                wire:model="password_confirmation" 
                                placeholder="Repeat new password" />
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button class="btn btn-primary py-2 fw-bold" type="submit" wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="updatePassword">
                                <span class="fas fa-save me-2"></span>Update Password & Login
                            </span>
                            <span wire:loading wire:target="updatePassword">
                                <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Saving...
                            </span>
                        </button>
                    </div>

                    <div class="mt-4 text-center">
                        <p class="text-600 fs-11 mb-0">
                            <span class="fas fa-info-circle me-1"></span>Passwords must be between 8 and 20 characters and should include an uppercase letter, a number, and a symbol for maximum security.
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

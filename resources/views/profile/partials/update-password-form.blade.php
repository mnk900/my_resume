<div class="card border-0 shadow-sm rounded-3 bg-white mb-4">
    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center">
        <i class="fa-solid fa-key text-warning me-2 fs-5"></i>
        <div>
            <h5 class="fw-bold text-dark mb-0">Update Password</h5>
            <small class="text-muted">Ensure your account is using a long, random password to stay secure.</small>
        </div>
    </div>
    <div class="card-body p-4">
        @if (session('status') === 'password-updated')
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center mb-4" role="alert">
                <i class="fa-solid fa-circle-check me-2 fs-5"></i>
                <div>Your account password has been updated successfully!</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form method="post" action="{{ route('password.update') }}">
            @csrf
            @method('put')

            <div class="mb-3">
                <label for="update_password_current_password" class="form-label fw-semibold">Current Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="fa-solid fa-lock text-secondary"></i></span>
                    <input id="update_password_current_password" name="current_password" type="password" class="form-control @error('current_password', 'updatePassword') is-invalid @enderror" autocomplete="current-password" placeholder="Enter current password">
                </div>
                @error('current_password', 'updatePassword')
                    <div class="text-danger small mt-1"><strong>{{ $message }}</strong></div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="update_password_password" class="form-label fw-semibold">New Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="fa-solid fa-key text-secondary"></i></span>
                    <input id="update_password_password" name="password" type="password" class="form-control @error('password', 'updatePassword') is-invalid @enderror" autocomplete="new-password" placeholder="Enter new password">
                </div>
                @error('password', 'updatePassword')
                    <div class="text-danger small mt-1"><strong>{{ $message }}</strong></div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="update_password_password_confirmation" class="form-label fw-semibold">Confirm New Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="fa-solid fa-check-double text-secondary"></i></span>
                    <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="form-control @error('password_confirmation', 'updatePassword') is-invalid @enderror" autocomplete="new-password" placeholder="Confirm new password">
                </div>
                @error('password_confirmation', 'updatePassword')
                    <div class="text-danger small mt-1"><strong>{{ $message }}</strong></div>
                @enderror
            </div>

            <div class="d-flex align-items-center gap-3">
                <button type="submit" class="btn btn-primary rounded-pill px-4 fw-semibold shadow-sm">
                    <i class="fa-solid fa-shield-halved me-1"></i> Update Password
                </button>
            </div>
        </form>
    </div>
</div>

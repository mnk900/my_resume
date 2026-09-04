<div class="card border-0 shadow-sm rounded-3 bg-white mb-4 border-start border-4 border-danger">
    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center">
        <i class="fa-solid fa-triangle-exclamation text-danger me-2 fs-5"></i>
        <div>
            <h5 class="fw-bold text-danger mb-0">Delete Account</h5>
            <small class="text-muted">Permanently erase your account, portfolio, and all associated profile data.</small>
        </div>
    </div>
    <div class="card-body p-4">
        <p class="text-secondary small mb-3">
            Once your account is deleted, all of its resources, portfolio data, candidate applications, and settings will be permanently removed.
        </p>

        <button type="button" class="btn btn-outline-danger btn-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
            <i class="fa-solid fa-trash-can me-1"></i> Delete Account
        </button>

        <!-- Delete Account Modal -->
        <div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow">
                    <form method="post" action="{{ route('profile.destroy') }}">
                        @csrf
                        @method('delete')
                        <div class="modal-header border-bottom">
                            <h5 class="modal-title text-danger fw-bold" id="deleteAccountModalLabel">
                                <i class="fa-solid fa-triangle-exclamation me-1"></i> Confirm Account Deletion
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body py-4">
                            <p class="text-secondary small mb-3">
                                Are you sure you want to permanently delete your account? Please enter your current password to confirm deletion.
                            </p>
                            <div class="mb-3">
                                <label for="delete_password" class="form-label fw-semibold">Current Password</label>
                                <input id="delete_password" name="password" type="password" class="form-control @error('password', 'userDeletion') is-invalid @enderror" placeholder="Enter password to confirm" required>
                                @error('password', 'userDeletion')
                                    <div class="text-danger small mt-1"><strong>{{ $message }}</strong></div>
                                @enderror
                            </div>
                        </div>
                        <div class="modal-footer border-top bg-light">
                            <button type="button" class="btn btn-secondary btn-sm rounded-pill" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger btn-sm rounded-pill fw-semibold">
                                <i class="fa-solid fa-trash-can me-1"></i> Permanently Delete Account
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

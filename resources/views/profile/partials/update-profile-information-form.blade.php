<div class="card border-0 shadow-sm rounded-3 bg-white mb-4">
    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center">
        <i class="fa-solid fa-id-card text-primary me-2 fs-5"></i>
        <div>
            <h5 class="fw-bold text-dark mb-0">Profile Information</h5>
            <small class="text-muted">Update your account's name and email address.</small>
        </div>
    </div>
    <div class="card-body p-4">
        @if (session('status') === 'profile-updated')
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center mb-4" role="alert">
                <i class="fa-solid fa-circle-check me-2 fs-5"></i>
                <div>Your account profile information has been saved!</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form id="send-verification" method="post" action="{{ Route::has('verification.send') ? route('verification.send') : (Route::has('verification.resend') ? route('verification.resend') : '#') }}">
            @csrf
        </form>

        <form method="post" action="{{ route('profile.update') }}">
            @csrf
            @method('patch')

            <div class="mb-3">
                <label for="name" class="form-label fw-semibold">Full Name</label>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="fa-solid fa-user text-secondary"></i></span>
                    <input id="name" name="name" type="text" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
                </div>
                @error('name')
                    <div class="text-danger small mt-1"><strong>{{ $message }}</strong></div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="email" class="form-label fw-semibold">Email Address</label>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="fa-solid fa-envelope text-secondary"></i></span>
                    <input id="email" name="email" type="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required autocomplete="username">
                </div>
                @error('email')
                    <div class="text-danger small mt-1"><strong>{{ $message }}</strong></div>
                @enderror

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div class="mt-2 text-warning small">
                        Your email address is unverified.
                        <button form="send-verification" class="btn btn-link btn-sm p-0 text-decoration-underline text-warning">Click here to re-send verification email.</button>
                    </div>
                    @if (session('status') === 'verification-link-sent')
                        <div class="mt-2 text-success small">A new verification link has been sent to your email address.</div>
                    @endif
                @endif
            </div>

            <button type="submit" class="btn btn-primary rounded-pill px-4 fw-semibold shadow-sm">
                <i class="fa-solid fa-floppy-disk me-1"></i> Save Information
            </button>
        </form>
    </div>
</div>

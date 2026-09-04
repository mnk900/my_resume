<x-guest-layout>
    <div class="text-center mb-4">
        <div class="badge bg-danger-subtle text-danger border border-danger-subtle mb-2 px-3 py-1 rounded-pill" style="font-size: 0.75rem;">
            <i class="fa-solid fa-shield-halved me-1"></i> ADMIN USER PROVISIONING
        </div>
        <h3 class="fw-bold text-dark mb-1">Register Portfolio Candidate</h3>
        <p class="text-muted small mb-0">Create a new user account for candidate portfolios.</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        {{-- Name --}}
        <div class="mb-3">
            <label for="name" class="form-label fw-semibold">{{ __('Full Name') }}</label>
            <input id="name" type="text" class="form-control py-2 @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="e.g. Alex Mercer">
            @error('name')
                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>

        {{-- Email --}}
        <div class="mb-3">
            <label for="email" class="form-label fw-semibold">{{ __('Email Address') }}</label>
            <input id="email" type="email" class="form-control py-2 @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="e.g. alex@example.com">
            @error('email')
                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>

        {{-- Password --}}
        <div class="mb-3">
            <label for="password" class="form-label fw-semibold">{{ __('Password') }}</label>
            <input id="password" type="password" class="form-control py-2 @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
            @error('password')
                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>

        {{-- Confirm Password --}}
        <div class="mb-4">
            <label for="password-confirm" class="form-label fw-semibold">{{ __('Confirm Password') }}</label>
            <input id="password-confirm" type="password" class="form-control py-2 @error('password_confirmation') is-invalid @enderror" name="password_confirmation" required autocomplete="new-password">
            @error('password_confirmation')
                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary w-100 py-2 rounded-pill fw-bold shadow-sm mb-3">
            <i class="fa-solid fa-user-plus me-1"></i> {{ __('Create Portfolio User') }}
        </button>

        <div class="text-center pt-2 border-top">
            <a class="text-decoration-none fw-semibold small text-secondary" href="{{ route('admin.index') }}">
                <i class="fa-solid fa-arrow-left me-1"></i> {{ __('Return to Admin Dashboard') }}
            </a>
        </div>
    </form>
</x-guest-layout>

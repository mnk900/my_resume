<x-guest-layout>
    <div class="text-center mb-4">
        <h3 class="fw-bold text-dark mb-1">Welcome Back</h3>
        <p class="text-muted small">Sign in to your MyResume.cloud professional account.</p>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label fw-semibold">{{ __('Email Address') }}</label>
            <input id="email" type="email" class="form-control py-2 @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="name@company.com">
            @error('email')
                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>

        <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <label for="password" class="form-label fw-semibold mb-0">{{ __('Password') }}</label>
                @if (Route::has('password.request'))
                    <a class="small text-decoration-none" href="{{ route('password.request') }}">
                        {{ __('Forgot Password?') }}
                    </a>
                @endif
            </div>
            <input id="password" type="password" class="form-control py-2 @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
            @error('password')
                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>

        <div class="mb-4 form-check">
            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
            <label class="form-check-label text-secondary small" for="remember">
                {{ __('Remember this device') }}
            </label>
        </div>

        <button type="submit" class="btn btn-primary w-100 py-2 rounded-pill fw-bold shadow-sm mb-3">
            {{ __('Sign In to Dashboard') }}
        </button>

        <div class="text-center pt-2 border-top">
            <span class="text-muted small">New to MyResume.cloud?</span>
            <a href="{{ route('register') }}" class="text-decoration-none fw-semibold ms-1">Create an account</a>
        </div>
    </form>
</x-guest-layout>

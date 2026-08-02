<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        {{-- Name --}}
        <div class="mb-3">
            <label for="name" class="form-label fw-semibold">{{ __('Full Name') }}</label>
            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="e.g. John Doe">
            @error('name')
                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>



        {{-- Email --}}
        <div class="mb-3">
            <label for="email" class="form-label fw-semibold">{{ __('Email Address') }}</label>
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="e.g. john@example.com">
            @error('email')
                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>

        {{-- Password --}}
        <div class="mb-3">
            <label for="password" class="form-label fw-semibold">{{ __('Password') }}</label>
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
            @error('password')
                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>

        {{-- Confirm Password --}}
        <div class="mb-4">
            <label for="password-confirm" class="form-label fw-semibold">{{ __('Confirm Password') }}</label>
            <input id="password-confirm" type="password" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" required autocomplete="new-password">
            @error('password_confirmation')
                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>

        <div class="d-flex justify-content-between align-items-center mb-0">
            <a class="text-decoration-none" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>
            <button type="submit" class="btn btn-primary px-4">
                {{ __('Register') }}
            </button>
        </div>
    </form>
</x-guest-layout>

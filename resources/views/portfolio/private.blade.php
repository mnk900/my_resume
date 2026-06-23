@extends('portfolio.themes.classic')

@section('title', $user->name . ' - Private Portfolio')

@section('content')
<div class="container py-5 d-flex align-items-center justify-content-center" style="min-height: 90vh;">
    <div class="card shadow-lg border-0 rounded-4 overflow-hidden p-4 p-md-5 text-center bg-white" style="max-width: 550px; width: 100%;">
        <div class="mb-4">
            @if($portfolio->profile_image)
                <img src="{{ Storage::url($portfolio->profile_image) }}" alt="{{ $user->name }}" class="rounded-circle shadow" style="width: 110px; height: 110px; object-fit: cover; border: 4px solid #f1f5f9;">
            @else
                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&size=150&background=1e293b&color=fff" alt="{{ $user->name }}" class="rounded-circle shadow" style="width: 110px; height: 110px; border: 4px solid #f1f5f9;">
            @endif
        </div>

        <h3 class="fw-bold text-dark mb-1">{{ $user->name }}</h3>
        <p class="text-muted mb-4 small text-uppercase tracking-wider fw-semibold">{{ $portfolio->position ?? 'Professional' }}</p>

        <hr class="my-4" style="border-color: #f1f5f9;">

        <div class="mb-4">
            <span class="d-inline-block bg-warning-subtle text-warning-emphasis px-3 py-2 rounded-pill small fw-bold mb-3">
                <i class="bi bi-lock-fill me-1"></i> Private Profile
            </span>
            <h5 class="fw-bold text-dark">This Portfolio is Private</h5>
            <p class="text-secondary small px-3">Only verified connections of {{ $user->name }} can view this portfolio's sections, achievements, and work experience.</p>
        </div>

        <div class="mt-4">
            @guest
                <div class="alert alert-light border small text-muted mb-4">
                    Please log in to your account to send a connection request and view this profile.
                </div>
                <a href="{{ route('login') }}" class="btn btn-dark btn-lg w-100 rounded-pill shadow-sm">
                    <i class="bi bi-box-arrow-in-right me-1"></i> Log In to Connect
                </a>
            @else
                @if(Auth::id() === $user->id)
                    <div class="alert alert-info border small text-center mb-0">
                        This is your own portfolio. You can set it to public in the <a href="{{ route('portfolio.edit') }}" class="fw-bold">Settings</a> tab of your CMS.
                    </div>
                @else
                    @if(!$connection)
                        <form action="{{ route('connections.request', $user->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-lg w-100 rounded-pill shadow-sm fw-bold">
                                <i class="bi bi-person-plus-fill me-1"></i> Send Connection Request
                            </button>
                        </form>
                    @elseif($connection->status === 'pending')
                        @if($connection->sender_id === Auth::id())
                            <div class="alert alert-light border text-center mb-3 small">
                                <i class="bi bi-clock-history text-warning me-1"></i> Connection request sent and is pending approval.
                            </div>
                            <form action="{{ route('connections.cancel', $connection->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger btn-sm w-100 rounded-pill">
                                    Cancel Request
                                </button>
                            </form>
                        @else
                            <div class="alert alert-light border text-center mb-3 small">
                                <i class="bi bi-person-exclamation text-primary me-1"></i> {{ $user->name }} sent you a connection request.
                            </div>
                            <div class="row g-2">
                                <div class="col-6">
                                    <form action="{{ route('connections.accept', $connection->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-success w-100 rounded-pill">
                                            Accept
                                        </button>
                                    </form>
                                </div>
                                <div class="col-6">
                                    <form action="{{ route('connections.reject', $connection->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-secondary w-100 rounded-pill">
                                            Ignore
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endif
                    @endif
                @endif
            @endguest
        </div>
    </div>
</div>
@endsection

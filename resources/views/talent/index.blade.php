@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row align-items-center mb-4">
        <div class="col-md-8">
            <h1 class="h2 fw-bold text-dark mb-1"><i class="fa-solid fa-user-gear me-2 text-primary"></i> Candidate Discovery & Find Talent</h1>
            <p class="text-secondary mb-0">Search verified portfolio database to discover professionals, engineers, designers, and managers.</p>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <form action="{{ route('talent.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0" placeholder="Search by name, position, keywords..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <input type="text" name="skill" class="form-control" placeholder="Skill (e.g. PHP, Python, React)" value="{{ request('skill') }}">
                </div>
                <div class="col-md-2">
                    <input type="text" name="city" class="form-control" placeholder="City" value="{{ request('city') }}">
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100 fw-semibold">Search Talent</button>
                    <a href="{{ route('talent.index') }}" class="btn btn-outline-secondary" title="Reset"><i class="fa-solid fa-rotate-left"></i></a>
                </div>
            </form>
        </div>
    </div>

    <!-- Candidate Cards Grid -->
    <div class="row g-4">
        @forelse($candidates as $candidate)
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm rounded-4 p-4 d-flex flex-column">
                <div class="d-flex align-items-center gap-3 mb-3">
                    @if($candidate->portfolio && $candidate->portfolio->profile_image)
                        <img src="{{ asset('storage/' . $candidate->portfolio->profile_image) }}" alt="{{ $candidate->name }}" class="rounded-circle border p-1" style="width: 60px; height: 60px; object-fit: cover;">
                    @else
                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold fs-4" style="width: 60px; height: 60px;">
                            {{ strtoupper(substr($candidate->name, 0, 1)) }}
                        </div>
                    @endif
                    <div>
                        <h5 class="fw-bold mb-0 text-dark">{{ $candidate->name }}</h5>
                        <span class="text-primary small fw-semibold">{{ $candidate->portfolio->position ?? 'Professional Candidate' }}</span>
                        <span class="d-block text-muted small"><i class="fa-solid fa-location-dot me-1 text-danger"></i> {{ $candidate->portfolio->city ?? 'Location N/A' }}</span>
                    </div>
                </div>

                <p class="text-secondary small mb-3 flex-grow-1">
                    {{ Str::limit(strip_tags($candidate->portfolio->description ?? 'No bio detailed.'), 110) }}
                </p>

                @if($candidate->portfolio && $candidate->portfolio->skills->isNotEmpty())
                <div class="mb-3">
                    @foreach($candidate->portfolio->skills->take(3) as $sk)
                        <span class="badge bg-light text-secondary border me-1">{{ $sk->name }}</span>
                    @endforeach
                </div>
                @endif

                <div class="pt-3 border-top d-flex justify-content-between align-items-center mt-auto flex-wrap gap-2">
                    <a href="{{ route('portfolio.show', $candidate->username) }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-3">View Portfolio</a>
                    <div class="d-flex gap-1">
                        @auth
                            @if(Auth::id() !== $candidate->id)
                                @php
                                    $conn = \App\Models\Connection::where(function($q) use ($candidate) {
                                        $q->where('sender_id', Auth::id())->where('receiver_id', $candidate->id);
                                    })->orWhere(function($q) use ($candidate) {
                                        $q->where('sender_id', $candidate->id)->where('receiver_id', Auth::id());
                                    })->first();
                                @endphp
                                @if($conn && $conn->status === 'accepted')
                                    <span class="badge bg-success-subtle text-success border px-2 py-2 me-1" style="font-size: 0.72rem;"><i class="fa-solid fa-check me-1"></i> Connected</span>
                                    <a href="{{ route('messages.index', ['user_id' => $candidate->id]) }}" class="btn btn-sm btn-primary rounded-pill px-2" title="Message"><i class="fa-solid fa-comments me-1"></i> Message</a>
                                @elseif($conn && $conn->status === 'pending')
                                    <span class="badge bg-secondary-subtle text-secondary border px-2 py-2 me-1" style="font-size: 0.72rem;">Pending</span>
                                    <a href="{{ route('messages.index', ['user_id' => $candidate->id]) }}" class="btn btn-sm btn-outline-primary rounded-pill px-2" title="Message"><i class="fa-solid fa-comments me-1"></i> Message</a>
                                @else
                                    <form action="{{ route('connections.request', $candidate->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-success rounded-pill px-2" title="Connect"><i class="fa-solid fa-user-plus me-1"></i> Connect</button>
                                    </form>
                                    <a href="{{ route('messages.index', ['user_id' => $candidate->id]) }}" class="btn btn-sm btn-outline-primary rounded-pill px-2" title="Message"><i class="fa-solid fa-comments me-1"></i> Message</a>
                                @endif
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="btn btn-sm btn-outline-success rounded-pill px-2" title="Connect"><i class="fa-solid fa-user-plus me-1"></i> Connect</a>
                            <a href="{{ route('login') }}" class="btn btn-sm btn-outline-primary rounded-pill px-2" title="Message"><i class="fa-solid fa-comments me-1"></i> Message</a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 text-center py-5">
                <div class="card-body">
                    <i class="fa-solid fa-users-slash fa-3x text-muted mb-3"></i>
                    <h4 class="fw-bold text-dark">No Talent Profiles Found</h4>
                    <p class="text-muted">No candidate portfolios match your criteria.</p>
                </div>
            </div>
        </div>
        @endforelse
    </div>

    <div class="d-flex justify-content-center mt-5">
        {{ $candidates->withQueryString()->links() }}
    </div>
</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 fw-bold text-dark mb-1"><i class="fa-solid fa-bell me-2 text-primary"></i> System Notifications</h1>
            <p class="text-secondary mb-0">Track application status updates, interview invites, and platform alerts.</p>
        </div>
        <form action="{{ route('notifications.read-all') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-outline-secondary btn-sm rounded-pill"><i class="fa-solid fa-check-double me-1"></i> Mark All Read</button>
        </form>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="list-group list-group-flush">
                @forelse($notifications as $n)
                <div class="list-group-item p-4 border-bottom {{ $n->is_read ? 'bg-white' : 'bg-light' }}">
                    <div class="d-flex justify-content-between align-items-start mb-1">
                        <h6 class="fw-bold text-dark mb-0">{{ $n->title }}</h6>
                        <span class="text-muted small">{{ $n->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="text-secondary small mb-2">{{ $n->message }}</p>

                    <div class="d-flex gap-2">
                        @if($n->action_url)
                            <a href="{{ $n->action_url }}" class="btn btn-sm btn-primary rounded-pill px-3">View Details</a>
                        @endif

                        @if(!$n->is_read)
                            <form action="{{ route('notifications.read', $n->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-light border rounded-pill px-3">Mark Read</button>
                            </form>
                        @endif
                    </div>
                </div>
                @empty
                <div class="text-center py-5 text-muted">
                    <i class="fa-solid fa-bell-slash fa-3x text-muted mb-3 d-block"></i>
                    No notifications in your inbox.
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

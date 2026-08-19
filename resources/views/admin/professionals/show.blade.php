@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <a href="{{ route('admin.professionals.index') }}" class="small text-secondary text-decoration-none fw-semibold">&larr; Back to Professionals Directory</a>
        <h1 class="h3 fw-bold text-dark mt-1 mb-0">{{ $user->name }} &mdash; Professional Inspector</h1>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('portfolio.show', $user->username) }}" target="_blank" class="btn btn-outline-primary btn-sm rounded-pill"><i class="fa-solid fa-globe me-1"></i> Public Portfolio</a>
        <form action="{{ route('admin.professionals.suspend', $user->id) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-sm {{ $user->isSuspended() ? 'btn-success' : 'btn-outline-danger' }} rounded-pill" onclick="return confirm('Change suspension status?');">
                <i class="fa-solid fa-ban me-1"></i> {{ $user->isSuspended() ? 'Unsuspend Account' : 'Suspend Account' }}
            </button>
        </form>
    </div>
</div>

<div class="row g-4">
    <!-- User Profile Quick Card -->
    <div class="col-md-4">
        <div class="card border-0 shadow-sm bg-white p-3 mb-4 text-center">
            <div class="rounded-circle text-white d-flex align-items-center justify-content-center fw-bold fs-3 mx-auto mb-3" style="width: 64px; height: 64px; background-color: var(--brand-primary);">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <h5 class="fw-bold text-dark mb-0">{{ $user->name }}</h5>
            <p class="text-muted small mb-2">&#064;{{ $user->username }} &bull; {{ $user->email }}</p>
            
            <div class="d-flex justify-content-center gap-1 mb-3">
                @if($user->email_verified_at)
                    <span class="badge bg-success-subtle text-success">Email Verified</span>
                @else
                    <span class="badge bg-warning-subtle text-dark">Unverified</span>
                @endif
                @if($user->isSuspended())
                    <span class="badge bg-danger">Suspended</span>
                @else
                    <span class="badge bg-success-subtle text-success">Active</span>
                @endif
            </div>

            <div class="border-top pt-3 text-start small">
                <div class="d-flex justify-content-between mb-1"><span class="text-muted">Account ID:</span><span class="fw-bold text-dark">#{{ $user->id }}</span></div>
                <div class="d-flex justify-content-between mb-1"><span class="text-muted">Joined Date:</span><span class="fw-bold text-dark">{{ $user->created_at->format('M d, Y') }}</span></div>
                <div class="d-flex justify-content-between mb-1"><span class="text-muted">User Type:</span><span class="fw-bold text-dark">{{ ucfirst($user->user_type ?? 'Professional') }}</span></div>
                <div class="d-flex justify-content-between"><span class="text-muted">Admin Role:</span><span class="fw-bold text-primary">{{ ucfirst($user->admin_role ?? 'None') }}</span></div>
            </div>
        </div>

        <!-- System Audit Log for this User -->
        <div class="card border-0 shadow-sm bg-white p-3">
            <h6 class="fw-bold text-dark mb-3"><i class="fa-solid fa-list-check me-2 text-primary"></i> Administrative Log History</h6>
            @forelse($userAuditLogs as $log)
            <div class="border-bottom pb-2 mb-2 small">
                <div class="fw-bold text-dark">{{ $log->action }}</div>
                <span class="text-muted" style="font-size: 0.72rem;">By {{ $log->user->name ?? 'System' }} &bull; {{ $log->created_at->diffForHumans() }}</span>
            </div>
            @empty
            <p class="text-muted small mb-0">No administrative logs for this user.</p>
            @endforelse
        </div>
    </div>

    <!-- Portfolio & Application Details -->
    <div class="col-md-8">
        <!-- Portfolio Specs -->
        <div class="card border-0 shadow-sm bg-white p-3 mb-4">
            <h6 class="fw-bold text-dark mb-3"><i class="fa-solid fa-id-card text-primary me-2"></i> Portfolio Profile Overview</h6>
            @if($user->portfolio)
                <div class="row g-3 small">
                    <div class="col-md-6">
                        <span class="text-muted d-block">Portfolio Title</span>
                        <strong class="text-dark">{{ $user->portfolio->title }}</strong>
                    </div>
                    <div class="col-md-6">
                        <span class="text-muted d-block">Designation</span>
                        <strong class="text-dark">{{ $user->portfolio->position ?? 'N/A' }}</strong>
                    </div>
                    <div class="col-md-4">
                        <span class="text-muted d-block">Theme</span>
                        <strong class="text-dark">{{ ucfirst($user->portfolio->theme) }}</strong>
                    </div>
                    <div class="col-md-4">
                        <span class="text-muted d-block">Visibility</span>
                        <span class="badge bg-secondary-subtle text-dark">{{ $user->portfolio->is_public ? 'Public' : 'Private' }}</span>
                    </div>
                    <div class="col-md-4">
                        <span class="text-muted d-block">Status</span>
                        <span class="badge bg-success-subtle text-success">{{ $user->portfolio->is_active ? 'Active' : 'Inactive' }}</span>
                    </div>
                    <div class="col-12 border-top pt-2">
                        <span class="text-muted d-block">Professional Summary</span>
                        <p class="text-dark mb-0 lh-normal">{{ $user->portfolio->description ?? 'No summary provided.' }}</p>
                    </div>
                </div>
            @else
                <p class="text-muted small mb-0">No portfolio generated for this user.</p>
            @endif
        </div>

        <!-- Submitted Applications -->
        <div class="card border-0 shadow-sm bg-white p-3">
            <h6 class="fw-bold text-dark mb-3"><i class="fa-solid fa-paper-plane text-warning me-2"></i> Submitted Job Applications ({{ $user->jobApplications->count() }})</h6>
            @forelse($user->jobApplications as $app)
            <div class="p-3 border rounded-3 bg-light mb-2 d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fw-bold mb-0 text-dark small">{{ $app->opportunity->title ?? 'Position' }}</h6>
                    <span class="text-muted" style="font-size: 0.72rem;">Applied on {{ $app->created_at->format('M d, Y') }}</span>
                </div>
                <span class="badge bg-primary-subtle text-primary border border-primary">{{ ucfirst($app->status) }}</span>
            </div>
            @empty
            <p class="text-muted small mb-0">User has not submitted any job applications yet.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection

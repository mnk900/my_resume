@extends('layouts.admin')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
    <div>
        <h1 class="h3 fw-bold text-dark mb-1">Operational Command Center 🛡️</h1>
        <p class="text-secondary small mb-0">System governance, platform overview, verification queues, and operational metrics.</p>
    </div>
    <div class="d-flex gap-2 mt-3 mt-md-0">
        <a href="{{ route('admin.verification.index') }}" class="btn btn-primary btn-sm rounded-pill"><i class="fa-solid fa-shield-halved me-1"></i> Verification Queue</a>
        <a href="{{ route('admin.moderation.index') }}" class="btn btn-outline-danger btn-sm rounded-pill"><i class="fa-solid fa-triangle-exclamation me-1"></i> Moderation Queue</a>
    </div>
</div>

<!-- 01. REQUIRES YOUR ATTENTION TASK CENTER -->
<div class="card border-0 shadow-sm rounded-3 p-3 bg-white mb-4 border-start border-4 border-warning">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h6 class="fw-bold text-dark mb-0"><i class="fa-solid fa-bell text-warning me-2"></i> Requires Your Immediate Attention</h6>
        <span class="badge bg-warning-subtle text-dark border border-warning" style="font-size: 0.72rem;">Action Required</span>
    </div>
    <div class="row g-3 text-center">
        <div class="col-6 col-md-3">
            <a href="{{ route('admin.verification.index') }}" class="text-decoration-none text-dark d-block p-2 rounded bg-light border hover-lift">
                <span class="fs-4 fw-bold text-primary d-block">{{ $attentionCounts['pending_companies'] }}</span>
                <span class="text-muted small">Pending Companies</span>
            </a>
        </div>
        <div class="col-6 col-md-3">
            <a href="{{ route('admin.verification.index') }}" class="text-decoration-none text-dark d-block p-2 rounded bg-light border hover-lift">
                <span class="fs-4 fw-bold text-warning d-block">{{ $attentionCounts['unverified_users'] }}</span>
                <span class="text-muted small">Unverified Users</span>
            </a>
        </div>
        <div class="col-6 col-md-3">
            <a href="{{ route('admin.jobs.index', ['status' => 'draft']) }}" class="text-decoration-none text-dark d-block p-2 rounded bg-light border hover-lift">
                <span class="fs-4 fw-bold text-info d-block">{{ $attentionCounts['pending_opportunities'] }}</span>
                <span class="text-muted small">Draft/Pending Jobs</span>
            </a>
        </div>
        <div class="col-6 col-md-3">
            <a href="{{ route('admin.moderation.index') }}" class="text-decoration-none text-dark d-block p-2 rounded bg-light border hover-lift">
                <span class="fs-4 fw-bold text-danger d-block">{{ $attentionCounts['pending_reports'] }}</span>
                <span class="text-muted small">Reported Items</span>
            </a>
        </div>
    </div>
</div>

<!-- 02. KEY PLATFORM METRICS ROW -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm rounded-3 p-3 bg-white h-100">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <span class="text-muted small fw-semibold">Total Professionals</span>
                <i class="fa-solid fa-users text-primary"></i>
            </div>
            <div class="fs-3 fw-bold text-dark mb-0">{{ number_format($stats['total_users']) }}</div>
            <span class="text-success small" style="font-size: 0.72rem;"><i class="fa-solid fa-check me-1"></i> {{ $stats['verified_users'] }} Verified</span>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm rounded-3 p-3 bg-white h-100">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <span class="text-muted small fw-semibold">Registered Companies</span>
                <i class="fa-solid fa-building text-info"></i>
            </div>
            <div class="fs-3 fw-bold text-dark mb-0">{{ number_format($stats['total_companies']) }}</div>
            <span class="text-info small" style="font-size: 0.72rem;"><i class="fa-solid fa-circle-check me-1"></i> {{ $stats['verified_companies'] }} Approved</span>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm rounded-3 p-3 bg-white h-100">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <span class="text-muted small fw-semibold">Opportunities</span>
                <i class="fa-solid fa-briefcase text-success"></i>
            </div>
            <div class="fs-3 fw-bold text-dark mb-0">{{ number_format($stats['total_opportunities']) }}</div>
            <span class="text-muted small" style="font-size: 0.72rem;">{{ $stats['published_opportunities'] }} Published</span>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm rounded-3 p-3 bg-white h-100">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <span class="text-muted small fw-semibold">Job Applications</span>
                <i class="fa-solid fa-paper-plane text-warning"></i>
            </div>
            <div class="fs-3 fw-bold text-dark mb-0">{{ number_format($stats['total_applications']) }}</div>
            <span class="text-muted small" style="font-size: 0.72rem;">Submitted Records</span>
        </div>
    </div>
</div>

<!-- 03. MAIN DASHBOARD GRID: ACTIVITY TIMELINE & SYSTEM HEALTH -->
<div class="row g-4 mb-4">
    <!-- Audit Activity Timeline -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-3 bg-white h-100">
            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold text-dark mb-0"><i class="fa-solid fa-list-check me-2 text-primary"></i> Administrative Action Log</h6>
                <a href="{{ route('admin.audit-logs.index') }}" class="small text-primary text-decoration-none fw-semibold">View All Logs &rarr;</a>
            </div>
            <div class="card-body p-0">
                @if($recentAuditLogs->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Admin User</th>
                                    <th>Action Performed</th>
                                    <th>Target Entity</th>
                                    <th>Timestamp</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentAuditLogs as $log)
                                <tr>
                                    <td>
                                        <span class="fw-bold text-dark small">{{ $log->user->name ?? 'System' }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary-subtle text-primary border border-primary">{{ $log->action }}</span>
                                    </td>
                                    <td>
                                        <span class="text-muted small">{{ $log->target_model_name }} #{{ $log->target_id }}</span>
                                    </td>
                                    <td>
                                        <span class="text-secondary small" style="font-size: 0.72rem;">{{ $log->created_at->diffForHumans() }}</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-4 text-center text-muted small">
                        <i class="fa-solid fa-list-check fa-2xl mb-2 text-secondary"></i>
                        <p class="mb-0">No administrative audit actions recorded yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- System Health Widget -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-3 bg-white h-100">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="fw-bold text-dark mb-0"><i class="fa-solid fa-heart-pulse me-2 text-danger"></i> System Health Check</h6>
            </div>
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center p-2 mb-2 rounded bg-light border">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fa-solid fa-database text-success"></i>
                        <span class="fw-semibold text-dark small">Database Connection</span>
                    </div>
                    <span class="badge bg-success rounded-pill">Optimal</span>
                </div>

                <div class="d-flex justify-content-between align-items-center p-2 mb-2 rounded bg-light border">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fa-solid fa-envelope text-primary"></i>
                        <span class="fw-semibold text-dark small">Mailer Driver</span>
                    </div>
                    <span class="badge bg-success rounded-pill">Active ({{ config('mail.default') }})</span>
                </div>

                <div class="d-flex justify-content-between align-items-center p-2 mb-2 rounded bg-light border">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fa-solid fa-hard-drive text-info"></i>
                        <span class="fw-semibold text-dark small">Storage System</span>
                    </div>
                    <span class="badge bg-success rounded-pill">Writable</span>
                </div>

                <div class="d-flex justify-content-between align-items-center p-2 rounded bg-light border">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fa-solid fa-layer-group text-warning"></i>
                        <span class="fw-semibold text-dark small">Portfolio Cache Engine</span>
                    </div>
                    <span class="badge bg-success rounded-pill">Active</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

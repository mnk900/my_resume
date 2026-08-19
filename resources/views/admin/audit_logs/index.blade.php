@extends('layouts.admin')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
    <div>
        <h1 class="h3 fw-bold text-dark mb-1"><i class="fa-solid fa-list-check me-2 text-primary"></i> Administrative Audit Log</h1>
        <p class="text-secondary small mb-0">Immutable system action logs recording administrator events, targets, IP addresses, and timestamps.</p>
    </div>
</div>

<!-- Filters Bar -->
<div class="card border-0 shadow-sm p-3 bg-white mb-4">
    <form action="{{ route('admin.audit-logs.index') }}" method="GET" class="row g-3 align-items-center">
        <div class="col-md-8">
            <input type="text" name="search" class="form-control form-control-sm" placeholder="Search by action name or IP address..." value="{{ request('search') }}">
        </div>
        <div class="col-md-4 text-md-end">
            <button type="submit" class="btn btn-sm btn-primary rounded-pill px-3 me-1">Filter</button>
            <a href="{{ route('admin.audit-logs.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">Reset</a>
        </div>
    </form>
</div>

<!-- Audit Log Table -->
<div class="card border-0 shadow-sm bg-white">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Log ID</th>
                        <th>Administrator</th>
                        <th>Action Performed</th>
                        <th>Target Model & ID</th>
                        <th>IP Address</th>
                        <th>Timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($auditLogs as $log)
                    <tr>
                        <td><span class="fw-bold text-muted small">#{{ $log->id }}</span></td>
                        <td>
                            <strong class="d-block text-dark small">{{ $log->user->name ?? 'System Event' }}</strong>
                            <span class="text-muted" style="font-size: 0.72rem;">{{ $log->user->email ?? '' }}</span>
                        </td>
                        <td>
                            <span class="badge bg-primary-subtle text-primary border border-primary font-monospace">{{ $log->action }}</span>
                        </td>
                        <td>
                            <span class="text-dark small">{{ $log->target_model_name }} {{ $log->target_id ? '#' . $log->target_id : '' }}</span>
                        </td>
                        <td>
                            <span class="text-muted small font-monospace">{{ $log->ip_address ?? '127.0.0.1' }}</span>
                        </td>
                        <td>
                            <span class="text-secondary small">{{ $log->created_at->format('M d, Y H:i:s') }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">No audit logs recorded yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3 border-top">
            {{ $auditLogs->links() }}
        </div>
    </div>
</div>
@endsection

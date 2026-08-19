@extends('layouts.admin')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
    <div>
        <h1 class="h3 fw-bold text-dark mb-1"><i class="fa-solid fa-paper-plane me-2 text-warning"></i> System Applications Activity Log</h1>
        <p class="text-secondary small mb-0">System-wide visibility of candidate applications, stage progression, and hiring activity.</p>
    </div>
</div>

<!-- Data Table -->
<div class="card border-0 shadow-sm bg-white">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Candidate</th>
                        <th>Position / Opportunity</th>
                        <th>Hiring Company</th>
                        <th>Application Stage</th>
                        <th>Submitted Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($applications as $app)
                    <tr>
                        <td>
                            <strong class="d-block text-dark small">{{ $app->user->name }}</strong>
                            <span class="text-muted" style="font-size: 0.72rem;">{{ $app->user->email }}</span>
                        </td>
                        <td>
                            <span class="text-dark small fw-semibold">{{ $app->opportunity->title ?? 'Position' }}</span>
                        </td>
                        <td>
                            <span class="text-muted small">{{ $app->opportunity->company->name ?? 'Organization' }}</span>
                        </td>
                        <td>
                            <span class="badge bg-primary-subtle text-primary border border-primary">{{ ucfirst($app->status) }}</span>
                        </td>
                        <td>
                            <span class="text-muted small">{{ $app->created_at->format('M d, Y') }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">No applications submitted yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3 border-top">
            {{ $applications->links() }}
        </div>
    </div>
</div>
@endsection

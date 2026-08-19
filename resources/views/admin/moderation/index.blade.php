@extends('layouts.admin')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
    <div>
        <h1 class="h3 fw-bold text-dark mb-1"><i class="fa-solid fa-triangle-exclamation me-2 text-danger"></i> Content Moderation Center</h1>
        <p class="text-secondary small mb-0">Community reports queue, policy violations resolution, and moderation workflow.</p>
    </div>
</div>

<!-- Filters Bar -->
<div class="card border-0 shadow-sm p-3 bg-white mb-4">
    <form action="{{ route('admin.moderation.index') }}" method="GET" class="row g-3 align-items-center">
        <div class="col-md-6">
            <select name="status" class="form-select form-select-sm">
                <option value="">All Report Statuses</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending Review</option>
                <option value="reviewed" {{ request('status') === 'reviewed' ? 'selected' : '' }}>Under Review</option>
                <option value="actioned" {{ request('status') === 'actioned' ? 'selected' : '' }}>Action Taken</option>
                <option value="dismissed" {{ request('status') === 'dismissed' ? 'selected' : '' }}>Dismissed</option>
            </select>
        </div>
        <div class="col-md-6 text-md-end">
            <button type="submit" class="btn btn-sm btn-primary rounded-pill px-3 me-1">Filter</button>
            <a href="{{ route('admin.moderation.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">Reset</a>
        </div>
    </form>
</div>

<!-- Moderation Data Table -->
<div class="card border-0 shadow-sm bg-white">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Reporter</th>
                        <th>Target Entity</th>
                        <th>Reason / Description</th>
                        <th>Status</th>
                        <th>Reported Date</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reports as $report)
                    <tr>
                        <td>
                            <strong class="d-block text-dark small">{{ $report->reporter->name ?? 'Anonymous' }}</strong>
                        </td>
                        <td>
                            <span class="badge bg-secondary-subtle text-dark">{{ class_basename($report->reportable_type) }} #{{ $report->reportable_id }}</span>
                        </td>
                        <td>
                            <span class="fw-semibold text-dark small d-block">{{ $report->reason }}</span>
                            <span class="text-muted" style="font-size: 0.72rem;">{{ $report->details ?? 'No additional details provided.' }}</span>
                        </td>
                        <td>
                            @if($report->status === 'pending')
                                <span class="badge bg-warning-subtle text-dark">Pending Review</span>
                            @elseif($report->status === 'actioned')
                                <span class="badge bg-danger-subtle text-danger">Action Taken</span>
                            @else
                                <span class="badge bg-success-subtle text-success">{{ ucfirst($report->status) }}</span>
                            @endif
                        </td>
                        <td>
                            <span class="text-muted small">{{ $report->created_at->format('M d, Y') }}</span>
                        </td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-outline-primary rounded-pill" data-bs-toggle="modal" data-bs-target="#resolveReportModal{{ $report->id }}">Moderate</button>

                            <!-- Moderation Decision Modal -->
                            <div class="modal fade text-start" id="resolveReportModal{{ $report->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow">
                                        <form action="{{ route('admin.reports.status', $report->id) }}" method="POST">
                                            @csrf
                                            <div class="modal-header">
                                                <h5 class="modal-title fw-bold">Moderate Report #{{ $report->id }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Moderation Decision</label>
                                                    <select name="status" class="form-select" required>
                                                        <option value="actioned" {{ $report->status === 'actioned' ? 'selected' : '' }}>Action Taken (Content Hidden/Removed)</option>
                                                        <option value="reviewed" {{ $report->status === 'reviewed' ? 'selected' : '' }}>Under Review</option>
                                                        <option value="dismissed" {{ $report->status === 'dismissed' ? 'selected' : '' }}>Dismiss Report (No Violation)</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Moderator Resolution Notes</label>
                                                    <textarea name="admin_notes" class="form-control" rows="3" placeholder="Notes explaining moderation action...">{{ $report->admin_notes }}</textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-primary rounded-pill px-4">Save Decision</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">No content reports requiring moderation.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3 border-top">
            {{ $reports->links() }}
        </div>
    </div>
</div>
@endsection

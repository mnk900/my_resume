@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <span class="badge bg-primary-subtle text-primary border border-primary px-3 py-1 mb-2">Applicant Tracking System (ATS)</span>
            <h1 class="h2 fw-bold text-dark mb-1">Applicants for {{ $opportunity->title }}</h1>
            <p class="text-secondary mb-0">Evaluate candidates, review portfolio match scores, shortlist, and schedule interviews.</p>
        </div>
        <a href="{{ route('opportunities.show', $opportunity->slug) }}" class="btn btn-outline-secondary">Job View</a>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Candidate Portfolio</th>
                            <th>Applied Date</th>
                            <th>Match Score</th>
                            <th>Status</th>
                            <th class="text-end">ATS Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($applications as $app)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold" style="width: 44px; height: 44px;">
                                        {{ strtoupper(substr($app->user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark mb-0">{{ $app->user->name }}</div>
                                        <span class="text-muted small">{{ $app->user->portfolio->position ?? 'Professional Candidate' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td><span class="text-secondary small">{{ $app->applied_at->format('M d, Y') }}</span></td>
                            <td>
                                <span class="badge bg-success rounded-pill px-3 py-1 fw-bold fs-6">{{ $app->match_score ? round($app->match_score) . '%' : 'N/A' }}</span>
                            </td>
                            <td>
                                <span class="badge bg-secondary px-3 py-1">{{ strtoupper(str_replace('_', ' ', $app->status)) }}</span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('applications.show', $app->id) }}" class="btn btn-sm btn-primary rounded-pill px-3"><i class="fa-solid fa-clipboard-check me-1"></i> Review Candidate</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                No candidate applications received for this job yet.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

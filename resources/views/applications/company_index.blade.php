@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <span class="badge bg-primary-subtle text-primary border border-primary px-3 py-1 mb-2">Applicant Tracking System (ATS)</span>
            <h1 class="h2 fw-bold text-dark mb-1">Applicants for {{ $opportunity->title }}</h1>
            <p class="text-secondary mb-0">Evaluate candidates, review public CVs, send direct selection/rejection emails, and update hiring stages.</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            @php
                $shareUrl = rawurlencode(route('opportunities.apply_public', $opportunity->slug));
            @endphp
            <button type="button" onclick="navigator.clipboard.writeText('{{ route('opportunities.apply_public', $opportunity->slug) }}'); alert('Public Application Form link copied!');" class="btn btn-outline-primary shadow-sm rounded-pill">
                <i class="fa-solid fa-share-nodes me-1"></i> Copy Public Form Link
            </button>
            <a href="{{ route('opportunities.show', $opportunity->slug) }}" class="btn btn-outline-secondary rounded-pill">Job View</a>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Candidate Details</th>
                            <th>Applicant Type</th>
                            <th>Current Employment</th>
                            <th>Applied Date</th>
                            <th>Status</th>
                            <th class="text-end">ATS Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($applications as $app)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-circle {{ $app->is_public_applicant ? 'bg-success' : 'bg-primary' }} text-white d-flex align-items-center justify-content-center fw-bold fs-5" style="width: 44px; height: 44px;">
                                        {{ strtoupper(substr($app->candidate_name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark mb-0">{{ $app->candidate_name }}</div>
                                        <span class="text-muted small d-block"><i class="fa-solid fa-envelope text-secondary me-1"></i>{{ $app->candidate_email }}</span>
                                        @if($app->candidate_phone)
                                            <span class="text-muted small d-block"><i class="fa-solid fa-phone text-secondary me-1"></i>{{ $app->candidate_phone }}</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($app->is_public_applicant)
                                    <span class="badge bg-success-subtle text-success border border-success px-2.5 py-1">
                                        <i class="fa-solid fa-globe me-1"></i> Public Applicant
                                    </span>
                                @else
                                    <span class="badge bg-primary-subtle text-primary border border-primary px-2.5 py-1">
                                        <i class="fa-solid fa-user-check me-1"></i> Platform User
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($app->is_currently_employed)
                                    <span class="fw-semibold text-dark small d-block">{{ $app->current_designation ?? 'Employed' }}</span>
                                    <span class="text-muted small">{{ $app->current_organization_name }}</span>
                                @else
                                    <span class="text-muted small">Not Currently Employed</span>
                                @endif
                            </td>
                            <td><span class="text-secondary small">{{ $app->applied_at ? $app->applied_at->format('M d, Y') : 'N/A' }}</span></td>
                            <td>
                                @php
                                    $statusClass = match($app->status) {
                                        'selected' => 'bg-success text-white',
                                        'rejected' => 'bg-danger text-white',
                                        'shortlisted' => 'bg-warning text-dark',
                                        'interview' => 'bg-info text-dark',
                                        'under_review' => 'bg-primary text-white',
                                        default => 'bg-secondary text-white',
                                    };
                                @endphp
                                <span class="badge {{ $statusClass }} px-3 py-1 fw-bold">{{ strtoupper(str_replace('_', ' ', $app->status)) }}</span>
                            </td>
                            <td class="text-end">
                                <div class="btn-group">
                                    <a href="{{ route('applications.show', $app->id) }}" class="btn btn-sm btn-primary rounded-pill px-3 me-1">
                                        <i class="fa-solid fa-clipboard-check me-1"></i> Review
                                    </a>
                                    <a href="{{ route('applications.download-resume', $app->id) }}" class="btn btn-sm btn-outline-secondary rounded-circle p-2" title="Download Resume / CV">
                                        <i class="fa-solid fa-file-arrow-down text-danger"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-inbox fa-3xl text-secondary d-block mb-3"></i>
                                No candidate applications received for this position yet.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($applications->hasPages())
                <div class="p-3 border-top">
                    {{ $applications->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

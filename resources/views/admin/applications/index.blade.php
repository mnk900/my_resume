@extends('layouts.admin')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
    <div>
        <h1 class="h3 fw-bold text-dark mb-1"><i class="fa-solid fa-paper-plane me-2 text-warning"></i> System Applications Activity Tracker</h1>
        <p class="text-secondary small mb-0">System-wide visibility of candidate applications, public guest submissions, CV downloads, and hiring actions.</p>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
        <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<!-- Data Table -->
<div class="card border-0 shadow-sm bg-white rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Candidate Details</th>
                        <th>Applicant Type</th>
                        <th>Position / Opportunity</th>
                        <th>Hiring Company</th>
                        <th>Stage</th>
                        <th>Submitted Date</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($applications as $app)
                    <tr>
                        <td>
                            <strong class="d-block text-dark small">{{ $app->candidate_name }}</strong>
                            <span class="text-muted" style="font-size: 0.75rem;">
                                <i class="fa-solid fa-envelope text-secondary me-1"></i>{{ $app->candidate_email }}
                            </span>
                            @if($app->candidate_phone)
                                <span class="text-muted d-block" style="font-size: 0.72rem;">
                                    <i class="fa-solid fa-phone text-secondary me-1"></i>{{ $app->candidate_phone }}
                                </span>
                            @endif
                        </td>
                        <td>
                            @if($app->is_public_applicant)
                                <span class="badge bg-success-subtle text-success border border-success px-2 py-1" style="font-size: 0.72rem;">
                                    <i class="fa-solid fa-globe me-1"></i> Public Guest
                                </span>
                            @else
                                <span class="badge bg-primary-subtle text-primary border border-primary px-2 py-1" style="font-size: 0.72rem;">
                                    <i class="fa-solid fa-user-check me-1"></i> Registered User
                                </span>
                            @endif
                        </td>
                        <td>
                            @if($app->opportunity)
                                <a href="{{ route('opportunities.show', $app->opportunity->slug) }}" class="text-dark text-decoration-none small fw-semibold" target="_blank">
                                    {{ $app->opportunity->title }}
                                </a>
                            @else
                                <span class="text-muted small">Position Deleted</span>
                            @endif
                        </td>
                        <td>
                            <span class="text-muted small">{{ $app->opportunity->company->name ?? 'Platform Posting' }}</span>
                        </td>
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
                            <span class="badge {{ $statusClass }} px-2.5 py-1" style="font-size: 0.75rem;">{{ strtoupper(str_replace('_', ' ', $app->status)) }}</span>
                        </td>
                        <td>
                            <span class="text-muted small">{{ $app->created_at ? $app->created_at->format('M d, Y') : 'N/A' }}</span>
                        </td>
                        <td class="text-end">
                            <div class="btn-group">
                                <a href="{{ route('applications.show', $app->id) }}" class="btn btn-sm btn-primary rounded-pill px-3 me-1">
                                    <i class="fa-solid fa-eye me-1"></i> Review
                                </a>
                                <a href="{{ route('applications.download-resume', $app->id) }}" class="btn btn-sm btn-outline-secondary rounded-circle p-2" title="Download CV">
                                    <i class="fa-solid fa-download text-danger"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">No job applications submitted yet.</td>
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
@endsection

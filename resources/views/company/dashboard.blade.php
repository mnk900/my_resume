@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1"><i class="fa-solid fa-gauge me-2 text-primary"></i> {{ $company->name }} Dashboard</h1>
            <p class="text-secondary small mb-0">Recruitment management, applicant tracking, and talent acquisition center.</p>
        </div>
        <div class="d-flex gap-2 mt-3 mt-md-0">
            <a href="{{ route('opportunities.create', ['company_id' => $company->id]) }}" class="btn btn-primary shadow-sm btn-sm"><i class="fa-solid fa-plus me-1"></i> Post Opportunity</a>
            <a href="{{ route('companies.show', $company->slug) }}" class="btn btn-outline-secondary btn-sm">Public Profile</a>
        </div>
    </div>

    <!-- Stats Cards Grid -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
                <div class="d-flex align-items-center">
                    <div class="rounded-3 bg-primary-subtle p-2 me-3 text-primary"><i class="fa-solid fa-briefcase fa-lg"></i></div>
                    <div>
                        <h4 class="fw-bold mb-0 text-dark fs-4">{{ $stats['active_jobs'] }}</h4>
                        <span class="text-muted small">Active Opportunities</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
                <div class="d-flex align-items-center">
                    <div class="rounded-3 bg-success-subtle p-2 me-3 text-success"><i class="fa-solid fa-users fa-lg"></i></div>
                    <div>
                        <h4 class="fw-bold mb-0 text-dark fs-4">{{ $stats['total_applications'] }}</h4>
                        <span class="text-muted small">Total Applicants</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
                <div class="d-flex align-items-center">
                    <div class="rounded-3 bg-warning-subtle p-2 me-3 text-warning"><i class="fa-solid fa-star fa-lg"></i></div>
                    <div>
                        <h4 class="fw-bold mb-0 text-dark fs-4">{{ $stats['shortlisted'] }}</h4>
                        <span class="text-muted small">Shortlisted Candidates</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
                <div class="d-flex align-items-center">
                    <div class="rounded-3 bg-info-subtle p-2 me-3 text-info"><i class="fa-solid fa-comments fa-lg"></i></div>
                    <div>
                        <h4 class="fw-bold mb-0 text-dark fs-4">{{ $stats['interviews'] }}</h4>
                        <span class="text-muted small">Interviews Scheduled</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Opportunities & Recent Applications Grid -->
    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold text-dark mb-0 fs-6"><i class="fa-solid fa-list-check me-2 text-primary"></i> Company Job Postings</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Job Title</th>
                                    <th>Status</th>
                                    <th>Applicants</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentOpportunities as $opp)
                                <tr>
                                    <td>
                                        <div class="fw-bold text-dark small">{{ $opp->title }}</div>
                                        <span class="text-muted small" style="font-size: 0.75rem;">{{ ucfirst($opp->location_type) }} &bull; {{ $opp->city ?? 'Remote' }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-success-subtle text-success border border-success">{{ ucfirst($opp->status) }}</span>
                                    </td>
                                    <td><span class="badge bg-secondary rounded-pill">{{ $opp->applications_count }}</span></td>
                                    <td class="text-end">
                                        <div class="d-flex justify-content-end gap-1">
                                            <a href="{{ route('opportunities.edit', $opp->id) }}" class="btn btn-sm btn-outline-secondary rounded-circle p-1 px-2" title="Edit Job"><i class="fa-solid fa-pen-to-square"></i></a>
                                            <form action="{{ route('opportunities.destroy', $opp->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this job posting?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle p-1 px-2" title="Delete Job"><i class="fa-solid fa-trash-can"></i></button>
                                            </form>
                                            <a href="{{ route('opportunities.applications', $opp->id) }}" class="btn btn-sm btn-outline-primary rounded-pill">ATS Applicants</a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted small">No job postings created yet.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="fw-bold text-dark mb-0 fs-6"><i class="fa-solid fa-user-check me-2 text-primary"></i> Recent Candidate Applications</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Candidate</th>
                                    <th>Job</th>
                                    <th>Match %</th>
                                    <th class="text-end">Review</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentApplications as $app)
                                <tr>
                                    <td>
                                        <div class="fw-bold text-dark small">{{ $app->user->name }}</div>
                                        <span class="text-muted small" style="font-size: 0.75rem;">{{ $app->applied_at->diffForHumans() }}</span>
                                    </td>
                                    <td><span class="small text-truncate d-block" style="max-width: 120px; font-size: 0.75rem;">{{ $app->opportunity->title }}</span></td>
                                    <td>
                                        <span class="badge bg-primary rounded-pill fw-bold">{{ $app->match_score ? round($app->match_score) . '%' : 'N/A' }}</span>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('applications.show', $app->id) }}" class="btn btn-sm btn-primary rounded-pill">Review</a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted small">No applications received yet.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

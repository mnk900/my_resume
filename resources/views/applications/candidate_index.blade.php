@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 fw-bold text-dark mb-1"><i class="fa-solid fa-paper-plane me-2 text-primary"></i> My Submitted Applications</h1>
            <p class="text-secondary mb-0">Track the status of your job and opportunity applications.</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Job Title / Company</th>
                            <th>Applied Date</th>
                            <th>Match Score</th>
                            <th>Current Status</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($applications as $app)
                        <tr>
                            <td>
                                <div class="fw-bold text-dark">{{ $app->opportunity->title }}</div>
                                <span class="text-muted small">{{ $app->opportunity->company->name ?? 'Organization' }} &bull; {{ ucfirst($app->opportunity->location_type) }}</span>
                            </td>
                            <td><span class="text-secondary small">{{ $app->applied_at->format('M d, Y') }}</span></td>
                            <td>
                                <span class="badge bg-success rounded-pill px-3 py-1 fw-bold">{{ $app->match_score ? round($app->match_score) . '%' : 'N/A' }}</span>
                            </td>
                            <td>
                                @php
                                    $badgeClass = match($app->status) {
                                        'shortlisted' => 'bg-warning text-dark',
                                        'interview' => 'bg-info text-white',
                                        'selected' => 'bg-success text-white',
                                        'rejected' => 'bg-danger text-white',
                                        default => 'bg-secondary text-white',
                                    };
                                @endphp
                                <span class="badge {{ $badgeClass }} px-3 py-1">{{ strtoupper(str_replace('_', ' ', $app->status)) }}</span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('opportunities.show', $app->opportunity->slug) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">View Job</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-folder-open fa-2x mb-2 d-block text-muted"></i>
                                You haven't submitted any job applications yet.
                                <div class="mt-3"><a href="{{ route('opportunities.index') }}" class="btn btn-primary btn-sm rounded-pill px-3">Explore Opportunities</a></div>
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

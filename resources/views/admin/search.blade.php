@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 fw-bold text-dark mb-1"><i class="fa-solid fa-magnifying-glass me-2 text-primary"></i> Global Control Search</h1>
        <p class="text-secondary small mb-0">Cross-platform categorized search results for "{{ $query }}".</p>
    </div>
</div>

<form action="{{ route('admin.search') }}" method="GET" class="card border-0 shadow-sm p-3 bg-white mb-4">
    <div class="input-group">
        <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
        <input type="text" name="q" class="form-control bg-light border-start-0" placeholder="Search professionals, companies, jobs, applications..." value="{{ $query }}">
        <button class="btn btn-primary px-4 rounded-end" type="submit">Search</button>
    </div>
</form>

@if(empty($query))
    <div class="card border-0 shadow-sm p-5 text-center bg-white">
        <i class="fa-solid fa-magnifying-glass fa-3x text-muted mb-3"></i>
        <h5 class="fw-bold text-dark">Enter a search term</h5>
        <p class="text-muted small">Search across professionals, companies, jobs, applications, and network posts.</p>
    </div>
@else
    <!-- Categorized Search Results -->
    <div class="row g-4">
        <!-- Professionals -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm bg-white h-100">
                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold text-dark mb-0"><i class="fa-solid fa-users me-2 text-primary"></i> Professionals ({{ $professionals->count() }})</h6>
                    <a href="{{ route('admin.professionals.index', ['search' => $query]) }}" class="small text-primary text-decoration-none fw-semibold">View All</a>
                </div>
                <div class="card-body p-0">
                    @forelse($professionals as $user)
                    <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-bold text-dark small">{{ $user->name }}</div>
                            <span class="text-muted small">{{ $user->email }} &bull; {{ $user->portfolio->position ?? 'Professional' }}</span>
                        </div>
                        <a href="{{ route('admin.professionals.show', $user->id) }}" class="btn btn-sm btn-outline-primary rounded-pill">Inspect</a>
                    </div>
                    @empty
                    <p class="p-3 text-muted small mb-0">No matching professionals found.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Companies -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm bg-white h-100">
                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold text-dark mb-0"><i class="fa-solid fa-building me-2 text-info"></i> Companies ({{ $companies->count() }})</h6>
                    <a href="{{ route('admin.companies.index', ['search' => $query]) }}" class="small text-primary text-decoration-none fw-semibold">View All</a>
                </div>
                <div class="card-body p-0">
                    @forelse($companies as $company)
                    <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-bold text-dark small">{{ $company->name }}</div>
                            <span class="text-muted small">{{ $company->industry ?? 'Organization' }} &bull; {{ $company->city }}</span>
                        </div>
                        <span class="badge bg-primary-subtle text-primary">{{ ucfirst($company->verification_status) }}</span>
                    </div>
                    @empty
                    <p class="p-3 text-muted small mb-0">No matching companies found.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Jobs & Opportunities -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm bg-white h-100">
                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold text-dark mb-0"><i class="fa-solid fa-briefcase me-2 text-success"></i> Opportunities ({{ $jobs->count() }})</h6>
                    <a href="{{ route('admin.jobs.index', ['search' => $query]) }}" class="small text-primary text-decoration-none fw-semibold">View All</a>
                </div>
                <div class="card-body p-0">
                    @forelse($jobs as $job)
                    <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-bold text-dark small">{{ $job->title }}</div>
                            <span class="text-muted small">{{ $job->company->name ?? 'Organization' }} &bull; {{ $job->city }}</span>
                        </div>
                        <span class="badge bg-success-subtle text-success">{{ ucfirst($job->status) }}</span>
                    </div>
                    @empty
                    <p class="p-3 text-muted small mb-0">No matching opportunities found.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Applications -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm bg-white h-100">
                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold text-dark mb-0"><i class="fa-solid fa-paper-plane me-2 text-warning"></i> Applications ({{ $applications->count() }})</h6>
                    <a href="{{ route('admin.applications.index') }}" class="small text-primary text-decoration-none fw-semibold">View All</a>
                </div>
                <div class="card-body p-0">
                    @forelse($applications as $app)
                    <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-bold text-dark small">{{ $app->user->name }}</div>
                            <span class="text-muted small">Applied for: {{ $app->opportunity->title ?? 'Position' }}</span>
                        </div>
                        <span class="badge bg-secondary-subtle text-dark">{{ ucfirst($app->status) }}</span>
                    </div>
                    @empty
                    <p class="p-3 text-muted small mb-0">No matching applications found.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endif
@endsection

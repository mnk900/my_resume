@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row align-items-center mb-4">
        <div class="col-md-8">
            <h1 class="h2 fw-bold text-dark mb-1">Discover Professional Opportunities</h1>
            <p class="text-secondary">Explore jobs, internships, freelance projects, trainings, and workshops.</p>
        </div>
        <div class="col-md-4 text-md-end">
            @auth
            <a href="{{ route('opportunities.create') }}" class="btn btn-primary shadow-sm"><i class="fa-solid fa-plus me-1"></i> Post Opportunity</a>
            @endauth
        </div>
    </div>

    <!-- Personalized Recommended Jobs (if authenticated candidate) -->
    @if(Auth::check() && $recommendedJobs->isNotEmpty())
    <div class="card border-0 shadow-sm rounded-4 mb-4 bg-primary text-white p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold mb-0 text-white"><i class="fa-solid fa-wand-magic-sparkles me-2"></i> Recommended Jobs for Your Portfolio</h4>
            <span class="badge bg-white text-primary rounded-pill px-3">Matched to Your Skills & Experience</span>
        </div>
        <div class="row g-3">
            @foreach($recommendedJobs as $rec)
            <div class="col-md-6">
                <div class="card border-0 shadow-sm rounded-3 h-100 bg-white text-dark p-3">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <h6 class="fw-bold mb-0"><a href="{{ route('opportunities.show', $rec['job']->slug) }}" class="text-decoration-none text-dark">{{ $rec['job']->title }}</a></h6>
                            <span class="text-muted small">{{ $rec['job']->company->name ?? 'Organization' }} &bull; {{ ucfirst($rec['job']->location_type) }}</span>
                        </div>
                        <span class="badge bg-success rounded-pill px-3 py-2 fw-bold fs-6">{{ $rec['match']['overall_score'] }}% Match</span>
                    </div>
                    <p class="text-muted small mb-2">{{ Str::limit(strip_tags($rec['job']->description), 90) }}</p>
                    <div class="mt-auto pt-2 border-top d-flex justify-content-between align-items-center">
                        <span class="text-primary fw-semibold small">{{ $rec['job']->salary_min ? '$' . number_format($rec['job']->salary_min) : 'Competitive Salary' }}</span>
                        <a href="{{ route('opportunities.show', $rec['job']->slug) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">View Job</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Search & Filters -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <form action="{{ route('opportunities.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0" placeholder="Job title, skills, keywords..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <select name="type" class="form-select">
                        <option value="">All Types</option>
                        <option value="job" {{ request('type') == 'job' ? 'selected' : '' }}>Job</option>
                        <option value="internship" {{ request('type') == 'internship' ? 'selected' : '' }}>Internship</option>
                        <option value="freelance" {{ request('type') == 'freelance' ? 'selected' : '' }}>Freelance</option>
                        <option value="training" {{ request('type') == 'training' ? 'selected' : '' }}>Training</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="location_type" class="form-select">
                        <option value="">All Locations</option>
                        <option value="onsite" {{ request('location_type') == 'onsite' ? 'selected' : '' }}>On-Site</option>
                        <option value="remote" {{ request('location_type') == 'remote' ? 'selected' : '' }}>Remote</option>
                        <option value="hybrid" {{ request('location_type') == 'hybrid' ? 'selected' : '' }}>Hybrid</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="text" name="city" class="form-control" placeholder="City" value="{{ request('city') }}">
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100 fw-semibold">Search</button>
                    <a href="{{ route('opportunities.index') }}" class="btn btn-outline-secondary"><i class="fa-solid fa-rotate-left"></i></a>
                </div>
            </form>
        </div>
    </div>

    <!-- Opportunities Grid -->
    <div class="row g-4">
        @forelse($opportunities as $opp)
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm rounded-4 p-4 d-flex flex-column">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <span class="badge bg-primary-subtle text-primary border border-primary me-2 mb-2">{{ strtoupper($opp->type) }}</span>
                        @if($opp->is_featured)
                            <span class="badge bg-warning text-dark me-2"><i class="fa-solid fa-star me-1"></i> Featured</span>
                        @endif
                        <h5 class="fw-bold mb-1"><a href="{{ route('opportunities.show', $opp->slug) }}" class="text-decoration-none text-dark hover-primary">{{ $opp->title }}</a></h5>
                        <p class="text-muted small mb-0">{{ $opp->company->name ?? 'Organization' }} &bull; {{ ucfirst($opp->location_type) }}</p>
                    </div>
                </div>

                <p class="text-secondary small mb-3 flex-grow-1">
                    {{ Str::limit(strip_tags($opp->description), 120) }}
                </p>

                @if($opp->skills->isNotEmpty())
                <div class="mb-3">
                    @foreach($opp->skills->take(3) as $sk)
                        <span class="badge bg-light text-secondary border me-1">{{ $sk->skill_name }}</span>
                    @endforeach
                </div>
                @endif

                @php
                    $oppSymbol = ($opp->salary_currency === 'PKR' || $opp->salary_currency === 'Rs') ? 'PKR ' : '$';
                @endphp
                <div class="pt-3 border-top d-flex justify-content-between align-items-center mt-auto">
                    <span class="fw-bold text-dark small">{{ $opp->salary_min ? $oppSymbol . number_format($opp->salary_min) . ' / ' . $opp->salary_period : 'Competitive' }}</span>
                    <div class="d-flex gap-1 align-items-center">
                        @auth
                            @if(Auth::id() === $opp->posted_by_user_id || ($opp->company && $opp->company->user_id === Auth::id()) || Auth::user()->isAdmin())
                                <a href="{{ route('opportunities.edit', $opp->id) }}" class="btn btn-sm btn-outline-secondary rounded-circle p-1 px-2" title="Edit Job"><i class="fa-solid fa-pen-to-square"></i></a>
                            @endif
                        @endauth
                        <a href="{{ route('opportunities.show', $opp->slug) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">View Details</a>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 text-center py-5">
                <div class="card-body">
                    <i class="fa-solid fa-briefcase fa-3x text-muted mb-3"></i>
                    <h4 class="fw-bold text-dark">No Opportunities Found</h4>
                    <p class="text-muted">No positions match your current search filters.</p>
                </div>
            </div>
        </div>
        @endforelse
    </div>

    <div class="d-flex justify-content-center mt-5">
        {{ $opportunities->withQueryString()->links() }}
    </div>
</div>
@endsection

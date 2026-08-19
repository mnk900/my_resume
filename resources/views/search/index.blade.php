@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <form action="{{ route('search.index') }}" method="GET" class="row g-2">
                <div class="col-md-10">
                    <div class="input-group input-group-lg">
                        <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-magnifying-glass text-primary"></i></span>
                        <input type="text" name="q" class="form-control border-start-0" placeholder="Search professionals, jobs, companies, opportunities..." value="{{ $q }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold shadow-sm">Search</button>
                </div>
            </form>
        </div>
    </div>

    @if(!empty($q))
    <h3 class="fw-bold text-dark mb-4">Search Results for "<span class="text-primary">{{ $q }}</span>"</h3>

    <!-- Professionals Section -->
    <div class="mb-5">
        <h5 class="fw-bold text-dark mb-3"><i class="fa-solid fa-user-gear me-2 text-primary"></i> Professionals ({{ $professionals->count() }})</h5>
        <div class="row g-3">
            @forelse($professionals as $p)
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-3 p-3">
                    <h6 class="fw-bold mb-1"><a href="{{ route('portfolio.show', $p->username) }}" class="text-decoration-none text-dark">{{ $p->name }}</a></h6>
                    <span class="text-primary small fw-semibold d-block mb-1">{{ $p->portfolio->position ?? 'Candidate' }}</span>
                    <a href="{{ route('portfolio.show', $p->username) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 mt-2">View Portfolio</a>
                </div>
            </div>
            @empty
            <div class="col-12"><span class="text-muted small">No professional candidates found matching "{{ $q }}".</span></div>
            @endforelse
        </div>
    </div>

    <!-- Companies Section -->
    <div class="mb-5">
        <h5 class="fw-bold text-dark mb-3"><i class="fa-solid fa-building me-2 text-primary"></i> Companies & Organizations ({{ $companies->count() }})</h5>
        <div class="row g-3">
            @forelse($companies as $c)
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-3 p-3">
                    <h6 class="fw-bold mb-1"><a href="{{ route('companies.show', $c->slug) }}" class="text-decoration-none text-dark">{{ $c->name }}</a></h6>
                    <span class="text-muted small d-block mb-1">{{ $c->industry ?? 'Organization' }} &bull; {{ $c->city ?? 'Global' }}</span>
                    <a href="{{ route('companies.show', $c->slug) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 mt-2">View Profile</a>
                </div>
            </div>
            @empty
            <div class="col-12"><span class="text-muted small">No companies found matching "{{ $q }}".</span></div>
            @endforelse
        </div>
    </div>

    <!-- Opportunities Section -->
    <div class="mb-5">
        <h5 class="fw-bold text-dark mb-3"><i class="fa-solid fa-briefcase me-2 text-primary"></i> Jobs & Opportunities ({{ $opportunities->count() }})</h5>
        <div class="row g-3">
            @forelse($opportunities as $opp)
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-3 p-3">
                    <h6 class="fw-bold mb-1"><a href="{{ route('opportunities.show', $opp->slug) }}" class="text-decoration-none text-dark">{{ $opp->title }}</a></h6>
                    <span class="text-muted small d-block mb-1">{{ $opp->company->name ?? 'Organization' }} &bull; {{ ucfirst($opp->location_type) }}</span>
                    <a href="{{ route('opportunities.show', $opp->slug) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 mt-2">View Job Details</a>
                </div>
            </div>
            @empty
            <div class="col-12"><span class="text-muted small">No job opportunities found matching "{{ $q }}".</span></div>
            @endforelse
        </div>
    </div>
    @endif
</div>
@endsection

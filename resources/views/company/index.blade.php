@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row align-items-center mb-4">
        <div class="col-md-8">
            <h1 class="h2 fw-bold text-dark mb-1">Discover Companies & Organizations</h1>
            <p class="text-secondary">Explore top employers, recruitment agencies, educational institutes, and professional partners.</p>
        </div>
        <div class="col-md-4 text-md-end">
            @auth
            <a href="{{ route('companies.create') }}" class="btn btn-primary shadow-sm"><i class="fa-solid fa-plus me-1"></i> Register Company Profile</a>
            @endauth
        </div>
    </div>

    <!-- Search & Filter Card -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <form action="{{ route('companies.index') }}" method="GET" class="row g-3">
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0" placeholder="Search by name, industry, description..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <input type="text" name="industry" class="form-control" placeholder="Industry (e.g. Software, Healthcare)" value="{{ request('industry') }}">
                </div>
                <div class="col-md-2">
                    <input type="text" name="city" class="form-control" placeholder="City" value="{{ request('city') }}">
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100 fw-semibold">Filter</button>
                    <a href="{{ route('companies.index') }}" class="btn btn-outline-secondary" title="Clear Filters"><i class="fa-solid fa-rotate-left"></i></a>
                </div>
            </form>
        </div>
    </div>

    <!-- Companies Grid -->
    <div class="row g-4">
        @forelse($companies as $company)
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden position-relative">
                <div class="bg-gradient bg-primary opacity-25" style="height: 80px;"></div>
                <div class="card-body pt-0 px-4 pb-4">
                    <div class="d-flex justify-content-between align-items-end mb-3" style="margin-top: -40px;">
                        @if($company->logo_path)
                            <img src="{{ asset('storage/' . $company->logo_path) }}" alt="{{ $company->name }}" class="rounded-3 shadow-sm bg-white p-1 border" style="width: 72px; height: 72px; object-fit: contain;">
                        @else
                            <div class="rounded-3 shadow-sm bg-primary text-white d-flex align-items-center justify-content-center fw-bold fs-3 border" style="width: 72px; height: 72px;">
                                {{ strtoupper(substr($company->name, 0, 1)) }}
                            </div>
                        @endif

                        @if($company->isVerified())
                            <span class="badge bg-success-subtle text-success border border-success rounded-pill px-3"><i class="fa-solid fa-circle-check me-1"></i> Verified</span>
                        @endif
                    </div>

                    <h5 class="card-title fw-bold mb-1">
                        <a href="{{ route('companies.show', $company->slug) }}" class="text-decoration-none text-dark hover-primary">{{ $company->name }}</a>
                    </h5>
                    
                    <p class="text-primary small fw-semibold mb-2">{{ $company->industry ?? 'Organization' }} &bull; {{ $company->city ?? 'Global' }}</p>
                    
                    <p class="text-muted small mb-3 text-truncate-2" style="min-height: 40px;">
                        {{ Str::limit(strip_tags($company->description), 110) }}
                    </p>

                    <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                        <span class="badge bg-light text-dark border fw-normal"><i class="fa-solid fa-briefcase me-1 text-primary"></i> {{ $company->opportunities_count }} Openings</span>
                        <a href="{{ route('companies.show', $company->slug) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">View Profile</a>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 text-center py-5">
                <div class="card-body">
                    <i class="fa-solid fa-building-circle-xmark fa-3x text-muted mb-3"></i>
                    <h4 class="fw-bold text-dark">No Companies Found</h4>
                    <p class="text-muted mb-4">No organizations match your current search criteria.</p>
                    <a href="{{ route('companies.index') }}" class="btn btn-outline-primary rounded-pill px-4">Reset Filters</a>
                </div>
            </div>
        </div>
        @endforelse
    </div>

    <div class="d-flex justify-content-center mt-5">
        {{ $companies->withQueryString()->links() }}
    </div>
</div>
@endsection

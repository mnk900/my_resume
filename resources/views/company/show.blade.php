@extends('layouts.app')

@section('content')
<div class="container py-4">
    <!-- Header Banner Card -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="position-relative bg-primary bg-gradient" style="height: 180px;">
            @if($company->cover_path)
                <img src="{{ asset('storage/' . $company->cover_path) }}" alt="{{ $company->name }}" class="w-100 h-100 object-fit-cover">
            @endif
        </div>
        <div class="card-body p-4 pt-0">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3 mb-3" style="margin-top: -50px;">
                <div class="d-flex align-items-end gap-3">
                    @if($company->logo_path)
                        <img src="{{ asset('storage/' . $company->logo_path) }}" alt="{{ $company->name }}" class="rounded-4 shadow bg-white p-2 border" style="width: 110px; height: 110px; object-fit: contain;">
                    @else
                        <div class="rounded-4 shadow bg-primary text-white d-flex align-items-center justify-content-center fw-bold fs-1 border" style="width: 110px; height: 110px;">
                            {{ strtoupper(substr($company->name, 0, 1)) }}
                        </div>
                    @endif
                    <div>
                        <h1 class="h2 fw-bold mb-1 text-dark d-flex align-items-center gap-2">
                            {{ $company->name }}
                            @if($company->isVerified())
                                <i class="fa-solid fa-circle-check text-success fs-5" title="Verified Organization"></i>
                            @endif
                        </h1>
                        <p class="text-muted mb-0 fw-medium"><i class="fa-solid fa-industry text-primary me-1"></i> {{ $company->industry ?? 'Organization' }} &bull; <i class="fa-solid fa-location-dot me-1 text-danger"></i> {{ $company->city ?? 'Global' }}{{ $company->country ? ', ' . $company->country : '' }}</p>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    @if($userMembership || (Auth::check() && Auth::user()->isAdmin()))
                        <a href="{{ route('companies.dashboard', $company->id) }}" class="btn btn-outline-primary fw-semibold rounded-pill px-3"><i class="fa-solid fa-gauge me-1"></i> Dashboard</a>
                        <a href="{{ route('companies.edit', $company->id) }}" class="btn btn-primary fw-semibold rounded-pill px-3"><i class="fa-solid fa-pen me-1"></i> Edit Profile</a>
                    @endif
                    @if($company->website)
                        <a href="{{ $company->website }}" target="_blank" class="btn btn-light border fw-semibold rounded-pill px-3"><i class="fa-solid fa-globe me-1"></i> Website</a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="row g-4">
        <div class="col-lg-8">
            <!-- About Card -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <h4 class="fw-bold text-dark mb-3"><i class="fa-solid fa-circle-info me-2 text-primary"></i> About {{ $company->name }}</h4>
                    <p class="text-secondary lh-lg mb-0">{!! nl2br(e($company->description)) !!}</p>
                </div>
            </div>

            <!-- Open Opportunities Card -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                    <h4 class="fw-bold text-dark mb-0"><i class="fa-solid fa-briefcase me-2 text-primary"></i> Active Opportunities ({{ $company->opportunities->count() }})</h4>
                    @if($userMembership)
                    <a href="{{ route('opportunities.create', ['company_id' => $company->id]) }}" class="btn btn-sm btn-primary rounded-pill px-3"><i class="fa-solid fa-plus me-1"></i> Post New Job</a>
                    @endif
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        @forelse($company->opportunities as $opp)
                        <div class="col-12">
                            <div class="p-3 border rounded-3 hover-shadow transition bg-white d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="fw-bold mb-1"><a href="{{ route('opportunities.show', $opp->slug) }}" class="text-decoration-none text-dark">{{ $opp->title }}</a></h5>
                                    <p class="text-muted small mb-0">
                                        <span class="badge bg-primary-subtle text-primary border border-primary me-2">{{ strtoupper($opp->type) }}</span>
                                        <i class="fa-solid fa-location-dot me-1"></i> {{ ucfirst($opp->location_type) }} &bull; {{ $opp->city ?? 'Remote' }}
                                    </p>
                                </div>
                                <a href="{{ route('opportunities.show', $opp->slug) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">View Job</a>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-4 text-muted">
                            <p class="mb-0">No active job opportunities published at the moment.</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Sidebar Details Card -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-dark mb-3">Organization Details</h5>
                    <ul class="list-unstyled mb-0 d-flex flex-column gap-3">
                        <li class="d-flex align-items-center text-secondary">
                            <i class="fa-solid fa-building me-3 text-primary fa-lg"></i>
                            <div>
                                <span class="d-block small text-muted">Org Type</span>
                                <span class="fw-semibold text-dark">{{ $company->org_type ?? 'N/A' }}</span>
                            </div>
                        </li>
                        <li class="d-flex align-items-center text-secondary">
                            <i class="fa-solid fa-envelope me-3 text-primary fa-lg"></i>
                            <div>
                                <span class="d-block small text-muted">Email</span>
                                <span class="fw-semibold text-dark">{{ $company->email }}</span>
                            </div>
                        </li>
                        @if($company->phone)
                        <li class="d-flex align-items-center text-secondary">
                            <i class="fa-solid fa-phone me-3 text-primary fa-lg"></i>
                            <div>
                                <span class="d-block small text-muted">Phone</span>
                                <span class="fw-semibold text-dark">{{ $company->phone }}</span>
                            </div>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

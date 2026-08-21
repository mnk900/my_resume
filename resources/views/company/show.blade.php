@extends('layouts.app')

@section('content')
<div class="container py-4">
    <!-- Header Banner & Profile Card -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4 bg-white">
        <!-- Cover Banner Container -->
        <div class="position-relative" style="height: 220px; background: linear-gradient(135deg, #1e293b 0%, #0f172a 50%, #2563eb 100%);">
            @if($company->cover_path)
                <img src="{{ asset('storage/' . $company->cover_path) }}" alt="{{ $company->name }} Cover" class="w-100 h-100 object-fit-cover">
                <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(to bottom, rgba(0,0,0,0.1), rgba(0,0,0,0.4));"></div>
            @endif
        </div>

        <!-- Profile Info Bar -->
        <div class="card-body p-4 pt-0">
            <div class="d-flex flex-column flex-md-row align-items-center align-items-md-end justify-content-between gap-4" style="margin-top: -60px; position: relative; z-index: 5;">
                
                <!-- Logo & Title Block -->
                <div class="d-flex flex-column flex-md-row align-items-center align-items-md-end gap-3 text-center text-md-start">
                    <!-- Logo Box (100% visible, no clipping or overlapping overflow) -->
                    <div class="flex-shrink-0 bg-white rounded-4 shadow p-2 border border-2 border-white" style="width: 120px; height: 120px; box-shadow: 0 10px 25px rgba(0,0,0,0.12)!important;">
                        @if($company->logo_path)
                            <img src="{{ asset('storage/' . $company->logo_path) }}" alt="{{ $company->name }}" class="w-100 h-100 rounded-3" style="object-fit: contain;">
                        @else
                            <div class="w-100 h-100 rounded-3 bg-primary bg-gradient text-white d-flex align-items-center justify-content-center fw-bold fs-1">
                                {{ strtoupper(substr($company->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>

                    <!-- Company Title & Info -->
                    <div class="mb-1">
                        <h1 class="h2 fw-bold text-dark mb-1 d-flex align-items-center justify-content-center justify-content-md-start gap-2">
                            {{ $company->name }}
                            @if($company->isVerified())
                                <i class="fa-solid fa-circle-check text-primary fs-5" title="Verified Organization"></i>
                            @endif
                        </h1>
                        <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-start gap-2 text-secondary small">
                            <span class="badge bg-light text-dark border px-3 py-1.5 rounded-pill">
                                <i class="fa-solid fa-industry text-primary me-1"></i> {{ $company->industry ?? 'Organization' }}
                            </span>
                            @if($company->city || $company->country)
                            <span class="badge bg-light text-dark border px-3 py-1.5 rounded-pill">
                                <i class="fa-solid fa-location-dot text-danger me-1"></i> {{ implode(', ', array_filter([$company->city, $company->country])) }}
                            </span>
                            @endif
                            @if($company->org_type)
                            <span class="badge bg-light text-dark border px-3 py-1.5 rounded-pill">
                                <i class="fa-solid fa-building text-info me-1"></i> {{ $company->org_type }}
                            </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Action Buttons Block -->
                <div class="d-flex flex-wrap gap-2 justify-content-center justify-content-md-end w-100 w-md-auto mb-1">
                    @if($userMembership || (Auth::check() && Auth::user()->isAdmin()))
                        <a href="{{ route('companies.dashboard', $company->id) }}" class="btn btn-outline-primary fw-bold rounded-pill px-3 py-2">
                            <i class="fa-solid fa-gauge me-1"></i> Dashboard
                        </a>
                        <a href="{{ route('companies.edit', $company->id) }}" class="btn btn-primary fw-bold rounded-pill px-3 py-2 shadow-sm">
                            <i class="fa-solid fa-pen-to-square me-1"></i> Edit Profile
                        </a>
                    @endif
                    @if($company->website)
                        <a href="{{ $company->website }}" target="_blank" rel="noopener noreferrer" class="btn btn-light border fw-bold rounded-pill px-3 py-2 hover-primary">
                            <i class="fa-solid fa-globe me-1 text-primary"></i> Visit Website
                        </a>
                    @endif
                </div>

            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="row g-4">
        <!-- Left Main Column -->
        <div class="col-lg-8">
            <!-- About Card -->
            <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white">
                <div class="card-body p-4">
                    <h4 class="fw-bold text-dark mb-3"><i class="fa-solid fa-circle-info me-2 text-primary"></i> About {{ $company->name }}</h4>
                    <div class="text-secondary lh-lg mb-0" style="font-size: 0.95rem;">
                        {!! nl2br(e($company->description)) !!}
                    </div>
                </div>
            </div>

            <!-- Active Job Opportunities Card -->
            <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white">
                <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
                    <h4 class="fw-bold text-dark mb-0 fs-5"><i class="fa-solid fa-briefcase me-2 text-primary"></i> Active Job Openings ({{ $company->opportunities->count() }})</h4>
                    @if($userMembership)
                        <a href="{{ route('opportunities.create', ['company_id' => $company->id]) }}" class="btn btn-sm btn-primary rounded-pill px-3 fw-bold">
                            <i class="fa-solid fa-plus me-1"></i> Post New Job
                        </a>
                    @endif
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        @forelse($company->opportunities as $opp)
                        <div class="col-12">
                            <div class="p-3 border rounded-3 hover-shadow transition bg-white d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                                <div>
                                    <h5 class="fw-bold mb-1">
                                        <a href="{{ route('opportunities.show', $opp->slug) }}" class="text-decoration-none text-dark hover-primary">{{ $opp->title }}</a>
                                    </h5>
                                    <div class="d-flex flex-wrap align-items-center gap-2 text-muted small">
                                        <span class="badge bg-primary-subtle text-primary border border-primary px-2.5 py-1">{{ strtoupper($opp->type) }}</span>
                                        <span><i class="fa-solid fa-location-dot me-1 text-danger"></i> {{ ucfirst($opp->location_type) }} &bull; {{ $opp->city ?? 'Remote' }}</span>
                                        @if($opp->salary_min || $opp->salary_max)
                                            <span>&bull; <i class="fa-solid fa-money-bill-wave text-success me-1"></i> {{ $opp->salary_currency ?? '$' }}{{ number_format($opp->salary_min ?? 0) }} - {{ number_format($opp->salary_max ?? 0) }}</span>
                                        @endif
                                    </div>
                                </div>
                                <a href="{{ route('opportunities.show', $opp->slug) }}" class="btn btn-sm btn-outline-primary rounded-pill px-4 fw-bold flex-shrink-0">View Job</a>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-5 text-muted">
                            <i class="fa-solid fa-briefcase fa-3x mb-3 opacity-50"></i>
                            <p class="mb-0 fw-medium">No active job opportunities published by {{ $company->name }} at the moment.</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Sidebar Column -->
        <div class="col-lg-4">
            <!-- Organization Details Card -->
            <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-dark mb-4 border-bottom pb-2"><i class="fa-solid fa-building-circle-check text-primary me-2"></i> Organization Info</h5>
                    <ul class="list-unstyled mb-0 d-flex flex-column gap-3">
                        <li class="d-flex align-items-center text-secondary">
                            <div class="rounded-circle bg-primary-subtle text-primary p-2.5 me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <i class="fa-solid fa-layer-group"></i>
                            </div>
                            <div>
                                <span class="d-block small text-muted">Organization Type</span>
                                <span class="fw-semibold text-dark">{{ $company->org_type ?? 'Private Company' }}</span>
                            </div>
                        </li>
                        <li class="d-flex align-items-center text-secondary">
                            <div class="rounded-circle bg-info-subtle text-info p-2.5 me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <i class="fa-solid fa-industry"></i>
                            </div>
                            <div>
                                <span class="d-block small text-muted">Industry Domain</span>
                                <span class="fw-semibold text-dark">{{ $company->industry ?? 'Technology & Services' }}</span>
                            </div>
                        </li>
                        <li class="d-flex align-items-center text-secondary">
                            <div class="rounded-circle bg-success-subtle text-success p-2.5 me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <i class="fa-solid fa-envelope"></i>
                            </div>
                            <div>
                                <span class="d-block small text-muted">Contact Email</span>
                                <a href="mailto:{{ $company->email }}" class="fw-semibold text-dark text-decoration-none hover-primary">{{ $company->email }}</a>
                            </div>
                        </li>
                        @if($company->phone)
                        <li class="d-flex align-items-center text-secondary">
                            <div class="rounded-circle bg-warning-subtle text-warning p-2.5 me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <i class="fa-solid fa-phone"></i>
                            </div>
                            <div>
                                <span class="d-block small text-muted">Phone Contact</span>
                                <span class="fw-semibold text-dark">{{ $company->phone }}</span>
                            </div>
                        </li>
                        @endif
                        @if($company->website)
                        <li class="d-flex align-items-center text-secondary">
                            <div class="rounded-circle bg-secondary-subtle text-secondary p-2.5 me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <i class="fa-solid fa-globe"></i>
                            </div>
                            <div>
                                <span class="d-block small text-muted">Official Website</span>
                                <a href="{{ $company->website }}" target="_blank" rel="noopener noreferrer" class="fw-semibold text-primary text-decoration-none hover-underline text-break">
                                    {{ parse_url($company->website, PHP_URL_HOST) ?? $company->website }}
                                </a>
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

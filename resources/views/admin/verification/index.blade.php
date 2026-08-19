@extends('layouts.admin')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
    <div>
        <h1 class="h3 fw-bold text-dark mb-1"><i class="fa-solid fa-shield-halved me-2 text-primary"></i> Verification Control Center</h1>
        <p class="text-secondary small mb-0">Centralized governance queue for approving new companies, verifying professional profiles, and reviewing job postings.</p>
    </div>
</div>

<div class="row g-4">
    <!-- Pending Companies Queue -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm bg-white h-100">
            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold text-dark mb-0"><i class="fa-solid fa-building me-2 text-info"></i> Pending Companies ({{ $pendingCompanies->count() }})</h6>
            </div>
            <div class="card-body p-0">
                @forelse($pendingCompanies as $company)
                <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                    <div>
                        <strong class="d-block text-dark small">{{ $company->name }}</strong>
                        <span class="text-muted small">{{ $company->industry ?? 'Organization' }} &bull; {{ $company->city }}</span>
                    </div>
                    <form action="{{ route('admin.companies.status', $company->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="status" value="verified">
                        <button type="submit" class="btn btn-sm btn-success rounded-pill px-3"><i class="fa-solid fa-check me-1"></i> Approve</button>
                    </form>
                </div>
                @empty
                <div class="p-4 text-center text-muted small">
                    <i class="fa-solid fa-circle-check text-success fa-2xl mb-2"></i>
                    <p class="mb-0">No pending company verifications.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Unverified Users Queue -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm bg-white h-100">
            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold text-dark mb-0"><i class="fa-solid fa-users me-2 text-warning"></i> Unverified Professionals ({{ $unverifiedUsers->count() }})</h6>
            </div>
            <div class="card-body p-0">
                @forelse($unverifiedUsers as $user)
                <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                    <div>
                        <strong class="d-block text-dark small">{{ $user->name }}</strong>
                        <span class="text-muted small">{{ $user->email }} &bull; Joined {{ $user->created_at->diffForHumans() }}</span>
                    </div>
                    <form action="{{ route('admin.users.toggle-verification', $user->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-primary rounded-pill px-3"><i class="fa-solid fa-shield-halved me-1"></i> Verify</button>
                    </form>
                </div>
                @empty
                <div class="p-4 text-center text-muted small">
                    <i class="fa-solid fa-circle-check text-success fa-2xl mb-2"></i>
                    <p class="mb-0">All registered professionals are verified.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

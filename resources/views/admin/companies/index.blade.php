@extends('layouts.admin')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
    <div>
        <h1 class="h3 fw-bold text-dark mb-1"><i class="fa-solid fa-building me-2 text-info"></i> Company Directory & Verification</h1>
        <p class="text-secondary small mb-0">Manage registered organizations, review submitted verifications, and audit status approvals.</p>
    </div>
</div>

<!-- Filters Bar -->
<div class="card border-0 shadow-sm p-3 bg-white mb-4">
    <form action="{{ route('admin.companies.index') }}" method="GET" class="row g-3 align-items-center">
        <div class="col-md-5">
            <input type="text" name="search" class="form-control form-control-sm" placeholder="Search company by name, industry or city..." value="{{ request('search') }}">
        </div>
        <div class="col-md-4">
            <select name="status" class="form-select form-select-sm">
                <option value="">All Verification Statuses</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending Review</option>
                <option value="verified" {{ request('status') === 'verified' ? 'selected' : '' }}>Verified Companies</option>
                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
            </select>
        </div>
        <div class="col-md-3 text-md-end">
            <button type="submit" class="btn btn-sm btn-primary rounded-pill px-3 me-1">Filter</button>
            <a href="{{ route('admin.companies.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">Reset</a>
        </div>
    </form>
</div>

<!-- Data Table -->
<div class="card border-0 shadow-sm bg-white">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Company Name</th>
                        <th>Industry / Org Type</th>
                        <th>City / Location</th>
                        <th>Verification Status</th>
                        <th>Registered Date</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($companies as $company)
                    <tr>
                        <td>
                            <strong class="d-block text-dark small">{{ $company->name }}</strong>
                            <span class="text-muted" style="font-size: 0.72rem;">{{ $company->email ?? 'No email' }}</span>
                        </td>
                        <td>
                            <span class="badge bg-secondary-subtle text-dark">{{ $company->industry ?? 'General Industry' }}</span>
                        </td>
                        <td>
                            <span class="text-dark small">{{ $company->city ?? 'Remote' }}</span>
                        </td>
                        <td>
                            @if($company->verification_status === 'verified')
                                <span class="badge bg-success-subtle text-success"><i class="fa-solid fa-circle-check me-1"></i> Verified</span>
                            @elseif($company->verification_status === 'pending')
                                <span class="badge bg-warning-subtle text-dark"><i class="fa-solid fa-clock me-1"></i> Pending Review</span>
                            @elseif($company->verification_status === 'rejected')
                                <span class="badge bg-danger-subtle text-danger">Rejected</span>
                            @else
                                <span class="badge bg-danger">Suspended</span>
                            @endif
                        </td>
                        <td>
                            <span class="text-muted small">{{ $company->created_at->format('M d, Y') }}</span>
                        </td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-outline-primary rounded-pill" data-bs-toggle="modal" data-bs-target="#verifyCompanyModal{{ $company->id }}">Update Status</button>

                            <!-- Verification Status Modal -->
                            <div class="modal fade text-start" id="verifyCompanyModal{{ $company->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow">
                                        <form action="{{ route('admin.companies.status', $company->id) }}" method="POST">
                                            @csrf
                                            <div class="modal-header">
                                                <h5 class="modal-title fw-bold">Update Verification &mdash; {{ $company->name }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Verification Status</label>
                                                    <select name="status" class="form-select" required>
                                                        <option value="verified" {{ $company->verification_status === 'verified' ? 'selected' : '' }}>Verified (Approved)</option>
                                                        <option value="pending" {{ $company->verification_status === 'pending' ? 'selected' : '' }}>Pending Review</option>
                                                        <option value="rejected" {{ $company->verification_status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                                        <option value="suspended" {{ $company->verification_status === 'suspended' ? 'selected' : '' }}>Suspended</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-primary rounded-pill px-4">Save Status</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">No companies found matching filters.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3 border-top">
            {{ $companies->links() }}
        </div>
    </div>
</div>
@endsection

@extends('layouts.admin')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
    <div>
        <h1 class="h3 fw-bold text-dark mb-1"><i class="fa-solid fa-briefcase me-2 text-primary"></i> Opportunities & Jobs Governance</h1>
        <p class="text-secondary small mb-0">System-wide review, approval, publishing, and featuring of platform opportunities.</p>
    </div>
</div>

<!-- Filters Bar -->
<div class="card border-0 shadow-sm p-3 bg-white mb-4">
    <form action="{{ route('admin.jobs.index') }}" method="GET" class="row g-3 align-items-center">
        <div class="col-md-5">
            <input type="text" name="search" class="form-control form-control-sm" placeholder="Search by title, category or city..." value="{{ request('search') }}">
        </div>
        <div class="col-md-4">
            <select name="status" class="form-select form-select-sm">
                <option value="">All Opportunity Statuses</option>
                <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
                <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft / Pending</option>
                <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Closed</option>
                <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
            </select>
        </div>
        <div class="col-md-3 text-md-end">
            <button type="submit" class="btn btn-sm btn-primary rounded-pill px-3 me-1">Filter</button>
            <a href="{{ route('admin.jobs.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">Reset</a>
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
                        <th>Job Title</th>
                        <th>Organization</th>
                        <th>Type / Location</th>
                        <th>Featured</th>
                        <th>Status</th>
                        <th>Posted Date</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($opportunities as $opp)
                    <tr>
                        <td>
                            <strong class="d-block text-dark small"><a href="{{ route('opportunities.show', $opp->slug) }}" target="_blank" class="text-dark text-decoration-none hover-primary">{{ $opp->title }}</a></strong>
                            <span class="text-muted" style="font-size: 0.72rem;">{{ strtoupper($opp->type) }} &bull; {{ $opp->category ?? 'General' }}</span>
                        </td>
                        <td>
                            <span class="text-dark small fw-semibold">{{ $opp->company->name ?? 'Organization' }}</span>
                        </td>
                        <td>
                            <span class="text-muted small">{{ ucfirst($opp->location_type) }} &bull; {{ $opp->city ?? 'Remote' }}</span>
                        </td>
                        <td>
                            <form action="{{ route('admin.opportunities.feature', $opp->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm {{ $opp->is_featured ? 'btn-warning text-dark' : 'btn-outline-secondary' }} py-0 px-2 rounded-pill" style="font-size: 0.72rem;">
                                    <i class="fa-solid fa-star me-1"></i> {{ $opp->is_featured ? 'Featured' : 'Standard' }}
                                </button>
                            </form>
                        </td>
                        <td>
                            @if($opp->status === 'published')
                                <span class="badge bg-success-subtle text-success">Published</span>
                            @elseif($opp->status === 'draft')
                                <span class="badge bg-warning-subtle text-dark">Draft / Pending</span>
                            @elseif($opp->status === 'closed')
                                <span class="badge bg-secondary-subtle text-dark">Closed</span>
                            @else
                                <span class="badge bg-danger">Suspended</span>
                            @endif
                        </td>
                        <td>
                            <span class="text-muted small">{{ $opp->created_at->format('M d, Y') }}</span>
                        </td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-outline-primary rounded-pill" data-bs-toggle="modal" data-bs-target="#editJobModal{{ $opp->id }}">Update Status</button>

                            <!-- Update Job Status Modal -->
                            <div class="modal fade text-start" id="editJobModal{{ $opp->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow">
                                        <form action="{{ route('admin.jobs.status', $opp->id) }}" method="POST">
                                            @csrf
                                            <div class="modal-header">
                                                <h5 class="modal-title fw-bold">Update Job Status &mdash; {{ $opp->title }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Opportunity Status</label>
                                                    <select name="status" class="form-select" required>
                                                        <option value="published" {{ $opp->status === 'published' ? 'selected' : '' }}>Published (Live on Platform)</option>
                                                        <option value="draft" {{ $opp->status === 'draft' ? 'selected' : '' }}>Draft / Pending Review</option>
                                                        <option value="paused" {{ $opp->status === 'paused' ? 'selected' : '' }}>Paused</option>
                                                        <option value="closed" {{ $opp->status === 'closed' ? 'selected' : '' }}>Closed</option>
                                                        <option value="suspended" {{ $opp->status === 'suspended' ? 'selected' : '' }}>Suspended</option>
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
                        <td colspan="7" class="text-center py-4 text-muted">No opportunities found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3 border-top">
            {{ $opportunities->links() }}
        </div>
    </div>
</div>
@endsection

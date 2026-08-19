@extends('layouts.admin')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
    <div>
        <h1 class="h3 fw-bold text-dark mb-1"><i class="fa-solid fa-users me-2 text-primary"></i> Professional Management</h1>
        <p class="text-secondary small mb-0">Directory of all registered candidates, profile verification, status controls, and security audit.</p>
    </div>
</div>

<!-- Filters Bar -->
<div class="card border-0 shadow-sm p-3 bg-white mb-4">
    <form action="{{ route('admin.professionals.index') }}" method="GET" class="row g-3 align-items-center">
        <div class="col-md-5">
            <input type="text" name="search" class="form-control form-control-sm" placeholder="Search by name, email or username..." value="{{ request('search') }}">
        </div>
        <div class="col-md-4">
            <select name="status" class="form-select form-select-sm">
                <option value="">All Account Statuses</option>
                <option value="verified" {{ request('status') === 'verified' ? 'selected' : '' }}>Email Verified</option>
                <option value="unverified" {{ request('status') === 'unverified' ? 'selected' : '' }}>Unverified Email</option>
                <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspended Accounts</option>
            </select>
        </div>
        <div class="col-md-3 text-md-end">
            <button type="submit" class="btn btn-sm btn-primary rounded-pill px-3 me-1">Filter</button>
            <a href="{{ route('admin.professionals.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">Reset</a>
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
                        <th>Professional</th>
                        <th>Email / Username</th>
                        <th>Position / Title</th>
                        <th>Email Verified</th>
                        <th>Account Status</th>
                        <th>Joined</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($professionals as $user)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle text-white d-flex align-items-center justify-content-center fw-bold small" style="width: 32px; height: 32px; background-color: var(--brand-primary);">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <strong class="d-block text-dark small">{{ $user->name }}</strong>
                                    @if($user->isAdmin())
                                        <span class="badge bg-danger-subtle text-danger" style="font-size: 0.65rem;">ADMIN</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="text-dark small d-block">{{ $user->email }}</span>
                            <span class="text-muted" style="font-size: 0.72rem;">&#064;{{ $user->username }}</span>
                        </td>
                        <td>
                            <span class="text-dark small">{{ $user->portfolio->position ?? 'Professional' }}</span>
                        </td>
                        <td>
                            @if($user->email_verified_at)
                                <span class="badge bg-success-subtle text-success"><i class="fa-solid fa-check me-1"></i> Verified</span>
                            @else
                                <span class="badge bg-warning-subtle text-dark">Unverified</span>
                            @endif
                        </td>
                        <td>
                            @if($user->isSuspended())
                                <span class="badge bg-danger">Suspended</span>
                            @else
                                <span class="badge bg-success-subtle text-success">Active</span>
                            @endif
                        </td>
                        <td>
                            <span class="text-muted small">{{ $user->created_at->format('M d, Y') }}</span>
                        </td>
                        <td class="text-end">
                            <div class="dropdown d-inline">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle py-1 px-2" type="button" data-bs-toggle="dropdown">Actions</button>
                                <ul class="dropdown-menu dropdown-menu-end shadow-sm" style="font-size: 0.8125rem;">
                                    <li><a class="dropdown-item" href="{{ route('admin.professionals.show', $user->id) }}"><i class="fa-solid fa-user-gear me-2 text-primary"></i> Detailed Inspector</a></li>
                                    <li><a class="dropdown-item" href="{{ route('portfolio.show', $user->username) }}" target="_blank"><i class="fa-solid fa-eye me-2 text-info"></i> View Public Portfolio</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('admin.users.toggle-verification', $user->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="dropdown-item"><i class="fa-solid fa-shield-halved me-2 text-warning"></i> Toggle Email Verification</button>
                                        </form>
                                    </li>
                                    <li>
                                        <form action="{{ route('admin.professionals.suspend', $user->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="dropdown-item text-warning" onclick="return confirm('Change suspension status for this user?');">
                                                <i class="fa-solid fa-ban me-2"></i> {{ $user->isSuspended() ? 'Unsuspend Account' : 'Suspend Account' }}
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">No professionals found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3 border-top">
            {{ $professionals->links() }}
        </div>
    </div>
</div>
@endsection

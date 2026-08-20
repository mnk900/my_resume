@extends('layouts.admin')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
    <div>
        <h1 class="h3 fw-bold text-dark mb-1"><i class="fa-solid fa-users me-2 text-primary"></i> Professional Management</h1>
        <p class="text-secondary small mb-0">Directory of all registered candidates, profile verification, status controls, and security audit.</p>
    </div>
</div>

@if(session('status') === 'direct-email-sent')
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
        <i class="fa-solid fa-circle-check me-2"></i> Direct email successfully sent to <strong>{{ session('notified_user', 'user') }}</strong>.
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@elseif(session('status'))
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
        <i class="fa-solid fa-circle-check me-2"></i> {{ session('status') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
        <i class="fa-solid fa-triangle-exclamation me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

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
                                    <li><button type="button" class="dropdown-item text-success" data-bs-toggle="modal" data-bs-target="#sendEmailModal-{{ $user->id }}"><i class="fa-solid fa-envelope me-2 text-success"></i> Send Email to User</button></li>
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

<!-- Send Direct Email Modals (outside table context) -->
@foreach($professionals as $user)
<div class="modal fade text-start" id="sendEmailModal-{{ $user->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom py-3">
                <h5 class="modal-title fw-bold text-dark"><i class="fa-solid fa-envelope text-primary me-2"></i> Send Direct Email to {{ $user->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.send-email') }}" method="POST">
                @csrf
                <input type="hidden" name="recipient" value="{{ $user->id }}">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Recipient Email</label>
                        <input type="text" class="form-control bg-light" value="{{ $user->email }} ({{ $user->name }})" disabled>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email Subject <span class="text-danger">*</span></label>
                        <input type="text" name="subject" class="form-control" placeholder="e.g. Account Update / Important Notice" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Message Content <span class="text-danger">*</span></label>
                        <textarea name="message" class="form-control" rows="5" placeholder="Write your email message here..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top py-3">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4 fw-bold shadow-sm"><i class="fa-solid fa-paper-plane me-1"></i> Send Email</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
@endsection

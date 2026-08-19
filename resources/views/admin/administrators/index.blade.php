@extends('layouts.admin')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
    <div>
        <h1 class="h3 fw-bold text-dark mb-1"><i class="fa-solid fa-user-shield me-2 text-primary"></i> Administrators & Permissions Governance</h1>
        <p class="text-secondary small mb-0">Manage administrative authority, assign granular roles (Super Admin, Admin, Moderator, Support), and review access permissions.</p>
    </div>
</div>

<!-- Admin Users Table -->
<div class="card border-0 shadow-sm bg-white mb-4">
    <div class="card-header bg-white border-bottom py-3">
        <h6 class="fw-bold text-dark mb-0"><i class="fa-solid fa-users-gear text-primary me-2"></i> Current Platform Administrators</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Administrator</th>
                        <th>Email / Username</th>
                        <th>Assigned Admin Role</th>
                        <th>Account Status</th>
                        <th class="text-end">Assign Role</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($administrators as $admin)
                    <tr>
                        <td>
                            <strong class="d-block text-dark small">{{ $admin->name }}</strong>
                        </td>
                        <td>
                            <span class="text-dark small d-block">{{ $admin->email }}</span>
                            <span class="text-muted" style="font-size: 0.72rem;">&#064;{{ $admin->username }}</span>
                        </td>
                        <td>
                            <span class="badge bg-danger-subtle text-danger border border-danger">{{ strtoupper($admin->admin_role ?? 'Super Admin') }}</span>
                        </td>
                        <td>
                            @if($admin->isSuspended())
                                <span class="badge bg-danger">Suspended</span>
                            @else
                                <span class="badge bg-success-subtle text-success">Active</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-outline-primary rounded-pill" data-bs-toggle="modal" data-bs-target="#editRoleModal{{ $admin->id }}">Change Role</button>

                            <!-- Change Role Modal -->
                            <div class="modal fade text-start" id="editRoleModal{{ $admin->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow">
                                        <form action="{{ route('admin.administrators.update-role', $admin->id) }}" method="POST">
                                            @csrf
                                            <div class="modal-header">
                                                <h5 class="modal-title fw-bold">Assign Admin Role &mdash; {{ $admin->name }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Administrative Authority Role</label>
                                                    <select name="admin_role" class="form-select" required>
                                                        <option value="super_admin" {{ ($admin->admin_role ?? 'super_admin') === 'super_admin' ? 'selected' : '' }}>Super Admin (Highest Authority)</option>
                                                        <option value="admin" {{ $admin->admin_role === 'admin' ? 'selected' : '' }}>Administrator (Operational Authority)</option>
                                                        <option value="moderator" {{ $admin->admin_role === 'moderator' ? 'selected' : '' }}>Moderator (Content & Reports)</option>
                                                        <option value="support" {{ $admin->admin_role === 'support' ? 'selected' : '' }}>Support Admin (User Issues)</option>
                                                        <option value="none">Revoke Administrative Access</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-primary rounded-pill px-4">Update Role</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">No administrators registered.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Role Permission Matrix -->
<div class="card border-0 shadow-sm bg-white p-3">
    <h6 class="fw-bold text-dark mb-3"><i class="fa-solid fa-layer-group text-primary me-2"></i> Role Permission Matrix Architecture</h6>
    <div class="table-responsive">
        <table class="table table-bordered align-middle mb-0 text-center" style="font-size: 0.8125rem;">
            <thead class="table-light">
                <tr>
                    <th class="text-start">Permission Scope</th>
                    <th>Super Admin</th>
                    <th>Administrator</th>
                    <th>Moderator</th>
                    <th>Support Admin</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-start fw-semibold">Manage Admins & Roles</td>
                    <td><i class="fa-solid fa-check text-success"></i></td>
                    <td><i class="fa-solid fa-xmark text-muted"></i></td>
                    <td><i class="fa-solid fa-xmark text-muted"></i></td>
                    <td><i class="fa-solid fa-xmark text-muted"></i></td>
                </tr>
                <tr>
                    <td class="text-start fw-semibold">Verify Professionals & Companies</td>
                    <td><i class="fa-solid fa-check text-success"></i></td>
                    <td><i class="fa-solid fa-check text-success"></i></td>
                    <td><i class="fa-solid fa-xmark text-muted"></i></td>
                    <td><i class="fa-solid fa-check text-success"></i></td>
                </tr>
                <tr>
                    <td class="text-start fw-semibold">Approve Jobs & Opportunities</td>
                    <td><i class="fa-solid fa-check text-success"></i></td>
                    <td><i class="fa-solid fa-check text-success"></i></td>
                    <td><i class="fa-solid fa-check text-success"></i></td>
                    <td><i class="fa-solid fa-xmark text-muted"></i></td>
                </tr>
                <tr>
                    <td class="text-start fw-semibold">Content Moderation & Reports</td>
                    <td><i class="fa-solid fa-check text-success"></i></td>
                    <td><i class="fa-solid fa-check text-success"></i></td>
                    <td><i class="fa-solid fa-check text-success"></i></td>
                    <td><i class="fa-solid fa-xmark text-muted"></i></td>
                </tr>
                <tr>
                    <td class="text-start fw-semibold">View Audit Logs & System Health</td>
                    <td><i class="fa-solid fa-check text-success"></i></td>
                    <td><i class="fa-solid fa-check text-success"></i></td>
                    <td><i class="fa-solid fa-xmark text-muted"></i></td>
                    <td><i class="fa-solid fa-xmark text-muted"></i></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection

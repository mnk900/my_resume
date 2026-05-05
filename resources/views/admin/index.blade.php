<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0">{{ __('Admin Intelligence Center') }}</h2>
            <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#broadcastModal">
                📢 Broadcast Message
            </button>
        </div>
    </x-slot>

    <!-- Stats Cards -->
    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-primary text-white h-100">
                <div class="card-body">
                    <h6 class="opacity-75">Total Users</h6>
                    <h2 class="fw-bold mb-0">{{ $stats['total_users'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-success text-white h-100">
                <div class="card-body">
                    <h6 class="opacity-75">Active Portfolios</h6>
                    <h2 class="fw-bold mb-0">{{ $stats['active_portfolios'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-info text-white h-100">
                <div class="card-body">
                    <h6 class="opacity-75">Verified Reach</h6>
                    <h2 class="fw-bold mb-0">{{ $stats['verified_users'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-warning text-dark h-100">
                <div class="card-body">
                    <h6 class="opacity-75">Themes Deployed</h6>
                    <h2 class="fw-bold mb-0">{{ $stats['total_themes'] }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row gx-4 gy-4">
        <!-- User Management -->
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">Portfolio Moderation & User Control</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Identity</th>
                                    <th>Verification</th>
                                    <th>Portfolio Content</th>
                                    <th>Status</th>
                                    <th>Discovery Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $u)
                                    <tr>
                                        <td>
                                            <div class="fw-bold">{{ $u->name }}</div>
                                            <small class="text-muted">{{ $u->email }}</small>
                                            @if($u->isAdmin()) <span class="badge bg-dark ms-1 small">Admin</span> @endif
                                        </td>
                                        <td>
                                            @if($u->email_verified_at) 
                                                <span class="text-success small">✔ Verified</span> 
                                            @else 
                                                <span class="text-warning small">⏳ Pending</span> 
                                            @endif
                                        </td>
                                        <td>
                                            @if($u->portfolio)
                                                <a href="{{ route('portfolio.show', $u->username) }}" target="_blank" class="text-decoration-none">/{{ $u->username }}</a>
                                                <div class="small text-muted">{{ $u->portfolio->sections_count ?? 0 }} Modules configured</div>
                                            @else
                                                <span class="text-muted small italic">No profile created</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($u->portfolio)
                                                <form action="{{ route('admin.portfolio.toggle', $u->portfolio) }}" method="POST">
                                                    @csrf
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" role="switch" onchange="this.form.submit()" {{ $u->portfolio->is_active ? 'checked' : '' }}>
                                                        <label class="form-check-label {{ $u->portfolio->is_active ? 'text-success' : 'text-danger' }}">
                                                            {{ $u->portfolio->is_active ? 'Public' : 'Hidden' }}
                                                        </label>
                                                    </div>
                                                </form>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#notifyModal-{{ $u->id }}">Notify</button>
                                        </td>
                                    </tr>

                                    <!-- Notify Modal -->
                                    <div class="modal fade" id="notifyModal-{{ $u->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <form class="modal-content shadow-lg border-0" action="{{ route('admin.notify') }}" method="POST">
                                                @csrf
                                                <div class="modal-header border-0 bg-info text-white">
                                                    <h5 class="modal-title">Direct Notification: {{ $u->name }}</h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <input type="hidden" name="user_id" value="{{ $u->id }}">
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold">Message Content</label>
                                                        <textarea name="message" class="form-control" rows="4" placeholder="Type instructions or feedback..." required></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer border-0">
                                                    <button type="submit" class="btn btn-info text-white w-100 py-2">Send Secure Message</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            @if(session('status') === 'notification-sent')
                <div class="alert alert-info alert-dismissible fade show mt-3 border-0 shadow-sm" role="alert">
                    Direct notification dispatched to logged queues.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>

        <!-- Theme Management -->
        <div class="col-12 mt-2">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <h5 class="mb-0 fw-bold">Global Theme Availability</h5>
                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addThemeModal">+ Add Layout</button>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="table-light small text-uppercase">
                            <tr>
                                <th>Name</th>
                                <th>Slug Binding</th>
                                <th>Deployment Status</th>
                                <th>Control</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($themes as $theme)
                                <tr>
                                    <td class="fw-bold">{{ $theme->name }}</td>
                                    <td class="text-muted"><code>{{ $theme->slug }}</code></td>
                                    <td>
                                        <span class="badge rounded-pill bg-{{ $theme->is_active ? 'success' : 'danger' }}">
                                            {{ $theme->is_active ? 'Live' : 'Maintenance' }}
                                        </span>
                                    </td>
                                    <td>
                                        <form action="{{ route('admin.themes.toggle', $theme) }}" method="POST">
                                            @csrf
                                            <button class="btn btn-sm btn-link p-0 text-{{ $theme->is_active ? 'danger' : 'success' }} text-decoration-none">
                                                {{ $theme->is_active ? 'Disable Access' : 'Enable Access' }}
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Broadcast Modal -->
    <div class="modal fade" id="broadcastModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form class="modal-content shadow-lg border-0" action="{{ route('admin.broadcast') }}" method="POST">
                @csrf
                <div class="modal-header border-0 bg-primary text-white">
                    <h5 class="modal-title">System-Wide Broadcast Email</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Subject Line</label>
                        <input type="text" name="subject" class="form-control" placeholder="e.g. Platform Maintenance Saturday" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Email Content (HTML allowed)</label>
                        <textarea name="message" class="form-control" rows="8" placeholder="Enter your global announcement here..." required></textarea>
                    </div>
                    <div class="alert alert-warning py-2 mb-0 border-0">
                        <small><strong>Note:</strong> This will dispatch an email to ALL {{ $stats['total_users'] }} registered users simultaneously.</small>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-primary w-100 py-3 fw-bold">🚀 Dispatch Broadcast Now</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Theme Modal -->
    <div class="modal fade" id="addThemeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content border-0 shadow-lg" action="{{ route('admin.themes.store') }}" method="POST">
                @csrf
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">Register New Theme Layout</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Layout Internal Name</label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Cyberpunk Blue" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">FileSystem Slug Binding</label>
                        <input type="text" name="slug" class="form-control" placeholder="e.g. cyberpunk" required>
                        <small class="text-muted">Must match a filename in <code>portfolio/themes/</code>.</small>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-primary w-100 p-2">Save Layout</button>
                </div>
            </form>
        </div>
    </div>
    
    @if(session('status') === 'broadcast-sent')
        <div class="toast-container position-fixed bottom-0 end-0 p-3">
            <div class="toast show align-items-center text-white bg-primary border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        Broadcast successfully queued for delivery.
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        </div>
    @endif

</x-app-layout>

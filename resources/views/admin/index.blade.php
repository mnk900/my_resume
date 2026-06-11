<x-app-layout>
    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        <style>
            /* Fluid main container */
            @media (min-width: 1200px) {
                main.container {
                    max-width: 100% !important;
                    width: 100% !important;
                    padding-left: 0 !important;
                    padding-right: 0 !important;
                }
            }

            #adminSidebarMenu {
                background-color: #222d32 !important;
                width: 260px;
                min-width: 260px;
            }

            @media (min-width: 768px) {
                #adminSidebarMenu {
                    min-height: calc(100vh - 56px);
                }
            }

            .sidebar-menu .nav-link {
                color: #b8c7ce !important;
                border-radius: 4px;
                transition: all 0.2s;
                font-weight: 500;
            }

            .sidebar-menu .nav-link:hover {
                color: #fff !important;
                background-color: rgba(255, 255, 255, 0.1) !important;
            }

            .sidebar-menu .nav-link.active {
                color: #fff !important;
                background-color: #3c8dbc !important;
                border-left: 3px solid #00c0ef;
            }

            /* Stat boxes inspired by AdminLTE */
            .small-box {
                position: relative;
                display: block;
                margin-bottom: 20px;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
                border-radius: 8px;
                color: #fff;
                padding: 20px;
                overflow: hidden;
                transition: transform 0.3s ease, box-shadow 0.3s ease;
            }

            .small-box:hover {
                transform: translateY(-4px);
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -4px rgba(0, 0, 0, 0.05);
            }

            .small-box .inner h3 {
                font-size: 2.5rem;
                font-weight: bold;
                margin: 0 0 5px 0;
            }

            .small-box .inner p {
                font-size: 0.95rem;
                margin: 0;
                opacity: 0.9;
            }

            .small-box .icon {
                position: absolute;
                top: 10px;
                right: 15px;
                z-index: 0;
                font-size: 4rem;
                color: rgba(0, 0, 0, 0.15);
                transition: all 0.3s linear;
            }

            .small-box:hover .icon {
                font-size: 4.5rem;
                transform: scale(1.1);
            }

            .note-editor.note-frame {
                border-color: #dee2e6;
            }
        </style>
    @endpush

    <!-- Mobile Admin Header (Visible only on mobile) -->
    <div class="bg-white border-bottom p-2 d-md-none d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center">
            <button class="btn btn-outline-secondary me-2 py-1 px-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#adminSidebarMenu" aria-controls="adminSidebarMenu">
                <i class="bi bi-list fs-5"></i>
            </button>
            <span class="fw-bold">Admin Control Panel</span>
        </div>
        <div class="small-badge text-danger small"><i class="bi bi-shield-fill me-1" style="font-size: 0.5rem;"></i> Admin</div>
    </div>

    <div class="d-flex flex-column flex-md-row" id="dashboard-wrapper">
        <!-- Sidebar Navigation (Responsive Offcanvas) -->
        <div class="offcanvas-md offcanvas-start bg-dark text-white p-3 d-flex flex-column" tabindex="-1" id="adminSidebarMenu" aria-labelledby="adminSidebarMenuLabel" style="width: 260px; min-width: 260px;">
            <div class="offcanvas-header d-md-none border-bottom mb-3">
                <h5 class="offcanvas-title text-white fw-bold" id="adminSidebarMenuLabel">Admin Navigation</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" data-bs-target="#adminSidebarMenu" aria-label="Close"></button>
            </div>
            <div class="brand-link text-center mb-4 pb-3 border-bottom text-white text-decoration-none d-none d-md-block">
                <span class="brand-text fw-bold fs-5">Admin Control Panel</span>
            </div>
            <div class="user-panel d-flex align-items-center mb-4 pb-3 border-bottom">
                <div class="image me-3">
                    <div class="rounded-circle bg-danger d-flex align-items-center justify-content-center text-white fw-bold" style="width: 40px; height: 40px;">
                        AD
                    </div>
                </div>
                <div class="info">
                    <a href="#" class="d-block text-white text-decoration-none fw-semibold text-truncate" style="max-width: 160px;">{{ Auth::user()->name }}</a>
                    <span class="text-danger small"><i class="bi bi-shield-fill me-1" style="font-size: 0.6rem;"></i> Administrator</span>
                </div>
            </div>

            <!-- Admin Sidebar Menu -->
            <ul class="nav nav-pills flex-column mb-auto sidebar-menu" id="adminTabs" role="tablist">
                <li class="nav-item mb-2">
                    <button class="nav-link active text-start text-white w-100 d-flex align-items-center gap-2 border-0 bg-transparent py-2 px-3" id="admin-dashboard-tab" data-bs-toggle="tab" data-bs-target="#adminDashboardPane" type="button" role="tab">
                        <i class="bi bi-speedometer2"></i> <span>Dashboard</span>
                    </button>
                </li>
                <li class="nav-item mb-2">
                    <button class="nav-link text-start text-white w-100 d-flex align-items-center gap-2 border-0 bg-transparent py-2 px-3" id="admin-users-tab" data-bs-toggle="tab" data-bs-target="#adminUsersPane" type="button" role="tab">
                        <i class="bi bi-people-fill"></i> <span>User Management</span>
                    </button>
                </li>
                <li class="nav-item mb-2">
                    <button class="nav-link text-start text-white w-100 d-flex align-items-center gap-2 border-0 bg-transparent py-2 px-3" id="admin-themes-tab" data-bs-toggle="tab" data-bs-target="#adminThemesPane" type="button" role="tab">
                        <i class="bi bi-palette-fill"></i> <span>Theme Management</span>
                    </button>
                </li>
            </ul>
        </div>

        <!-- Right Content Wrapper -->
        <div class="flex-grow-1 p-4 bg-light overflow-auto" id="content-pane-wrapper">
            <!-- Toast notification messages -->
            @if (session('status'))
                <div class="alert alert-success alert-dismissible shadow-sm border-0 fade show mb-4" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    {{ str_replace('-', ' ', ucfirst(session('status'))) }} successfully.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible shadow-sm border-0 fade show mb-4" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    {{ str_replace('-', ' ', ucfirst(session('error'))) }}.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="tab-content" id="adminTabsContent">
                <!-- 1. DASHBOARD TAB PANE -->
                <div class="tab-pane fade show active" id="adminDashboardPane" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3 class="fw-bold mb-0">System Analytics</h3>
                        <button class="btn btn-primary btn-sm shadow-sm" data-bs-toggle="modal" data-bs-target="#broadcastModal">
                            📢 Broadcast Message
                        </button>
                    </div>

                    <!-- Small Infographics widgets -->
                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-3 mb-4">
                        <div class="col">
                            <div class="small-box" style="background: linear-gradient(135deg, #1e3a8a, #3b82f6);">
                                <div class="inner">
                                    <h3>{{ $stats['total_users'] }}</h3>
                                    <p>Total Users</p>
                                </div>
                                <div class="icon"><i class="bi bi-people"></i></div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="small-box" style="background: linear-gradient(135deg, #065f46, #10b981);">
                                <div class="inner">
                                    <h3>{{ $stats['active_portfolios'] }}</h3>
                                    <p>Active Portfolios</p>
                                </div>
                                <div class="icon"><i class="bi bi-folder-check"></i></div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="small-box" style="background: linear-gradient(135deg, #0f766e, #14b8a6);">
                                <div class="inner">
                                    <h3>{{ $stats['verified_users'] }}</h3>
                                    <p>Verified Reach</p>
                                </div>
                                <div class="icon"><i class="bi bi-shield-check"></i></div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="small-box" style="background: linear-gradient(135deg, #7c2d12, #f97316);">
                                <div class="inner">
                                    <h3>{{ $stats['total_themes'] }}</h3>
                                    <p>Themes Deployed</p>
                                </div>
                                <div class="icon"><i class="bi bi-palette"></i></div>
                            </div>
                        </div>
                    </div>

                    <!-- Row for logs and activity feeds -->
                    <div class="row g-4">
                        <!-- Column 1: Recent User Registrations Feed -->
                        <div class="col-lg-6">
                            <div class="card border-0 shadow-sm rounded-3 mb-4 bg-white h-100">
                                <div class="card-header bg-white py-3">
                                    <h5 class="mb-0 fw-bold"><i class="bi bi-activity me-2 text-warning"></i>Recent Registrations Activity Log</h5>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>User Name</th>
                                                    <th>Registration Date</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($users->take(5) as $u)
                                                    <tr>
                                                        <td class="fw-bold">
                                                            <div>{{ $u->name }}</div>
                                                            <small class="text-muted" style="font-size: 0.75rem;">{{ $u->email }}</small>
                                                        </td>
                                                        <td>{{ $u->created_at->diffForHumans() }}</td>
                                                        <td>
                                                            <span class="badge bg-{{ $u->portfolio ? 'success-subtle text-success' : 'secondary-subtle text-secondary' }}">
                                                                {{ $u->portfolio ? 'Profile Created' : 'No Profile' }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Column 2: Recent Platform-Wide Messages Feed -->
                        <div class="col-lg-6">
                            <div class="card border-0 shadow-sm rounded-3 mb-4 bg-white h-100">
                                <div class="card-header bg-white py-3">
                                    <h5 class="mb-0 fw-bold"><i class="bi bi-bell me-2 text-primary"></i>Recent Platform Notifications</h5>
                                </div>
                                <div class="card-body p-3">
                                    @if($messages->isEmpty())
                                        <div class="text-center py-5 text-muted">
                                            <i class="bi bi-chat-left-dots fs-1 d-block mb-3 text-secondary"></i>
                                            <p class="mb-0">No contact messages submitted across portfolios yet.</p>
                                        </div>
                                    @else
                                        <div class="list-group list-group-flush">
                                            @foreach($messages as $msg)
                                                <div class="list-group-item px-0 py-2 border-0 border-bottom">
                                                    <div class="d-flex w-100 justify-content-between align-items-center mb-1">
                                                        <strong class="text-dark small">{{ $msg->name }} ({{ $msg->email }})</strong>
                                                        <small class="text-muted" style="font-size: 0.75rem;">{{ $msg->created_at->diffForHumans() }}</small>
                                                    </div>
                                                    <p class="mb-1 text-secondary small text-truncate" style="max-width: 450px;">{{ strip_tags($msg->message) }}</p>
                                                    <small class="text-primary-emphasis bg-primary-subtle px-2 py-0.5 rounded-pill" style="font-size: 0.7rem;">
                                                        To: {{ $msg->portfolio->user->name ?? 'User' }}
                                                    </small>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. USER MANAGEMENT TAB PANE -->
                <div class="tab-pane fade" id="adminUsersPane" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3 class="fw-bold mb-0">User Management</h3>
                    </div>

                    <!-- Search and Paginate registered users -->
                    <div class="card shadow-sm border-0 rounded-3 mb-4 bg-white py-2 px-3">
                        <div class="d-flex align-items-center gap-2">
                            <div class="input-group input-group-sm" style="max-width: 300px;">
                                <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                                <input type="text" id="userTableSearch" class="form-control border-start-0" placeholder="Search users by name, email...">
                            </div>
                            <div class="ms-auto" id="userTablePagination"></div>
                        </div>
                    </div>

                    <!-- Users list table -->
                    <div class="card shadow-sm border-0 rounded-3 mb-4 bg-white">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0" id="usersTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Identity</th>
                                            <th>Verification</th>
                                            <th>Portfolio Link</th>
                                            <th>Status switch</th>
                                            <th>Direct Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($users as $u)
                                            <tr class="user-row-item">
                                                <td>
                                                    <div class="fw-bold text-dark mb-1">{{ $u->name }}</div>
                                                    <small class="text-muted d-block mb-1">{{ $u->email }}</small>
                                                    
                                                    <!-- Toggable Admin Role Switch -->
                                                    <form action="{{ route('admin.users.toggle-role', $u) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <div class="form-check form-switch d-inline-block align-middle">
                                                            <input class="form-check-input" type="checkbox" role="switch" onchange="this.form.submit()" {{ $u->isAdmin() ? 'checked' : '' }} {{ $u->id === auth()->id() ? 'disabled' : '' }}>
                                                            <span class="badge bg-{{ $u->isAdmin() ? 'dark' : 'secondary' }} small">
                                                                {{ $u->role }}
                                                            </span>
                                                        </div>
                                                    </form>
                                                </td>
                                                <td>
                                                    <!-- Toggable Manual Verification Switch -->
                                                    <form action="{{ route('admin.users.toggle-verification', $u) }}" method="POST">
                                                        @csrf
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input" type="checkbox" role="switch" onchange="this.form.submit()" {{ $u->email_verified_at ? 'checked' : '' }}>
                                                            <span class="text-{{ $u->email_verified_at ? 'success' : 'warning' }} small font-semibold">
                                                                <i class="bi bi-{{ $u->email_verified_at ? 'patch-check-fill' : 'hourglass-split' }} me-1"></i>{{ $u->email_verified_at ? 'Verified' : 'Pending' }}
                                                            </span>
                                                        </div>
                                                    </form>
                                                </td>
                                                <td>
                                                    @if($u->portfolio)
                                                        <a href="{{ route('portfolio.show', $u->username) }}" target="_blank" class="text-decoration-none">/{{ $u->username }}</a>
                                                    @else
                                                        <span class="text-muted small italic">No profile</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($u->portfolio)
                                                        <form action="{{ route('admin.portfolio.toggle', $u->portfolio) }}" method="POST">
                                                            @csrf
                                                            <div class="form-check form-switch">
                                                                <input class="form-check-input" type="checkbox" role="switch" onchange="this.form.submit()" {{ $u->portfolio->is_active ? 'checked' : '' }}>
                                                                <label class="form-check-label {{ $u->portfolio->is_active ? 'text-success' : 'text-danger' }} small">
                                                                    {{ $u->portfolio->is_active ? 'Active' : 'Hidden' }}
                                                                </label>
                                                            </div>
                                                        </form>
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#notifyModal-{{ $u->id }}">Notify</button>
                                                        @if($u->id !== auth()->id())
                                                            <form action="{{ route('admin.users.destroy', $u) }}" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete this user, their portfolio, and all their sections?')">
                                                                @csrf @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete User Account"><i class="bi bi-trash"></i></button>
                                                            </form>
                                                        @endif
                                                    </div>
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
                                                                <textarea name="message" class="form-control js-summernote" data-height="120" placeholder="Type instructions or feedback..." required></textarea>
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

                    <!-- Email Dispatch Card -->
                    <div class="card shadow-sm border-0 rounded-3 mb-4 bg-white">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0 fw-bold">📧 Direct Email Center</h5>
                        </div>
                        <div class="card-body p-4">
                            <form action="{{ route('admin.send-email') }}" method="POST">
                                @csrf
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Recipient</label>
                                            <select name="recipient" class="form-select" required>
                                                <option value="all">📢 Send to All Users (Broadcast)</option>
                                                @foreach($users as $u)
                                                    <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Subject Line</label>
                                            <input type="text" name="subject" class="form-control" placeholder="e.g. System Updates Required" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Message Content (HTML allowed)</label>
                                    <textarea name="message" class="form-control js-summernote" data-height="160" placeholder="Type your email body message here..." required></textarea>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary px-4 py-2 fw-bold shadow-sm">
                                        ✉ Dispatch Email
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- 3. THEME MANAGEMENT TAB PANE -->
                <div class="tab-pane fade" id="adminThemesPane" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3 class="fw-bold mb-0">Theme Configuration</h3>
                        <button class="btn btn-primary btn-sm shadow-sm" data-bs-toggle="modal" data-bs-target="#addThemeModal">+ Add Layout Theme</button>
                    </div>

                    <!-- Theme configuration table -->
                    <div class="card shadow-sm border-0 rounded-3 mb-4 bg-white">
                        <div class="card-body p-0">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Theme Name</th>
                                        <th>FileSystem Slug</th>
                                        <th>Availability Status</th>
                                        <th>Action Control</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($themes as $theme)
                                        <tr>
                                            <td class="fw-bold">{{ $theme->name }}</td>
                                            <td><code>{{ $theme->slug }}</code></td>
                                            <td>
                                                <span class="badge rounded-pill bg-{{ $theme->is_active ? 'success' : 'danger' }}">
                                                    {{ $theme->is_active ? 'Live' : 'Maintenance' }}
                                                </span>
                                            </td>
                                            <td>
                                                <form action="{{ route('admin.themes.toggle', $theme) }}" method="POST">
                                                    @csrf
                                                    <button class="btn btn-sm btn-link p-0 text-{{ $theme->is_active ? 'danger' : 'success' }} text-decoration-none fw-bold">
                                                        {{ $theme->is_active ? 'Disable' : 'Enable' }}
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
        </div>
    </div>

    <!-- Global Broadcast Modal -->
    <div class="modal fade" id="broadcastModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form class="modal-content shadow-lg border-0" action="{{ route('admin.broadcast') }}" method="POST">
                @csrf
                <div class="modal-header border-0 bg-primary text-white">
                    <h5 class="modal-title">System-Wide Broadcast Announcement</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Subject Line</label>
                        <input type="text" name="subject" class="form-control" placeholder="e.g. Dashboard Redesign Launch" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Email Content (HTML allowed)</label>
                        <textarea name="message" class="form-control js-summernote" data-height="200" placeholder="Type announcement message here..." required></textarea>
                    </div>
                    <div class="alert alert-warning py-2 mb-0 border-0">
                        <small><strong>Note:</strong> This will dispatch an email broadcast to ALL registered portfolio users.</small>
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
                        <input type="text" name="name" class="form-control" placeholder="e.g. Glassmorphic Teal" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">FileSystem Slug Binding</label>
                        <input type="text" name="slug" class="form-control" placeholder="e.g. teal-glass" required>
                        <small class="text-muted">Must match a filename in <code>portfolio/themes/</code>.</small>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-primary w-100 p-2">Save Layout</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            // Client-side search and pagination engine for Admin user table
            function initAdminUserTable() {
                const searchInput = document.getElementById('userTableSearch');
                const tableBody = document.querySelector('#usersTable tbody');
                if (!tableBody) return;

                const rows = Array.from(tableBody.querySelectorAll('.user-row-item'));
                const paginationContainer = document.getElementById('userTablePagination');
                const itemsPerPage = 8;
                let currentPage = 1;
                let filteredRows = [...rows];

                function renderPage() {
                    const totalPages = Math.ceil(filteredRows.length / itemsPerPage);
                    
                    // Hide all
                    rows.forEach(r => r.style.display = 'none');

                    // Show current page items
                    const start = (currentPage - 1) * itemsPerPage;
                    const end = start + itemsPerPage;

                    filteredRows.slice(start, end).forEach(r => {
                        r.style.display = '';
                    });

                    // Render pagination links
                    if (paginationContainer) {
                        paginationContainer.innerHTML = '';
                        if (totalPages > 1) {
                            const nav = document.createElement('nav');
                            const ul = document.createElement('ul');
                            ul.className = 'pagination pagination-sm mb-0 justify-content-end';

                            // Previous
                            const prevLi = document.createElement('li');
                            prevLi.className = `page-item ${currentPage === 1 ? 'disabled' : ''}`;
                            prevLi.innerHTML = `<a class="page-link" href="javascript:void(0)" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a>`;
                            prevLi.addEventListener('click', () => {
                                if (currentPage > 1) {
                                    currentPage--;
                                    renderPage();
                                }
                            });
                            ul.appendChild(prevLi);

                            // Pages
                            for (let i = 1; i <= totalPages; i++) {
                                const li = document.createElement('li');
                                li.className = `page-item ${i === currentPage ? 'active' : ''}`;
                                li.innerHTML = `<a class="page-link" href="javascript:void(0)">${i}</a>`;
                                li.addEventListener('click', () => {
                                    currentPage = i;
                                    renderPage();
                                });
                                ul.appendChild(li);
                            }

                            // Next
                            const nextLi = document.createElement('li');
                            nextLi.className = `page-item ${currentPage === totalPages ? 'disabled' : ''}`;
                            nextLi.innerHTML = `<a class="page-link" href="javascript:void(0)" aria-label="Next"><span aria-hidden="true">&raquo;</span></a>`;
                            nextLi.addEventListener('click', () => {
                                if (currentPage < totalPages) {
                                    currentPage++;
                                    renderPage();
                                }
                            });
                            ul.appendChild(nextLi);

                            nav.appendChild(ul);
                            paginationContainer.appendChild(nav);
                        }
                    }
                }

                if (searchInput) {
                    searchInput.addEventListener('input', function() {
                        const query = this.value.toLowerCase().trim();
                        filteredRows = rows.filter(r => {
                            const searchText = r.innerText.toLowerCase();
                            return searchText.includes(query);
                        });
                        currentPage = 1;
                        renderPage();
                    });
                }

                renderPage();
            }

            document.addEventListener('DOMContentLoaded', async function () {
                const initEditors = (scope = document) => {
                    const $ = window.jQuery;
                    $(scope).find('.js-summernote').each(function () {
                        const $el = $(this);
                        if ($el.next('.note-editor').length) {
                            return;
                        }

                        $el.summernote({
                            height: Number($el.data('height')) || 160,
                            placeholder: $el.attr('placeholder') || '',
                            toolbar: [
                                ['style', ['bold', 'italic', 'underline', 'clear']],
                                ['font', ['strikethrough', 'superscript', 'subscript']],
                                ['para', ['ul', 'ol', 'paragraph']],
                                ['insert', ['link']],
                                ['view', ['codeview']]
                            ]
                        });
                    });
                };

                const loadScript = (src) => new Promise((resolve, reject) => {
                    const script = document.createElement('script');
                    script.src = src;
                    script.onload = resolve;
                    script.onerror = reject;
                    document.head.appendChild(script);
                });

                try {
                    if (!window.jQuery) {
                        await loadScript('https://code.jquery.com/jquery-3.7.1.min.js');
                    }

                    if (!window.jQuery || !window.jQuery.fn || !window.jQuery.fn.summernote) {
                        await loadScript('https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.js');
                    }

                    initEditors(document);

                    window.jQuery(document).on('shown.bs.collapse', '.collapse', function () {
                        initEditors(this);
                    });

                    window.jQuery(document).on('shown.bs.modal', '.modal', function () {
                        initEditors(this);
                    });

                    window.jQuery(document).on('shown.bs.tab', 'button[data-bs-toggle="tab"], a[data-bs-toggle="tab"]', function () {
                        const target = window.jQuery(this).attr('data-bs-target') || window.jQuery(this).attr('href');
                        if (target) {
                            initEditors(window.jQuery(target));
                        }
                    });
                } catch (e) {
                    console.error('Summernote failed to load:', e);
                }

                // Run Admin User table pagination
                initAdminUserTable();

                // Auto-close responsive offcanvas sidebar on menu click (mobile view)
                const adminSidebarMenuEl = document.getElementById('adminSidebarMenu');
                if (adminSidebarMenuEl) {
                    adminSidebarMenuEl.querySelectorAll('.nav-link').forEach(link => {
                        link.addEventListener('click', () => {
                            const bsOffcanvas = bootstrap.Offcanvas.getInstance(adminSidebarMenuEl);
                            if (bsOffcanvas) {
                                bsOffcanvas.hide();
                            }
                        });
                    });
                }
            });
        </script>
    @endpush
</x-app-layout>

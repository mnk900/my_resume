<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'MyResume.cloud') }} — Control Center</title>

    <!-- Google Fonts: Outfit (Headings) & Inter (Body) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet">

    <!-- FontAwesome 6.4.0 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Bootstrap CSS 5.3.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --brand-primary: #4c75a1;
            --brand-primary-hover: #375c85;
            --brand-primary-active: #264568;
            --brand-secondary: #1e293b;
            --brand-accent: #b0c6db;
            --brand-tint: #f0f4f8;
            --brand-light: #f8fafc;
            --font-size-hero: 2.375rem;
            --font-size-h1: 1.625rem;
            --font-size-h2: 1.375rem;
            --font-size-h3: 1.125rem;
            --font-size-body: 0.875rem;
            --font-size-sm: 0.8125rem;
            --font-size-xs: 0.75rem;
            --font-size-badge: 0.6875rem;
            --border-color: #e2e8f0;
            --radius-sm: 6px;
            --radius-md: 10px;
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.05);
            --shadow-md: 0 4px 6px -1px rgba(0,0,0,0.08);
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            font-size: var(--font-size-body);
            color: #334155;
            background-color: #f1f5f9;
            line-height: 1.5;
        }

        h1, h2, h3, h4, h5, h6, .font-heading {
            font-family: 'Outfit', system-ui, -apple-system, sans-serif;
        }

        /* Top Admin Navbar */
        .admin-navbar {
            background-color: #ffffff !important;
            border-bottom: 1px solid var(--border-color);
            position: sticky;
            top: 0;
            z-index: 1040;
            height: 60px;
        }

        /* Admin Sidebar */
        #adminSidebar {
            background-color: #1e293b !important;
            width: 240px;
            min-width: 240px;
            color: #94a3b8;
        }

        .admin-sidebar-header {
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #64748b;
            margin-top: 1.25rem;
            margin-bottom: 0.35rem;
            padding-left: 0.75rem;
        }

        .admin-sidebar .nav-link {
            color: #cbd5e1 !important;
            font-size: var(--font-size-sm);
            font-weight: 500;
            padding: 0.5rem 0.75rem;
            border-radius: 6px;
            transition: all 0.15s ease;
            display: flex;
            align-items: center;
        }

        .admin-sidebar .nav-link:hover {
            color: #ffffff !important;
            background-color: rgba(255, 255, 255, 0.08) !important;
        }

        .admin-sidebar .nav-link.active {
            color: #ffffff !important;
            background-color: var(--brand-primary) !important;
            font-weight: 600;
        }

        .admin-sidebar .badge {
            font-size: 0.6875rem;
        }

        /* Content Workspace */
        .admin-workspace {
            flex-grow: 1;
            min-width: 0;
            background-color: #f8fafc;
        }

        .card {
            border-radius: var(--radius-md);
            border-color: var(--border-color);
            box-shadow: var(--shadow-sm);
        }

        .table { font-size: var(--font-size-sm); }
        .table th { font-size: var(--font-size-xs); font-weight: 600; text-transform: uppercase; letter-spacing: 0.03em; background-color: #f8fafc; }
        .badge { font-size: var(--font-size-badge); font-weight: 600; padding: 0.35rem 0.65rem; }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Top Control Center Navbar -->
    <nav class="navbar navbar-expand-lg admin-navbar px-3">
        <div class="container-fluid p-0 d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-sm btn-light border d-md-none" type="button" data-bs-toggle="collapse" data-bs-target="#adminSidebar">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <a href="{{ route('admin.index') }}" class="d-flex align-items-center text-decoration-none me-3">
                    <img src="{{ asset('images/logo.jpeg') }}" alt="MyResume.cloud" style="height: 34px;" class="rounded shadow-sm me-2">
                    <span class="fw-bold text-dark fs-6 font-heading">Control Center</span>
                    <span class="badge bg-danger-subtle text-danger ms-2 border border-danger-subtle" style="font-size: 0.65rem;">ADMIN</span>
                </a>
            </div>

            <!-- Global Control Search Form -->
            <form action="{{ route('admin.search') }}" method="GET" class="d-none d-md-flex align-items-center flex-grow-1 mx-4" style="max-width: 480px;">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <input type="text" name="q" class="form-control bg-light border-start-0 shadow-none" placeholder="Search professionals, companies, jobs, applications..." value="{{ request('q') }}">
                    <button class="btn btn-outline-secondary btn-sm" type="submit">Search</button>
                </div>
            </form>

            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('welcome') }}" target="_blank" class="btn btn-sm btn-outline-secondary rounded-pill d-none d-sm-inline-flex align-items-center">
                    <i class="fa-solid fa-globe me-1"></i> Live Site
                </a>
                
                <div class="dropdown">
                    <button class="btn btn-sm btn-light border rounded-pill dropdown-toggle d-flex align-items-center gap-2 px-3" type="button" data-bs-toggle="dropdown">
                        <div class="rounded-circle text-white d-flex align-items-center justify-content-center fw-bold" style="width: 26px; height: 26px; background-color: var(--brand-primary); font-size: 0.75rem;">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <span class="fw-semibold text-dark small">{{ Auth::user()->name }}</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2" style="font-size: 0.8125rem;">
                        <li class="px-3 py-2 border-bottom">
                            <div class="fw-bold text-dark">{{ Auth::user()->name }}</div>
                            <small class="text-muted d-block">{{ ucfirst(Auth::user()->admin_role ?? 'Administrator') }}</small>
                        </li>
                        <li><a class="dropdown-item py-2" href="{{ route('portfolio.edit') }}"><i class="fa-solid fa-gauge me-2 text-primary"></i> User Dashboard</a></li>
                        <li><a class="dropdown-item py-2" href="{{ route('admin.settings.index') }}"><i class="fa-solid fa-gear me-2 text-secondary"></i> System Settings</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item py-2 text-danger"><i class="fa-solid fa-arrow-right-from-bracket me-2"></i> Log Out</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Side-by-Side Control Center Layout -->
    <div class="d-flex flex-column flex-md-row w-100 min-vh-100">
        <!-- 240px Dark Admin Sidebar -->
        <aside id="adminSidebar" class="admin-sidebar flex-shrink-0 collapse d-md-block p-3">
            <div class="sidebar-menu">
                <div class="admin-sidebar-header">Overview</div>
                <ul class="nav flex-column gap-1">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.index') || request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.index') }}">
                            <i class="fa-solid fa-chart-line me-2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.verification.*') ? 'active' : '' }}" href="{{ route('admin.verification.index') }}">
                            <i class="fa-solid fa-shield-halved me-2"></i> Verification Center
                        </a>
                    </li>
                </ul>

                <div class="admin-sidebar-header">Platform Management</div>
                <ul class="nav flex-column gap-1">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.professionals.*') ? 'active' : '' }}" href="{{ route('admin.professionals.index') }}">
                            <i class="fa-solid fa-users me-2"></i> Professionals
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.companies.*') ? 'active' : '' }}" href="{{ route('admin.companies.index') }}">
                            <i class="fa-solid fa-building me-2"></i> Companies
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.jobs.*') ? 'active' : '' }}" href="{{ route('admin.jobs.index') }}">
                            <i class="fa-solid fa-briefcase me-2"></i> Jobs & Opportunities
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.applications.*') ? 'active' : '' }}" href="{{ route('admin.applications.index') }}">
                            <i class="fa-solid fa-paper-plane me-2"></i> Applications
                        </a>
                    </li>
                </ul>

                <div class="admin-sidebar-header">Community & Moderation</div>
                <ul class="nav flex-column gap-1">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.moderation.*') ? 'active' : '' }}" href="{{ route('admin.moderation.index') }}">
                            <i class="fa-solid fa-triangle-exclamation me-2"></i> Content Moderation
                        </a>
                    </li>
                </ul>

                <div class="admin-sidebar-header">Analytics & Operations</div>
                <ul class="nav flex-column gap-1">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.analytics.*') ? 'active' : '' }}" href="{{ route('admin.analytics.index') }}">
                            <i class="fa-solid fa-chart-pie me-2"></i> Analytics Hub
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('invoices.*') ? 'active' : '' }}" href="{{ route('invoices.index') }}">
                            <i class="fa-solid fa-file-invoice-dollar me-2"></i> Invoices & Billing
                        </a>
                    </li>
                </ul>

                <div class="admin-sidebar-header">Governance & System</div>
                <ul class="nav flex-column gap-1">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.audit-logs.*') ? 'active' : '' }}" href="{{ route('admin.audit-logs.index') }}">
                            <i class="fa-solid fa-list-check me-2"></i> Audit Logs
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.administrators.*') ? 'active' : '' }}" href="{{ route('admin.administrators.index') }}">
                            <i class="fa-solid fa-user-shield me-2"></i> Admin Roles
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}" href="{{ route('admin.settings.index') }}">
                            <i class="fa-solid fa-sliders me-2"></i> System Settings
                        </a>
                    </li>
                </ul>
            </div>
        </aside>

        <!-- Admin Workspace -->
        <main class="admin-workspace p-3 p-md-4">
            <!-- Notifications Alert Bar -->
            @if (session('status'))
                <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm mb-4" role="alert">
                    <i class="fa-solid fa-circle-check me-2"></i> {{ str_replace('-', ' ', ucfirst(session('status'))) }} action performed successfully!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm mb-4" role="alert">
                    <i class="fa-solid fa-circle-xmark me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{ $slot ?? '' }}
            @yield('content')
        </main>
    </div>

    <!-- Bootstrap JS 5.3.3 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon">
    <title>{{ config('app.name', 'MyResume.cloud') }} — Portfolio, Talent & Opportunity Network</title>

    <!-- Google Fonts: Outfit (Headings) & Inter (Body) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS 5.3.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome 6.4.0 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Authentic Brand Colors & Compact Typography System -->
    <style>
        :root {
            /* Logo Extracted Brand Tokens */
            --brand-primary: #4c75a1;            /* Steel Slate Blue */
            --brand-primary-hover: #375c85;      /* Darker Steel Slate */
            --brand-primary-active: #264568;     /* Deep Steel Navy */
            
            --brand-secondary: #1e293b;          /* Dark Slate Structure */
            --brand-accent: #b0c6db;             /* Soft Ice Blue Accent */
            --brand-tint: #f0f4f8;               /* Light Slate Surface Tint */
            --brand-light: #f8fafc;              /* Application Surface BG */
            
            --font-headings: 'Outfit', sans-serif;
            --font-body: 'Inter', sans-serif;

            /* Type Scale & Sizing System */
            --font-size-hero: 2.375rem;          /* 38px Marketing Hero H1 */
            --font-size-h1: 1.625rem;            /* 26px App Page H1 */
            --font-size-h2: 1.375rem;            /* 22px Section H2 */
            --font-size-h3: 1.125rem;            /* 18px Card Title H3 */
            --font-size-h4: 1.000rem;            /* 16px Sub-title H4 */
            --font-size-body: 0.875rem;          /* 14px Standard Body Text */
            --font-size-sm: 0.8125rem;           /* 13px Small / Metadata */
            --font-size-xs: 0.750rem;            /* 12px Micro Captions */
            --font-size-badge: 0.6875rem;        /* 11px Compact Badges */

            --border-color: #cbd5e1;
            --radius-md: 10px;
            --radius-pill: 9999px;
            --shadow-sm: 0 1px 3px 0 rgba(15, 23, 42, 0.08);
            --shadow-md: 0 4px 12px -2px rgba(15, 23, 42, 0.1);
        }

        body {
            font-family: var(--font-body);
            font-size: var(--font-size-body);
            line-height: 1.5;
            background-color: var(--brand-light);
            color: #334155;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        h1, .h1 { font-size: var(--font-size-h1); line-height: 1.25; font-family: var(--font-headings); font-weight: 700; color: var(--brand-secondary); }
        h2, .h2 { font-size: var(--font-size-h2); line-height: 1.3;  font-family: var(--font-headings); font-weight: 700; color: var(--brand-secondary); }
        h3, .h3 { font-size: var(--font-size-h3); line-height: 1.35; font-family: var(--font-headings); font-weight: 600; color: var(--brand-secondary); }
        h4, .h4 { font-size: var(--font-size-h4); line-height: 1.4;  font-family: var(--font-headings); font-weight: 600; color: var(--brand-secondary); }

        /* Compact Button System */
        .btn {
            font-size: var(--font-size-sm);
            font-weight: 600;
            padding: 0.45rem 1rem;
            min-height: 38px;
            border-radius: 8px;
        }
        .btn-sm {
            font-size: var(--font-size-xs);
            padding: 0.25rem 0.75rem;
            min-height: 32px;
            border-radius: 6px;
        }
        .btn-lg {
            font-size: var(--font-size-body);
            padding: 0.6rem 1.25rem;
            min-height: 44px;
            border-radius: var(--radius-pill);
        }

        .btn-primary {
            background-color: var(--brand-primary) !important;
            border-color: var(--brand-primary) !important;
            color: #ffffff !important;
        }
        .btn-primary:hover, .btn-primary:focus, .btn-primary:active {
            background-color: var(--brand-primary-hover) !important;
            border-color: var(--brand-primary-hover) !important;
            color: #ffffff !important;
        }

        /* Guaranteed Dropdown Visibility & Position Overrides */
        .dropdown { position: relative; }
        .dropdown-menu.show {
            display: block !important;
            opacity: 1 !important;
            visibility: visible !important;
            z-index: 1050 !important;
        }
        .dropdown-menu-end {
            right: 0 !important;
            left: auto !important;
        }
        .btn-outline-primary {
            color: var(--brand-primary) !important;
            border-color: var(--brand-primary) !important;
        }
        .btn-outline-primary:hover, .btn-outline-primary:active {
            background-color: var(--brand-primary) !important;
            color: #ffffff !important;
        }

        .text-primary {
            color: var(--brand-primary) !important;
        }
        .bg-primary-subtle {
            background-color: rgba(76, 117, 161, 0.12) !important;
        }
        .border-primary-subtle {
            border-color: rgba(76, 117, 161, 0.3) !important;
        }

        /* Compact Form Controls */
        .form-control, .form-select {
            font-size: var(--font-size-sm);
            min-height: 38px;
            border-color: var(--border-color);
            border-radius: 8px;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--brand-primary);
            box-shadow: 0 0 0 0.25rem rgba(76, 117, 161, 0.2);
        }
        .form-label {
            font-size: var(--font-size-sm);
            font-weight: 600;
            color: #334155;
            margin-bottom: 0.35rem;
        }

        /* High Information Density Cards */
        .card {
            border-radius: var(--radius-md);
            border-color: var(--border-color);
            box-shadow: var(--shadow-sm);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .card-body {
            padding: 18px;
        }
        .card.hover-lift:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-md);
        }

        /* Tables & Badges Sizing */
        .table { font-size: var(--font-size-sm); }
        .table th { font-size: var(--font-size-xs); font-weight: 600; text-transform: uppercase; letter-spacing: 0.03em; }
        .badge { font-size: var(--font-size-badge); font-weight: 600; padding: 0.35rem 0.65rem; }

        .hover-primary:hover {
            color: var(--brand-primary) !important;
        }

        /* Header Navbar */
        .app-navbar {
            background-color: #ffffff !important;
            border-bottom: 1px solid var(--border-color);
            position: sticky;
            top: 0;
            z-index: 1040;
        }
        .app-navbar .nav-link {
            font-size: var(--font-size-sm);
            font-weight: 500;
            color: #475569;
            padding: 0.45rem 0.85rem;
            transition: color 0.2s ease;
        }
        .app-navbar .nav-link:hover, .app-navbar .nav-link.active {
            color: var(--brand-primary);
            font-weight: 600;
        }

        main {
            flex: 1;
        }
    </style>
    @stack('styles')
    @vite(['resources/js/app.js'])
</head>
<body>
    <!-- App Header Navbar -->
    <nav class="navbar navbar-expand-lg app-navbar py-2">
        <div class="container">
            <!-- Brand Logo -->
            <a class="navbar-brand d-flex align-items-center py-1 me-3" href="{{ route('welcome') }}">
                <img src="{{ asset('images/logo.jpeg') }}" alt="MyResume.cloud" style="height: 40px; max-height: 40px; object-fit: contain;" class="rounded shadow-sm">
            </a>

            <!-- Mobile Offcanvas Toggler -->
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileAppNav" aria-controls="mobileAppNav" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Desktop Navigation -->
            <div class="collapse navbar-collapse" id="desktopNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 align-items-center">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('talent.*') ? 'active' : '' }}" href="{{ route('talent.index') }}">Professionals</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('companies.*') ? 'active' : '' }}" href="{{ route('companies.index') }}">Companies</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('opportunities.*') ? 'active' : '' }}" href="{{ route('opportunities.index') }}">Opportunities</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('feed.*') ? 'active' : '' }}" href="{{ route('feed.index') }}">Network</a>
                    </li>
                </ul>

                <!-- Search Input Trigger -->
                <form action="{{ route('search.index') }}" method="GET" class="me-3 d-none d-xl-block" style="width: 220px;">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                        <input type="text" name="q" class="form-control bg-light border-start-0 ps-0" placeholder="Search platform..." value="{{ request('q') }}">
                    </div>
                </form>

                <ul class="navbar-nav ms-auto align-items-center">
                    @auth
                    @php
                        $unreadNotificationsCount = \App\Models\SystemNotification::where('user_id', Auth::id())->where('is_read', false)->count();
                        $unreadDirectMessagesCount = Auth::user()->unreadDirectMessagesCount();
                        $unreadVisitorInquiriesCount = Auth::user()->portfolio ? Auth::user()->portfolio->messages()->where('is_read', false)->count() : 0;
                        $totalUnreadMessagesCount = $unreadDirectMessagesCount + $unreadVisitorInquiriesCount;
                        $pendingConnectionsCount = \App\Models\Connection::where('receiver_id', Auth::id())->where('status', 'pending')->count();
                    @endphp
                    <li class="nav-item me-2">
                        <a class="btn btn-outline-primary btn-sm rounded-pill px-3" href="{{ route('opportunities.create') }}"><i class="fa-solid fa-plus me-1"></i> Post Job</a>
                    </li>
                    <li class="nav-item me-2">
                        <a class="nav-link position-relative py-1" href="{{ route('portfolio.edit') }}#networkPane" title="Connection Requests">
                            <i class="fa-solid fa-user-plus fa-lg text-secondary"></i>
                            @if($pendingConnectionsCount > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.65rem;">
                                    {{ $pendingConnectionsCount }}
                                </span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item me-2">
                        <a class="nav-link position-relative py-1" href="{{ route('messages.index') }}" title="Direct Messages & Chat">
                            <i class="fa-solid fa-comments fa-lg text-secondary"></i>
                            @if($totalUnreadMessagesCount > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.65rem;">
                                    {{ $totalUnreadMessagesCount }}
                                </span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item me-3">
                        <a class="nav-link position-relative py-1" href="{{ route('notifications.index') }}" title="Notifications">
                            <i class="fa-solid fa-bell fa-lg text-secondary"></i>
                            @if($unreadNotificationsCount > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.65rem;">
                                    {{ $unreadNotificationsCount }}
                                </span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-decoration-none d-flex align-items-center py-0 border-0 bg-transparent" href="javascript:void(0)" role="button" id="userMenu" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
                            <div class="rounded-circle text-white d-flex align-items-center justify-content-center fw-bold me-2" style="width: 34px; height: 34px; font-size: 0.85rem; background-color: var(--brand-primary);">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <span class="fw-semibold text-dark small me-1">{{ Auth::user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3 mt-2" aria-labelledby="userMenu">
                            <li><a class="dropdown-item py-2 small" href="{{ route('portfolio.edit') }}"><i class="fa-solid fa-user-pen me-2 text-primary"></i> My Portfolio CMS</a></li>
                            <li><a class="dropdown-item py-2 small" href="{{ route('portfolio.show', Auth::user()->username) }}"><i class="fa-solid fa-eye me-2 text-info"></i> View Public Profile</a></li>
                            <li><a class="dropdown-item py-2 small" href="{{ route('applications.candidate.index') }}"><i class="fa-solid fa-paper-plane me-2 text-success"></i> My Applications</a></li>
                            @if(\App\Models\SystemSetting::isAiMockEnabled())
                                <li><a class="dropdown-item py-2 small" href="{{ route('mock-interviews.index') }}"><i class="fa-solid fa-robot me-2 text-warning"></i> AI Mock Interviews</a></li>
                            @endif
                            <li><a class="dropdown-item py-2 small" href="{{ route('preferences.edit') }}"><i class="fa-solid fa-sliders me-2 text-secondary"></i> Career Preferences</a></li>
                            <li><hr class="dropdown-divider"></li>
                            @if(Auth::user()->companies->isNotEmpty())
                                @foreach(Auth::user()->companies as $myCompany)
                                <li><a class="dropdown-item py-2 small" href="{{ route('companies.dashboard', $myCompany->id) }}"><i class="fa-solid fa-building me-2 text-primary"></i> {{ $myCompany->name }} Dashboard</a></li>
                                @endforeach
                            @else
                                <li><a class="dropdown-item py-2 small" href="{{ route('companies.create') }}"><i class="fa-solid fa-building-user me-2 text-primary"></i> Create Company Profile</a></li>
                            @endif
                            @if(Auth::user()->isAdmin())
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item py-2 text-danger fw-bold small" href="{{ route('admin.index') }}"><i class="fa-solid fa-shield-halved me-2"></i> Admin Dashboard</a></li>
                            @endif
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button class="dropdown-item py-2 text-muted small" type="submit"><i class="fa-solid fa-arrow-right-from-bracket me-2"></i> Log Out</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                    @else
                    <li class="nav-item me-2">
                        <a class="btn btn-outline-secondary btn-sm rounded-pill px-3" href="{{ route('login') }}">Sign In</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-primary btn-sm rounded-pill px-3 fw-semibold shadow-sm" href="{{ route('register') }}">Create Portfolio</a>
                    </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Mobile Offcanvas Drawer -->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="mobileAppNav" aria-labelledby="mobileAppNavLabel">
        <div class="offcanvas-header border-bottom">
            <h5 class="offcanvas-title fw-bold" id="mobileAppNavLabel">
                <img src="{{ asset('images/logo.jpeg') }}" alt="Logo" style="height: 36px;">
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <ul class="nav flex-column gap-2 mb-4">
                <li class="nav-item"><a class="nav-link text-dark fw-semibold" href="{{ route('opportunities.index') }}"><i class="fa-solid fa-briefcase text-primary me-2"></i> Opportunities</a></li>
                <li class="nav-item"><a class="nav-link text-dark fw-semibold" href="{{ route('companies.index') }}"><i class="fa-solid fa-building text-primary me-2"></i> Companies</a></li>
                <li class="nav-item"><a class="nav-link text-dark fw-semibold" href="{{ route('talent.index') }}"><i class="fa-solid fa-user-gear text-primary me-2"></i> Find Talent</a></li>
                <li class="nav-item"><a class="nav-link text-dark fw-semibold" href="{{ route('feed.index') }}"><i class="fa-solid fa-square-rss text-primary me-2"></i> Feed</a></li>
            </ul>

            @auth
            <hr>
            <ul class="nav flex-column gap-2">
                <li class="nav-item"><a class="nav-link text-dark" href="{{ route('portfolio.edit') }}"><i class="fa-solid fa-user-pen me-2 text-primary"></i> My Portfolio CMS</a></li>
                <li class="nav-item"><a class="nav-link text-dark" href="{{ route('applications.candidate.index') }}"><i class="fa-solid fa-paper-plane me-2 text-success"></i> My Applications</a></li>
                @if(\App\Models\SystemSetting::isAiMockEnabled())
                    <li class="nav-item"><a class="nav-link text-dark" href="{{ route('mock-interviews.index') }}"><i class="fa-solid fa-robot me-2 text-warning"></i> AI Mock Interviews</a></li>
                @endif
                <li class="nav-item">
                    <a class="nav-link text-dark d-flex align-items-center justify-content-between" href="{{ route('messages.index') }}">
                        <span><i class="fa-solid fa-comments me-2 text-primary"></i> Direct Messages</span>
                        @if($totalUnreadMessagesCount > 0)
                            <span class="badge bg-danger rounded-pill">{{ $totalUnreadMessagesCount }}</span>
                        @endif
                    </a>
                </li>
                @if(Auth::user()->companies->isNotEmpty())
                    @foreach(Auth::user()->companies as $myCompany)
                    <li class="nav-item"><a class="nav-link text-dark" href="{{ route('companies.dashboard', $myCompany->id) }}"><i class="fa-solid fa-building me-2 text-primary"></i> {{ $myCompany->name }} Dashboard</a></li>
                    @endforeach
                @else
                    <li class="nav-item"><a class="nav-link text-dark" href="{{ route('companies.create') }}"><i class="fa-solid fa-building-user me-2 text-primary"></i> Create Company Profile</a></li>
                @endif
                @if(Auth::user()->isAdmin())
                    <li class="nav-item"><a class="nav-link text-danger fw-bold" href="{{ route('admin.index') }}"><i class="fa-solid fa-shield-halved me-2"></i> Admin Dashboard</a></li>
                @endif
                <li class="nav-item mt-2">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="btn btn-outline-danger btn-sm w-100 rounded-pill" type="submit"><i class="fa-solid fa-arrow-right-from-bracket me-1"></i> Log Out</button>
                    </form>
                </li>
            </ul>
            @endauth
        </div>
    </div>

    <!-- Main App Content -->
    <main>
        @if (isset($header))
            <header class="bg-white shadow-sm py-3 mb-4">
                <div class="container">
                    {{ $header }}
                </div>
            </header>
        @endif

        <div class="{{ ($fullWidth ?? false) || request()->routeIs('portfolio.edit') ? 'container-fluid p-0' : 'container py-4' }}">
            {{ $slot ?? '' }}
            @yield('content')
        </div>
    </main>

    <!-- Global App Footer -->
    @include('partials.footer')

    <!-- Bootstrap JS 5.3.3 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('click', function(e) {
            var toggle = e.target.closest('[data-bs-toggle="dropdown"]');
            if (toggle) {
                e.preventDefault();
                e.stopPropagation();
                var menu = toggle.nextElementSibling;
                if (!menu || !menu.classList.contains('dropdown-menu')) {
                    var parent = toggle.closest('.dropdown');
                    if (parent) menu = parent.querySelector('.dropdown-menu');
                }
                if (menu) {
                    var isOpen = menu.classList.contains('show');
                    document.querySelectorAll('.dropdown-menu.show').forEach(function(m) {
                        m.classList.remove('show');
                    });
                    document.querySelectorAll('[data-bs-toggle="dropdown"]').forEach(function(t) {
                        t.setAttribute('aria-expanded', 'false');
                    });
                    if (!isOpen) {
                        menu.classList.add('show');
                        toggle.setAttribute('aria-expanded', 'true');
                    }
                }
            } else if (!e.target.closest('.dropdown-menu')) {
                document.querySelectorAll('.dropdown-menu.show').forEach(function(m) {
                    m.classList.remove('show');
                });
                document.querySelectorAll('[data-bs-toggle="dropdown"]').forEach(function(t) {
                    t.setAttribute('aria-expanded', 'false');
                });
            }
        });
    </script>
    @include('partials.loader')
    @stack('scripts')
</body>
</html>

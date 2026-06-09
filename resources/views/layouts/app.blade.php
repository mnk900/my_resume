<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon-16x16.png') }}">
    <title>Innovative IT Solutions in Gilgit-Baltistan for Businesses| MY Resumes Dot Cloud</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --brand-primary: #467ba7;
            --brand-hover: #37638a;
        }
        .dropdown-menu {
            z-index: 1050;
        }
        .dropdown-item {
            cursor: pointer;
        }
        /* Custom dashboard brand styling */
        .btn-primary {
            background-color: var(--brand-primary) !important;
            border-color: var(--brand-primary) !important;
        }
        .btn-primary:hover, .btn-primary:focus, .btn-primary:active {
            background-color: var(--brand-hover) !important;
            border-color: var(--brand-hover) !important;
        }
        .text-primary {
            color: var(--brand-primary) !important;
        }
        .nav-link.active {
            color: var(--brand-primary) !important;
            font-weight: bold;
        }
    </style>
    @stack('styles')
    
    <!-- Scripts -->
    @vite(['resources/js/app.js'])
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center py-1" href="{{ route('dashboard') }}">
                <img src="{{ asset('images/logo.jpeg') }}" alt="Logo" style="height: 48px; max-height: 48px; object-fit: contain;" class="rounded shadow-sm">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active fw-bold' : '' }}" href="{{ route('dashboard') }}">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('portfolio.edit') ? 'active fw-bold' : '' }}" href="{{ route('portfolio.edit') }}">Manage Portfolio</a>
                    </li>
                    @if(Auth::check() && Auth::user()->isAdmin())
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.index') ? 'active fw-bold' : '' }}" href="{{ route('admin.index') }}">Admin Users</a>
                    </li>
                    @endif
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button class="dropdown-item" type="submit">Log Out</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div>
        @if (isset($header))
            <header class="bg-white shadow-sm py-3 mb-4">
                <div class="container">
                    {{ $header }}
                </div>
            </header>
        @endif

        <main class="container py-4">
            {{ $slot ?? '' }}
            @yield('content')
        </main>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Ensure dropdown works
        document.addEventListener('DOMContentLoaded', function() {
            const dropdownToggle = document.querySelector('.dropdown-toggle');
            const dropdownMenu = document.querySelector('.dropdown-menu');
            
            if (dropdownToggle && dropdownMenu) {
                dropdownToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    dropdownMenu.classList.toggle('show');
                    dropdownToggle.setAttribute('aria-expanded', dropdownMenu.classList.contains('show'));
                });
                
                // Close dropdown when clicking outside
                document.addEventListener('click', function(e) {
                    if (!dropdownToggle.contains(e.target) && !dropdownMenu.contains(e.target)) {
                        dropdownMenu.classList.remove('show');
                        dropdownToggle.setAttribute('aria-expanded', 'false');
                    }
                });
            }
        });
    </script>
    @stack('scripts')
</body>
</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Innovative IT Solutions in Gilgit-Baltistan for Businesses| MY Resumes Dot Cloud</title>
    <link rel="icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --brand-primary: #467ba7;
            --brand-hover: #37638a;
            --brand-gradient: linear-gradient(135deg, #467ba7 0%, #72a1c9 100%);
        }
        body { font-family: 'Inter', sans-serif; }
        .hero { background: var(--brand-gradient); color: white; padding: 100px 0; }
        .search-card { margin-top: -50px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        .portfolio-card { transition: all 0.3s; border: none; border-radius: 12px; height: 100%; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .portfolio-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
        .badge-role { background-color: var(--brand-primary); }

        /* Brand override for buttons and text */
        .btn-primary {
            background-color: var(--brand-primary) !important;
            border-color: var(--brand-primary) !important;
        }
        .btn-primary:hover, .btn-primary:focus, .btn-primary:active {
            background-color: var(--brand-hover) !important;
            border-color: var(--brand-hover) !important;
        }
        .btn-outline-primary {
            color: var(--brand-primary) !important;
            border-color: var(--brand-primary) !important;
        }
        .btn-outline-primary:hover, .btn-outline-primary:active, .btn-outline-primary:focus {
            background-color: var(--brand-primary) !important;
            border-color: var(--brand-primary) !important;
            color: #fff !important;
        }
        .text-primary {
            color: var(--brand-primary) !important;
        }

        /* Custom Pagination styles matching brand */
        .pagination .page-item.active .page-link {
            background-color: var(--brand-primary) !important;
            border-color: var(--brand-primary) !important;
            color: #fff !important;
        }
        .pagination .page-link {
            color: var(--brand-primary);
        }
        .pagination .page-link:hover {
            color: var(--brand-hover);
            background-color: #f8f9fa;
        }
        .pagination .page-link:focus {
            box-shadow: 0 0 0 0.25rem rgba(70, 123, 167, 0.25);
        }

        /* Custom Footer Style helper */
        .text-light-50 {
            color: rgba(255, 255, 255, 0.65) !important;
        }
        .hover-white {
            transition: color 0.2s ease-in-out;
        }
        .hover-white:hover {
            color: #ffffff !important;
        }
    </style>
</head>
<body class="bg-light">

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center fw-bold fs-3" href="/">
                <img src="{{ asset('images/logo.jpeg') }}" alt="Logo" style="height: 48px; max-height: 48px; object-fit: contain;" class="rounded shadow-sm">
            </a>
            <div class="ms-auto">
                @if (Route::has('login'))
                    <div class="d-flex gap-2 align-items-center">
                        @auth
                            <a href="{{ route('dashboard') }}" class="btn btn-primary shadow-sm">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-link text-decoration-none text-dark fw-semibold me-2">Log in</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="btn btn-primary shadow-sm">Get Started</a>
                            @endif
                        @endauth
                    </div>
                @endif
            </div>
        </div>
    </nav>

    <!-- Intro Section -->
    <header class="hero">
        <div class="container text-center">
            <h1 class="display-3 fw-800 mb-4">One Platform. Every Professional</h1>
            <p class="lead mb-5 opacity-75">A unified platform that enables professionals from all fields to create, manage, and showcase their digital portfolios in a structured and impactful way.</p>
        </div>
    </header>

    <main class="container">
        <!-- Search & Filter -->
        <div class="card search-card border-0 mb-5">
            <div class="card-body p-4 p-md-5">
                <form action="{{ route('welcome') }}" method="GET" class="row g-3">
                    <div class="col-md-10">
                        <input type="text" name="search" class="form-control form-control-lg border-2" placeholder="Search by name, position, or general keywords..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary btn-lg w-100 shadow">Search</button>
                    </div>
                    
                    <div class="col-12 mt-4">
                        <div class="row g-2">
                            <div class="col-md-2">
                                <input type="text" name="skills" class="form-control" placeholder="Skills" value="{{ request('skills') }}">
                            </div>
                            <div class="col-md-2">
                                <input type="text" name="position" class="form-control" placeholder="Position" value="{{ request('position') }}">
                            </div>
                            <div class="col-md-2">
                                <input type="text" name="city" class="form-control" placeholder="City" value="{{ request('city') }}">
                            </div>
                            <div class="col-md-2">
                                <input type="text" name="organization" class="form-control" placeholder="Organization" value="{{ request('organization') }}">
                            </div>
                            <div class="col-md-2">
                                <input type="text" name="country" class="form-control" placeholder="Country" value="{{ request('country') }}">
                            </div>
                            <div class="col-md-2">
                                <a href="{{ route('welcome') }}" class="btn btn-outline-secondary w-100">Clear Filters</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Portfolios Grid -->
        <div class="row g-4 mb-5">
            <div class="col-12 mb-2">
                <h3 class="fw-bold">Explore Portfolios</h3>
                <p class="text-muted small">Showing {{ $portfolios->total() }} results</p>
            </div>
            
            @forelse($portfolios as $portfolio)
                <div class="col-md-6 col-lg-4">
                    <div class="card portfolio-card bg-white p-2">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                @if($portfolio->profile_image)
                                    <img src="{{ Storage::url($portfolio->profile_image) }}" alt="{{ $portfolio->user->name }}" class="rounded-circle shadow-sm me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                @else
                                    <div class="bg-primary-subtle text-primary rounded-circle p-3 fw-bold me-3">
                                        {{ strtoupper(substr($portfolio->user->name, 0, 1)) }}
                                    </div>
                                @endif
                                <div>
                                    <h5 class="mb-0 fw-bold">{{ $portfolio->user->name }}</h5>
                                    <small class="text-muted">{{ $portfolio->position ?? 'Professional' }}</small>
                                </div>
                            </div>
                            
                            @if($portfolio->organization)
                                <p class="mb-2 small text-muted">
                                    <strong>🏢 {{ $portfolio->organization }}</strong>
                                </p>
                            @endif
                            
                            <p class="card-text small text-secondary">
                                {{ Str::limit(strip_tags($portfolio->description), 100) }}
                            </p>
                            
                            <div class="mt-3 small text-muted d-flex gap-3">
                                <span>📍 {{ $portfolio->city ?? 'Unknown' }}, {{ $portfolio->country ?? 'Global' }}</span>
                            </div>
                            
                            <hr class="my-3 opacity-10">
                            
                            <div class="d-grid">
                                <a href="{{ route('portfolio.show', $portfolio->user->username) }}" target="_blank" class="btn btn-outline-primary shadow-sm border-2 fw-bold">View Portfolio</a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <div class="mb-4">🔍</div>
                    <h4>No portfolios found matching your criteria.</h4>
                    <p class="text-muted">Try adjusting your search or filters.</p>
                </div>
            @endforelse
        </div>

        <div class="d-flex justify-content-center mb-5">
            {{ $portfolios->links() }}
        </div>
    </main>

    <footer class="bg-dark text-light py-5 mt-5 border-top border-secondary">
        <div class="container">
            <div class="row g-4 justify-content-between">
                <!-- Column 1: Brand -->
                <div class="col-lg-4 col-md-6">
                    <div class="mb-3">
                        <img src="{{ asset('images/logo.jpeg') }}" alt="Logo" style="height: 48px; max-height: 48px; object-fit: contain; filter: brightness(0.9) contrast(1.1);" class="rounded bg-white p-1">
                    </div>
                    <p class="text-light-50 small mb-4">
                        A unified platform that enables professionals from all fields to create, manage, and showcase their digital portfolios in a structured and impactful way.
                    </p>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-light-50 hover-white"><i class="fab fa-twitter fs-5"></i></a>
                        <a href="#" class="text-light-50 hover-white"><i class="fab fa-linkedin fs-5"></i></a>
                        <a href="#" class="text-light-50 hover-white"><i class="fab fa-github fs-5"></i></a>
                    </div>
                </div>

                <!-- Column 2: Platform Links -->
                <div class="col-lg-3 col-md-6">
                    <h6 class="text-uppercase fw-bold mb-3" style="color: #72a1c9;">Platform</h6>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2"><a href="/" class="text-light-50 text-decoration-none hover-white small">Home</a></li>
                        <li class="mb-2"><a href="{{ route('login') }}" class="text-light-50 text-decoration-none hover-white small">Sign In</a></li>
                        <li class="mb-2"><a href="{{ route('register') }}" class="text-light-50 text-decoration-none hover-white small">Get Started</a></li>
                    </ul>
                </div>

                <!-- Column 3: Contact & Support -->
                <div class="col-lg-4 col-md-6">
                    <h6 class="text-uppercase fw-bold mb-3" style="color: #72a1c9;">Brought to you by</h6>
                    <p class="text-light-50 small mb-2">
                        <strong>I-Tech GB</strong> — Empowering digital innovation and professional growth in Gilgit-Baltistan.
                    </p>
                    <ul class="list-unstyled mb-0 text-light-50 small">
                        <li class="mb-1"><i class="fas fa-envelope me-2"></i> info@itechgb.com</li>
                        <li class="mb-1"><i class="fas fa-globe me-2"></i> <a href="https://itechgb.com" target="_blank" class="text-light-50 text-decoration-none hover-white">itechgb.com</a></li>
                    </ul>
                </div>
            </div>

            <hr class="my-4 border-secondary opacity-25">

            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <span class="text-light-50 small">&copy; {{ date('Y') }} MyResumes. All rights reserved.</span>
                </div>
                <div class="col-md-6 text-center text-md-end mt-2 mt-md-0">
                    <a href="#" class="text-light-50 text-decoration-none hover-white small me-3">Privacy Policy</a>
                    <a href="#" class="text-light-50 text-decoration-none hover-white small">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

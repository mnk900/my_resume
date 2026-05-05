<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SaaS Portfolio Builder</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .hero { background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%); color: white; padding: 100px 0; }
        .search-card { margin-top: -50px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        .portfolio-card { transition: all 0.3s; border: none; border-radius: 12px; height: 100%; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .portfolio-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
        .badge-role { background-color: #6366f1; }
    </style>
</head>
<body class="bg-light">

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-transparent position-absolute w-100">
        <div class="container">
            <a class="navbar-brand fw-bold fs-3" href="/">PortfolioSaaS</a>
            <div class="ms-auto">
                @if (Route::has('login'))
                    <div class="d-flex gap-2">
                        @auth
                            <a href="{{ route('dashboard') }}" class="btn btn-light">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="btn text-white">Log in</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="btn btn-light shadow-sm">Get Started</a>
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
            <h1 class="display-3 fw-800 mb-4">Build Your Professional Presence in Minutes</h1>
            <p class="lead mb-5 opacity-75">A powerful, multi-tenant portfolio builder for developers, designers, and creators.</p>
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
                                {{ Str::limit($portfolio->description, 100) }}
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

    <footer class="bg-white py-5 border-top">
        <div class="container text-center">
            <p class="mb-0 text-muted">&copy; {{ date('Y') }} PortfolioSaaS Builder. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Showcase Your Professional Journey | My Resumes</title>
    <link rel="icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@300;400;500;600;700;800&display=swap');

        :root {
            --brand-primary: #2563eb;
            --brand-primary-hover: #1d4ed8;
            --brand-secondary: #0f172a;
            --brand-accent: #06b6d4;
            --brand-gradient: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            --brand-light: #f8fafc;
            --font-headings: 'Outfit', sans-serif;
            --font-body: 'Inter', sans-serif;
        }

        body {
            font-family: var(--font-body);
            background-color: var(--brand-light);
            color: #334155;
            overflow-x: hidden;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: var(--font-headings);
            font-weight: 700;
            color: var(--brand-secondary);
        }

        /* Navbar */
        .navbar {
            backdrop-filter: blur(8px);
            background-color: rgba(255, 255, 255, 0.9) !important;
            transition: all 0.3s;
        }

        /* Carousel Hero Slider */
        .hero-carousel {
            background: var(--brand-gradient);
            position: relative;
            padding: 80px 0;
            overflow: hidden;
        }

        .hero-carousel::before {
            content: '';
            position: absolute;
            top: -20%;
            right: -10%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(37,99,235,0.15) 0%, rgba(0,0,0,0) 70%);
            z-index: 0;
        }

        .hero-carousel::after {
            content: '';
            position: absolute;
            bottom: -20%;
            left: -10%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(6,182,212,0.1) 0%, rgba(0,0,0,0) 70%);
            z-index: 0;
        }

        .carousel-item {
            min-height: 400px;
        }

        .carousel-caption-custom {
            z-index: 5;
            position: relative;
            text-align: left;
            color: #fff;
        }

        .carousel-caption-custom h1 {
            font-size: 3.5rem;
            line-height: 1.15;
            font-weight: 800;
            color: #ffffff;
            margin-bottom: 20px;
        }

        .carousel-caption-custom p {
            font-size: 1.2rem;
            color: #cbd5e1;
            margin-bottom: 30px;
            font-weight: 300;
        }

        /* Floating Search Card */
        .search-card {
            margin-top: -60px;
            border-radius: 20px;
            box-shadow: 0 20px 40px -15px rgba(15, 23, 42, 0.15);
            background: #ffffff;
            border: 1px solid rgba(226, 232, 240, 0.8);
            z-index: 10;
            position: relative;
        }

        .btn-primary {
            background-color: var(--brand-primary);
            border-color: var(--brand-primary);
            font-weight: 600;
            padding: 10px 24px;
            border-radius: 8px;
            transition: all 0.2s;
        }

        .btn-primary:hover, .btn-primary:focus, .btn-primary:active {
            background-color: var(--brand-primary-hover) !important;
            border-color: var(--brand-primary-hover) !important;
            transform: translateY(-1px);
        }

        /* Portfolio Explore Cards */
        .portfolio-card {
            border: none;
            border-radius: 16px;
            background-color: #ffffff;
            box-shadow: 0 4px 6px -1px rgba(15, 23, 42, 0.03), 0 2px 4px -2px rgba(15, 23, 42, 0.03);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            height: 100%;
        }

        .portfolio-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 25px -5px rgba(15, 23, 42, 0.08), 0 8px 10px -6px rgba(15, 23, 42, 0.08);
        }

        .avatar-placeholder {
            background: linear-gradient(135deg, #e2e8f0 0%, #cbd5e1 100%);
            color: #475569;
            width: 54px;
            height: 54px;
            font-size: 1.3rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            border-radius: 50%;
        }

        /* Benefits Comparison Table */
        .comparison-section {
            background-color: #ffffff;
            padding: 90px 0;
            border-top: 1px solid #e2e8f0;
            border-bottom: 1px solid #e2e8f0;
        }

        .comparison-card {
            border-radius: 16px;
            box-shadow: 0 10px 15px -3px rgba(15, 23, 42, 0.05);
            border: 1px solid #f1f5f9;
        }

        .table-comparison th {
            font-weight: 700;
            font-family: var(--font-headings);
            font-size: 1.05rem;
            padding: 18px;
            background-color: var(--brand-light);
            color: var(--brand-secondary);
        }

        .table-comparison td {
            padding: 18px;
            vertical-align: middle;
            font-size: 0.95rem;
        }

        /* Platform Stats */
        .stats-banner {
            background-color: var(--brand-secondary);
            color: #ffffff;
            padding: 60px 0;
            background-image: radial-gradient(circle at 10% 20%, rgba(37,99,235,0.15) 0%, transparent 40%);
        }

        .stat-number {
            font-family: var(--font-headings);
            font-size: 3rem;
            font-weight: 800;
            color: var(--brand-accent);
            line-height: 1;
            margin-bottom: 5px;
        }

        /* FAQs Accordion */
        .faq-section {
            padding: 90px 0;
        }

        .accordion-item {
            border: none;
            margin-bottom: 15px;
            border-radius: 12px !important;
            box-shadow: 0 4px 6px -1px rgba(15, 23, 42, 0.02), 0 2px 4px -2px rgba(15, 23, 42, 0.02);
            overflow: hidden;
            border: 1px solid #f1f5f9;
        }

        .accordion-button {
            font-weight: 600;
            color: var(--brand-secondary);
            padding: 20px;
            font-family: var(--font-headings);
        }

        .accordion-button:not(.collapsed) {
            background-color: rgba(37,99,235,0.05);
            color: var(--brand-primary);
            box-shadow: none;
        }

        .accordion-button:focus {
            box-shadow: none;
        }

        /* Footer styling */
        footer {
            background-color: #090d16 !important;
        }
        
        .footer-logo {
            filter: brightness(0.9) contrast(1.1);
        }

        .text-muted-custom {
            color: #94a3b8 !important;
        }

        .hover-glow:hover {
            color: #ffffff !important;
        }
    </style>
</head>
<body class="bg-light">

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm sticky-top py-3">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center fw-bold fs-3" href="/">
                <img src="{{ asset('images/logo.jpeg') }}" alt="Logo" style="height: 48px; max-height: 48px; object-fit: contain;" class="rounded shadow-sm me-2">
                <span class="fs-4 text-dark font-headings">My Resumes</span>
            </a>
            <div class="ms-auto">
                @if (Route::has('login'))
                    <div class="d-flex gap-2 align-items-center">
                        @auth
                            <a href="{{ route('dashboard') }}" class="btn btn-primary shadow-sm"><i class="fa-solid fa-gauge me-1"></i> Dashboard</a>
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

    <!-- Hero Interactive Carousel -->
    <div id="heroCarousel" class="carousel slide hero-carousel" data-bs-ride="carousel" data-bs-interval="6000">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>
        
        <div class="container">
            <div class="carousel-inner">
                <!-- Slide 1 -->
                <div class="carousel-item active">
                    <div class="row align-items-center">
                        <div class="col-lg-7 carousel-caption-custom">
                            <h1 class="display-4">Your Professional Story, Perfected.</h1>
                            <p class="lead">Step away from standardized formats and single-page text files. Build a high-performance digital portfolio that showcases your direct achievements, skills, and personality in real time.</p>
                            <div class="d-flex gap-3 flex-wrap">
                                <a href="{{ route('register') }}" class="btn btn-primary btn-lg shadow"><i class="fa-solid fa-rocket me-2"></i> Create Your Resume</a>
                                <a href="#exploreShowcase" class="btn btn-outline-light btn-lg"><i class="fa-solid fa-magnifying-glass me-2"></i> Explore Portfolios</a>
                            </div>
                        </div>
                        <div class="col-lg-5 d-none d-lg-block text-center position-relative">
                            <i class="fa-solid fa-id-card-clip text-primary" style="font-size: 15rem; opacity: 0.15; position: absolute; top: -50px; left: 20%; transform: rotate(-15deg);"></i>
                            <div class="card bg-dark bg-opacity-50 text-white border-secondary p-4 rounded-3 shadow-lg text-start mx-auto" style="max-width: 380px; backdrop-filter: blur(10px); z-index: 10;">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-primary text-white rounded-circle p-3 fw-bold me-3">JD</div>
                                    <div>
                                        <h5 class="mb-0 text-white">John Doe</h5>
                                        <small class="text-accent text-info">Senior Cloud Engineer</small>
                                    </div>
                                </div>
                                <p class="small text-secondary mb-3">"Fully optimized portfolio built with the Classic theme. Connect directly to view my certifications and downloadable PDF resume."</p>
                                <span class="badge bg-success-subtle text-success border border-success-subtle py-1 px-2"><i class="fa-solid fa-circle-check me-1"></i> Public Access</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Slide 2 -->
                <div class="carousel-item">
                    <div class="row align-items-center">
                        <div class="col-lg-7 carousel-caption-custom">
                            <h1 class="display-4">Three Exquisite Layout Themes.</h1>
                            <p class="lead">Select from Classic, Premium, or Elegant templates curated for visual elegance. Seamlessly toggle options to display projects, testimonials, and dynamic files across all devices.</p>
                            <div class="d-flex gap-3 flex-wrap">
                                <a href="{{ route('register') }}" class="btn btn-primary btn-lg shadow"><i class="fa-solid fa-palette me-2"></i> Select Your Theme</a>
                                <a href="#compareBenefits" class="btn btn-outline-light btn-lg"><i class="fa-solid fa-circle-info me-2"></i> Compare Benefits</a>
                            </div>
                        </div>
                        <div class="col-lg-5 d-none d-lg-block text-center position-relative">
                            <i class="fa-solid fa-cubes text-accent" style="font-size: 14rem; opacity: 0.12; position: absolute; bottom: -50px; right: 20%;"></i>
                            <div class="card bg-white text-dark border-0 p-4 rounded-3 shadow-lg text-start mx-auto" style="max-width: 380px; z-index: 10;">
                                <div class="d-flex gap-1 mb-2">
                                    <span class="badge bg-primary">Classic</span>
                                    <span class="badge bg-success">Premium</span>
                                    <span class="badge bg-info text-white">Elegant</span>
                                </div>
                                <h6 class="fw-bold mb-2">Responsive & Optimized</h6>
                                <p class="small text-muted mb-0">Every section transitions seamlessly from desktop viewports to mobile interfaces automatically.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Slide 3 -->
                <div class="carousel-item">
                    <div class="row align-items-center">
                        <div class="col-lg-7 carousel-caption-custom">
                            <h1 class="display-4">Complete Privacy & Connections Control.</h1>
                            <p class="lead">Keep your credentials private by default. Approve specific connection requests from fellow professionals to allow viewing, or set your portfolio public so the world can discover you.</p>
                            <div class="d-flex gap-3 flex-wrap">
                                <a href="{{ route('register') }}" class="btn btn-primary btn-lg shadow"><i class="fa-solid fa-user-shield me-2"></i> Set Visibility</a>
                                <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg"><i class="fa-solid fa-user-plus me-2"></i> Connect Now</a>
                            </div>
                        </div>
                        <div class="col-lg-5 d-none d-lg-block text-center position-relative">
                            <i class="fa-solid fa-user-lock text-info" style="font-size: 15rem; opacity: 0.15; position: absolute; top: -50px; right: 20%; transform: rotate(15deg);"></i>
                            <div class="card bg-dark bg-opacity-70 text-white border-0 p-4 rounded-3 shadow-lg text-start mx-auto" style="max-width: 380px; backdrop-filter: blur(10px); z-index: 10;">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <span class="small fw-bold text-warning"><i class="fa-solid fa-lock me-1"></i> Private Profile</span>
                                    <span class="badge bg-danger">1 Connection Pending</span>
                                </div>
                                <div class="bg-secondary bg-opacity-35 p-3 rounded-3 small">
                                    <div class="fw-bold text-white mb-1">User B requested connection</div>
                                    <div class="text-muted-custom">"I would like to view your portfolio and work achievements."</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <button class="carousel-control-prev d-none d-md-flex" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next d-none d-md-flex" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

    <!-- Main Container -->
    <main class="container">
        <!-- Search & Filter Card -->
        <div class="card search-card border-0 mb-5">
            <div class="card-body p-4 p-md-5">
                <form action="{{ route('welcome') }}" method="GET" class="row g-3">
                    <div class="col-lg-9">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 border-2 py-3 px-3"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                            <input type="text" name="search" class="form-control form-control-lg border-2 border-start-0 py-3" placeholder="Search professionals by name, position, or general keywords..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-lg-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-lg flex-grow-1 shadow"><i class="fa-solid fa-magnifying-glass me-1"></i> Search</button>
                        <button class="btn btn-outline-secondary btn-lg" type="button" data-bs-toggle="collapse" data-bs-target="#advancedFilters" aria-expanded="false" aria-controls="advancedFilters">
                            <i class="fa-solid fa-sliders"></i>
                        </button>
                    </div>
                    
                    <!-- Collapsible Advanced Filters -->
                    <div class="collapse col-12 mt-3 {{ (request('skills') || request('position') || request('city') || request('organization') || request('country')) ? 'show' : '' }}" id="advancedFilters">
                        <div class="p-4 bg-light rounded-3 border border-2">
                            <h6 class="fw-bold mb-3"><i class="fa-solid fa-filter me-2 text-primary"></i>Refine Search Criteria</h6>
                            <div class="row g-3">
                                <div class="col-md-4 col-lg-2">
                                    <label class="form-label small fw-bold">Skills</label>
                                    <input type="text" name="skills" class="form-control" placeholder="e.g. PHP, Laravel" value="{{ request('skills') }}">
                                </div>
                                <div class="col-md-4 col-lg-2">
                                    <label class="form-label small fw-bold">Position</label>
                                    <input type="text" name="position" class="form-control" placeholder="e.g. Engineer" value="{{ request('position') }}">
                                </div>
                                <div class="col-md-4 col-lg-2">
                                    <label class="form-label small fw-bold">City</label>
                                    <input type="text" name="city" class="form-control" placeholder="e.g. Gilgit" value="{{ request('city') }}">
                                </div>
                                <div class="col-md-4 col-lg-2">
                                    <label class="form-label small fw-bold">Organization</label>
                                    <input type="text" name="organization" class="form-control" placeholder="e.g. Google" value="{{ request('organization') }}">
                                </div>
                                <div class="col-md-4 col-lg-2">
                                    <label class="form-label small fw-bold">Country</label>
                                    <input type="text" name="country" class="form-control" placeholder="e.g. Pakistan" value="{{ request('country') }}">
                                </div>
                                <div class="col-md-4 col-lg-2 d-flex align-items-end">
                                    <a href="{{ route('welcome') }}" class="btn btn-outline-secondary w-100">Clear All Filters</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Portfolios Showcase Grid -->
        <div id="exploreShowcase" class="row g-4 mb-5 pt-3">
            <div class="col-12 mb-2 d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="fw-bold mb-1">Explore Portfolios</h2>
                    <p class="text-muted small">Showcasing verified profiles &bull; {{ $portfolios->total() }} results</p>
                </div>
            </div>
            
            @forelse($portfolios as $portfolio)
                <div class="col-md-6 col-lg-4">
                    <div class="card portfolio-card p-3">
                        <div class="card-body d-flex flex-column h-100 justify-content-between">
                            <div>
                                <div class="d-flex align-items-center mb-3">
                                    @if($portfolio->profile_image)
                                        <img src="{{ Storage::url($portfolio->profile_image) }}" alt="{{ $portfolio->user->name }}" class="rounded-circle shadow-sm me-3" style="width: 54px; height: 54px; object-fit: cover; border: 2px solid #e2e8f0;">
                                    @else
                                        <div class="avatar-placeholder me-3">
                                            {{ strtoupper(substr($portfolio->user->name, 0, 2)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <h5 class="mb-0 fw-bold fs-6 text-truncate" style="max-width: 180px;">{{ $portfolio->user->name }}</h5>
                                        <small class="text-muted text-truncate d-block" style="max-width: 180px;">{{ $portfolio->position ?? 'Professional' }}</small>
                                    </div>
                                </div>
                                
                                @if($portfolio->organization)
                                    <div class="mb-2 text-primary small fw-semibold">
                                        <i class="fa-solid fa-building me-1 opacity-75"></i> {{ $portfolio->organization }}
                                    </div>
                                @endif
                                
                                <p class="card-text small text-secondary mb-4">
                                    {{ Str::limit(strip_tags($portfolio->description), 105) }}
                                </p>
                            </div>
                            
                            <div>
                                <div class="d-flex justify-content-between align-items-center mb-3 small text-muted">
                                    <span><i class="fa-solid fa-location-dot me-1"></i> {{ $portfolio->city ?? 'Remote' }}, {{ $portfolio->country ?? 'Global' }}</span>
                                    <span class="badge bg-light text-secondary border"><i class="fa-solid fa-star text-warning me-1"></i> Verified</span>
                                </div>
                                <div class="d-grid">
                                    <a href="{{ route('portfolio.show', $portfolio->user->username) }}" target="_blank" class="btn btn-outline-primary border-2 fw-bold shadow-sm">View Full Resume</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5 bg-white rounded-3 shadow-sm border">
                    <i class="fa-solid fa-magnifying-glass display-4 text-secondary mb-3"></i>
                    <h4>No portfolios found matching your search.</h4>
                    <p class="text-muted">Try clearing some filters or searching for general keywords.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mb-5">
            {{ $portfolios->links() }}
        </div>
    </main>

    <!-- Platform Stats Section -->
    <section class="stats-banner text-center">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="stat-number">3 Themes</div>
                    <p class="mb-0 text-muted-custom small">Classic, Premium, and Elegant responsive styles layout.</p>
                </div>
                <div class="col-md-4">
                    <div class="stat-number">100% Free</div>
                    <p class="mb-0 text-muted-custom small">No hosting costs, themes fee, or subscription limits.</p>
                </div>
                <div class="col-md-4">
                    <div class="stat-number">Secure Access</div>
                    <p class="mb-0 text-muted-custom small">Toggle profile privacy and manage connection requests safely.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Comparison Section -->
    <section id="compareBenefits" class="comparison-section">
        <div class="container">
            <div class="text-center mb-5 max-width-600 mx-auto">
                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill fw-bold mb-2">Platform Matrix</span>
                <h2 class="display-6 fw-bold">Why You Need a Portfolio Website</h2>
                <p class="text-muted">A direct comparison of what you get with MyResumes compared to traditional CV files and standardized social networks.</p>
            </div>
            
            <div class="card comparison-card border-0 overflow-hidden shadow">
                <div class="table-responsive">
                    <table class="table table-striped table-comparison mb-0">
                        <thead>
                            <tr>
                                <th>Features</th>
                                <th class="text-primary bg-primary bg-opacity-10"><i class="fa-solid fa-circle-check text-primary me-2"></i>Custom Portfolio Website</th>
                                <th><i class="fa-solid fa-file-pdf text-muted me-2"></i>Traditional CV Resumes</th>
                                <th><i class="fa-brands fa-linkedin text-muted me-2"></i>LinkedIn Profiles</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="fw-semibold">Design & Branding</td>
                                <td class="text-success fw-semibold"><i class="fa-solid fa-circle-check me-2"></i>Complete Control (3 Themes)</td>
                                <td class="text-danger"><i class="fa-solid fa-circle-xmark me-2"></i>Static/Standard Black-and-white</td>
                                <td class="text-warning"><i class="fa-solid fa-triangle-exclamation me-2"></i>Uniform look shared by everyone</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Privacy Controls</td>
                                <td class="text-success fw-semibold"><i class="fa-solid fa-circle-check me-2"></i>Public/Private Toggles + Connections</td>
                                <td class="text-danger"><i class="fa-solid fa-circle-xmark me-2"></i>None (Once sent, it is distributed)</td>
                                <td class="text-warning"><i class="fa-solid fa-triangle-exclamation me-2"></i>Limited (Visibility is public only)</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Engagement Outreach</td>
                                <td class="text-success fw-semibold"><i class="fa-solid fa-circle-check me-2"></i>Direct contact forms + CMS inbox</td>
                                <td class="text-danger"><i class="fa-solid fa-circle-xmark me-2"></i>Static contact text (Manual emails)</td>
                                <td class="text-warning"><i class="fa-solid fa-triangle-exclamation me-2"></i>Paywalled behind InMail subscription</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">File Exports</td>
                                <td class="text-success fw-semibold"><i class="fa-solid fa-circle-check me-2"></i>Single-click PDF/Word Generation</td>
                                <td class="text-warning"><i class="fa-solid fa-triangle-exclamation me-2"></i>Manual document upkeep required</td>
                                <td class="text-danger"><i class="fa-solid fa-circle-xmark me-2"></i>Clunky, unformatted PDF exports</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Data Access</td>
                                <td class="text-success fw-semibold"><i class="fa-solid fa-circle-check me-2"></i>Full ownership; hosted professional link</td>
                                <td class="text-danger"><i class="fa-solid fa-circle-xmark me-2"></i>Stored in recruiters local folders</td>
                                <td class="text-warning"><i class="fa-solid fa-triangle-exclamation me-2"></i>Subject to search engine algorithms</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="faq-section bg-light">
        <div class="container">
            <div class="text-center mb-5 max-width-600 mx-auto">
                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill fw-bold mb-2">FAQ Helper</span>
                <h2 class="display-6 fw-bold">Frequently Asked Questions</h2>
                <p class="text-muted">Everything you need to know about setting up and sharing your portfolio website.</p>
            </div>
            
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="accordion" id="faqAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faqOne">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    How do I create my portfolio website?
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="faqOne" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Simply click "Get Started" in the navigation bar, register an account, and fill out your professional profile data in the CMS dashboard. Your resume portfolio is hosted automatically and updated in real time.
                                </div>
                            </div>
                        </div>
                        
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faqTwo">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    Can I hide my portfolio from the public directory?
                                </button>
                            </h2>
                            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="faqTwo" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Yes. Under your CMS Settings tab, you can toggle your profile to "Private". When private, search engine spiders and guest visitors will only see a private card prompting them to connect. Only approved connection requests can view your full resume details.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faqThree">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                    Is there a fee for downloading my portfolio as a document?
                                </button>
                            </h2>
                            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="faqThree" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    No. The platform allows connections and public visitors to download your complete profile as a formatted Word or PDF resume at any time for free.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faqFour">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                    Can I change my theme layout at any time?
                                </button>
                            </h2>
                            <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="faqFour" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Absolutely. You can toggle between Classic, Premium, and Elegant design styles instantly from your CMS portfolio editor. Your sections and contents adjust automatically without any layout loss.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="text-light py-5 border-top border-secondary">
        <div class="container">
            <div class="row g-4 justify-content-between">
                <!-- Column 1: Brand -->
                <div class="col-lg-4 col-md-6">
                    <div class="mb-3">
                        <img src="{{ asset('images/logo.jpeg') }}" alt="Logo" style="height: 48px; max-height: 48px; object-fit: contain; padding: 4px;" class="rounded bg-white footer-logo">
                        <span class="fs-4 text-white font-headings ms-2">My Resumes</span>
                    </div>
                    <p class="text-muted-custom small mb-4">
                        A unified portfolio platform enabling professionals from all fields to build, customize, and share their digital identities securely and elegantly.
                    </p>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-muted-custom hover-glow"><i class="fab fa-twitter fs-5"></i></a>
                        <a href="#" class="text-muted-custom hover-glow"><i class="fab fa-linkedin fs-5"></i></a>
                        <a href="#" class="text-muted-custom hover-glow"><i class="fab fa-github fs-5"></i></a>
                    </div>
                </div>

                <!-- Column 2: Platform Links -->
                <div class="col-lg-3 col-md-6">
                    <h6 class="text-uppercase fw-bold mb-3 text-info">Platform</h6>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2"><a href="/" class="text-muted-custom text-decoration-none hover-glow small">Home</a></li>
                        <li class="mb-2"><a href="{{ route('login') }}" class="text-muted-custom text-decoration-none hover-glow small">Sign In</a></li>
                        <li class="mb-2"><a href="{{ route('register') }}" class="text-muted-custom text-decoration-none hover-glow small">Get Started</a></li>
                    </ul>
                </div>

                <!-- Column 3: Contact & Support -->
                <div class="col-lg-4 col-md-6">
                    <h6 class="text-uppercase fw-bold mb-3 text-info">Brought to you by</h6>
                    <p class="text-muted-custom small mb-2">
                        <strong>I-Tech GB</strong> — Empowering digital innovation and professional growth in Gilgit-Baltistan.
                    </p>
                    <ul class="list-unstyled mb-0 text-muted-custom small">
                        <li class="mb-1"><i class="fas fa-envelope me-2"></i> info@itechgb.com</li>
                        <li class="mb-1"><i class="fas fa-globe me-2"></i> <a href="https://itechgb.com" target="_blank" class="text-muted-custom text-decoration-none hover-glow">itechgb.com</a></li>
                    </ul>
                </div>
            </div>

            <hr class="my-4 border-secondary opacity-25">

            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <span class="text-muted-custom small">&copy; {{ date('Y') }} MyResumes. All rights reserved.</span>
                </div>
                <div class="col-md-6 text-center text-md-end mt-2 mt-md-0">
                    <a href="#" class="text-muted-custom text-decoration-none hover-glow small me-3">Privacy Policy</a>
                    <a href="#" class="text-muted-custom text-decoration-none hover-glow small">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon">

    <!-- Primary SEO Meta Tags -->
    <title>MyResume.cloud — Build Your Professional Identity & Discover Opportunities</title>
    <meta name="title" content="MyResume.cloud — Build Your Professional Identity & Discover Opportunities">
    <meta name="description" content="Create a verified professional portfolio, connect with your network, discover jobs and opportunities, and prepare for your next career move with AI mock interviews.">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:title" content="MyResume.cloud — Professional Portfolio, Talent & Opportunity Network">
    <meta property="og:description" content="Showcase your professional identity, discover jobs, connect with companies, and prepare for interviews.">
    <meta property="og:image" content="{{ asset('images/logo.jpeg') }}">

    <!-- Google Fonts: Outfit (Headings) & Inter (Body) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS 5.3.3 & FontAwesome 6.4.0 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Authentic Brand Design System & Restrained SaaS Type Scale -->
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
            overflow-x: hidden;
        }

        h1, .h1 { font-size: var(--font-size-h1); line-height: 1.25; font-family: var(--font-headings); font-weight: 700; color: var(--brand-secondary); }
        h2, .h2 { font-size: var(--font-size-h2); line-height: 1.3;  font-family: var(--font-headings); font-weight: 700; color: var(--brand-secondary); }
        h3, .h3 { font-size: var(--font-size-h3); line-height: 1.35; font-family: var(--font-headings); font-weight: 600; color: var(--brand-secondary); }
        h4, .h4 { font-size: var(--font-size-h4); line-height: 1.4;  font-family: var(--font-headings); font-weight: 600; color: var(--brand-secondary); }

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
            padding: 0.55rem 1.25rem;
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
        .btn-outline-primary {
            color: var(--brand-primary) !important;
            border-color: var(--brand-primary) !important;
        }
        .btn-outline-primary:hover, .btn-outline-primary:active {
            background-color: var(--brand-primary) !important;
            color: #ffffff !important;
        }

        .text-primary { color: var(--brand-primary) !important; }
        .bg-primary-subtle { background-color: rgba(76, 117, 161, 0.12) !important; }
        .border-primary-subtle { border-color: rgba(76, 117, 161, 0.3) !important; }

        .card {
            border-radius: var(--radius-md);
            border-color: var(--border-color);
            box-shadow: var(--shadow-sm);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .card-body { padding: 18px; }
        .card.hover-lift:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-md);
        }

        .badge { font-size: var(--font-size-badge); font-weight: 600; padding: 0.35rem 0.65rem; }

        /* Navbar Sticky */
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

        /* Hero Banner Structure */
        .hero-banner {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            position: relative;
            padding: 75px 0 85px;
            color: #ffffff;
            overflow: hidden;
        }
        .hero-banner::before {
            content: '';
            position: absolute;
            top: -20%;
            right: -10%;
            width: 450px;
            height: 450px;
            background: radial-gradient(circle, rgba(76, 117, 161, 0.25) 0%, rgba(0,0,0,0) 70%);
            z-index: 0;
        }

        .hero-title {
            font-size: var(--font-size-hero);
            line-height: 1.18;
            font-weight: 800;
            letter-spacing: -0.01em;
            color: #ffffff;
        }
        @media (max-width: 768px) {
            .hero-title { font-size: 1.85rem; }
        }

        /* Layered Hero Visual Container */
        .hero-visual-wrapper {
            position: relative;
        }
        .main-hero-card {
            background: #ffffff;
            border-radius: 14px;
            box-shadow: 0 15px 35px -10px rgba(0,0,0,0.3);
            border: 1px solid rgba(255,255,255,0.2);
            color: #0f172a;
        }
        .floating-match-badge {
            position: absolute;
            top: -15px;
            right: -15px;
            background: #ffffff;
            border-radius: 12px;
            padding: 10px 14px;
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.25);
            border: 2px solid var(--brand-primary);
            z-index: 10;
        }
        .floating-prep-card {
            position: absolute;
            bottom: -15px;
            left: -15px;
            background: #1e293b;
            color: #ffffff;
            border-radius: 12px;
            padding: 10px 14px;
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.3);
            border: 1px solid var(--brand-accent);
            z-index: 10;
        }

        /* Process Connection Line for How It Works */
        .process-line {
            position: relative;
        }
        @media (min-width: 992px) {
            .process-line::before {
                content: '';
                position: absolute;
                top: 24px;
                left: 10%;
                right: 10%;
                height: 2px;
                background: #cbd5e1;
                z-index: 0;
            }
        }
        .step-number {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background-color: var(--brand-primary);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: var(--font-size-xs);
            font-family: var(--font-headings);
            position: relative;
            z-index: 1;
        }
    </style>
</head>
<body>

    <!-- 01. SECTION 01 — CLEAN NEUTRAL NAVIGATION -->
    <nav class="navbar navbar-expand-lg app-navbar py-2">
        <div class="container">
            <!-- Left: Brand Logo -->
            <a class="navbar-brand d-flex align-items-center py-1 me-4" href="{{ route('welcome') }}">
                <img src="{{ asset('images/logo.jpeg') }}" alt="MyResume.cloud" style="height: 38px; max-height: 38px; object-fit: contain;" class="rounded shadow-sm">
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainHomeNav" aria-controls="mainHomeNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainHomeNav">
                <!-- Center: Clean Destinations -->
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 fw-medium">
                    <li class="nav-item"><a class="nav-link text-dark me-2" href="{{ route('talent.index') }}">Professionals</a></li>
                    <li class="nav-item"><a class="nav-link text-dark me-2" href="{{ route('opportunities.index') }}">Opportunities</a></li>
                    <li class="nav-item"><a class="nav-link text-dark me-2" href="{{ route('companies.index') }}">Companies</a></li>
                    <li class="nav-item"><a class="nav-link text-dark" href="{{ route('feed.index') }}">Network</a></li>
                </ul>

                <!-- Right: CTAs & Employer Sub-Link -->
                <div class="d-flex align-items-center gap-2">
                    @auth
                        <div class="dropdown">
                            <button class="btn btn-link nav-link dropdown-toggle text-decoration-none d-flex align-items-center py-0 border-0 bg-transparent" type="button" id="homeUserMenu" data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="rounded-circle text-white d-flex align-items-center justify-content-center fw-bold me-2" style="width: 34px; height: 34px; font-size: 0.85rem; background-color: var(--brand-primary);">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                <span class="fw-semibold text-dark small">{{ Auth::user()->name }}</span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3 mt-2" aria-labelledby="homeUserMenu">
                                <li><a class="dropdown-item py-2 small" href="{{ route('portfolio.edit') }}"><i class="fa-solid fa-user-pen me-2 text-primary"></i> My Portfolio CMS</a></li>
                                <li><a class="dropdown-item py-2 small" href="{{ route('portfolio.show', Auth::user()->username) }}"><i class="fa-solid fa-eye me-2 text-info"></i> View Public Profile</a></li>
                                <li><a class="dropdown-item py-2 small" href="{{ route('applications.candidate.index') }}"><i class="fa-solid fa-paper-plane me-2 text-success"></i> My Applications</a></li>
                                <li><a class="dropdown-item py-2 small" href="{{ route('mock-interviews.index') }}"><i class="fa-solid fa-robot me-2 text-warning"></i> AI Mock Interviews</a></li>
                                <li><a class="dropdown-item py-2 small" href="{{ route('preferences.edit') }}"><i class="fa-solid fa-sliders me-2 text-secondary"></i> Career Preferences</a></li>
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
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-secondary rounded-pill px-3 fw-semibold">Sign In</a>
                        <a href="{{ route('register') }}" class="btn btn-primary rounded-pill px-3 fw-bold shadow-sm">Create Portfolio</a>
                        <a href="{{ route('companies.create') }}" class="text-secondary small fw-semibold ms-2 d-none d-xl-inline-block text-decoration-none hover-primary">For Companies</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- 02. SECTION 02 — HERO WITH LAYERED PRODUCT EXPERIENCE -->
    <section class="hero-banner">
        <div class="container position-relative" style="z-index: 2;">
            <div class="row align-items-center g-4">
                <!-- Left Story Column -->
                <div class="col-lg-6 text-center text-lg-start">
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1 fw-semibold mb-3">
                        Professional Portfolio • Opportunities • Network • AI Career Tools
                    </span>
                    <h1 class="hero-title mb-3">
                        Build Your Professional Identity.<br>Discover Your Next Opportunity.
                    </h1>
                    <p class="text-light opacity-90 fw-normal mb-4 lh-normal" style="font-size: 0.95rem;">
                        Create a verified professional portfolio, connect with your network, discover jobs and opportunities, and prepare for your next career move — all in one unified ecosystem.
                    </p>

                    <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center justify-content-lg-start mb-4">
                        <a href="{{ route('register') }}" class="btn btn-primary btn-lg rounded-pill px-4 py-2 fw-bold shadow-lg">
                            Create Your Portfolio
                        </a>
                        <a href="{{ route('opportunities.index') }}" class="btn btn-outline-light btn-lg rounded-pill px-4 py-2 fw-semibold">
                            Explore Opportunities
                        </a>
                    </div>

                    <div class="pt-2 border-top border-secondary d-flex align-items-center justify-content-center justify-content-lg-start gap-2 text-light small">
                        <span>For Companies &rarr;</span>
                        <a href="{{ route('companies.create') }}" class="text-info fw-bold text-decoration-none hover-glow">Find Relevant Talent</a>
                    </div>
                </div>

                <!-- Right Layered Visual Column -->
                <div class="col-lg-6">
                    <div class="hero-visual-wrapper p-2 p-md-3">
                        <!-- Floating Badge: Match Score -->
                        <div class="floating-match-badge d-none d-sm-flex align-items-center gap-2">
                            <div class="rounded-circle bg-success-subtle text-success p-1"><i class="fa-solid fa-bolt"></i></div>
                            <div>
                                <div class="fw-bold text-dark fs-6" style="line-height: 1;">92% Match</div>
                                <small class="text-muted" style="font-size: 0.7rem;">Senior Backend Dev</small>
                            </div>
                        </div>

                        <!-- Main Profile Mockup Card -->
                        <div class="main-hero-card p-3 p-md-4">
                            <div class="d-flex align-items-center gap-3 pb-3 mb-3 border-bottom">
                                <div class="rounded-circle text-white d-flex align-items-center justify-content-center fw-bold fs-5" style="width: 48px; height: 48px; background-color: var(--brand-primary);">
                                    FA
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="fw-bold text-dark mb-0 fs-6">Fazal Ali Khan <i class="fa-solid fa-circle-check text-primary small"></i></h6>
                                    <span class="text-primary small fw-semibold">Full Stack Engineer</span>
                                </div>
                                <span class="badge bg-success-subtle text-success border border-success rounded-pill px-2">Verified</span>
                            </div>

                            <div class="mb-3">
                                <div class="text-muted small fw-semibold mb-1">Core Technical Stack</div>
                                <div class="d-flex flex-wrap gap-1">
                                    <span class="badge bg-light text-secondary border">PHP</span>
                                    <span class="badge bg-light text-secondary border">Laravel</span>
                                    <span class="badge bg-light text-secondary border">MySQL</span>
                                    <span class="badge bg-light text-secondary border">REST APIs</span>
                                </div>
                            </div>

                            <div class="row g-2 text-muted small pt-2 border-top">
                                <div class="col-6"><i class="fa-solid fa-location-dot me-1 text-danger"></i> Islamabad / Remote</div>
                                <div class="col-6"><i class="fa-solid fa-briefcase me-1 text-info"></i> 5+ Yrs Experience</div>
                            </div>
                        </div>

                        <!-- Floating Secondary Card: AI Prep -->
                        <div class="floating-prep-card d-none d-sm-flex align-items-center gap-2">
                            <i class="fa-solid fa-robot text-warning fs-5"></i>
                            <div>
                                <div class="fw-bold text-white small" style="line-height: 1;">AI Interview Prep</div>
                                <small class="text-info" style="font-size: 0.7rem;">82% Readiness Score</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 03. SECTION 03 — 4 CORE VALUE PROPOSITIONS -->
    <section class="py-4 bg-white border-bottom">
        <div class="container py-2">
            <div class="row g-3">
                <div class="col-6 col-lg-3">
                    <div class="p-3 rounded-3 bg-light h-100 border-0">
                        <div class="text-primary fw-bold mb-1 fs-5"><i class="fa-solid fa-user-pen me-2"></i> Build</div>
                        <p class="text-secondary small mb-0 lh-normal">Create a professional portfolio that represents your complete career identity.</p>
                    </div>
                </div>

                <div class="col-6 col-lg-3">
                    <div class="p-3 rounded-3 bg-light h-100 border-0">
                        <div class="text-primary fw-bold mb-1 fs-5"><i class="fa-solid fa-magnifying-glass-chart me-2"></i> Discover</div>
                        <p class="text-secondary small mb-0 lh-normal">Find jobs, internships, trainings, and freelance opportunities matched to you.</p>
                    </div>
                </div>

                <div class="col-6 col-lg-3">
                    <div class="p-3 rounded-3 bg-light h-100 border-0">
                        <div class="text-primary fw-bold mb-1 fs-5"><i class="fa-solid fa-users-viewfinder me-2"></i> Connect</div>
                        <p class="text-secondary small mb-0 lh-normal">Build relationships with professionals and organizations across your network.</p>
                    </div>
                </div>

                <div class="col-6 col-lg-3">
                    <div class="p-3 rounded-3 bg-light h-100 border-0">
                        <div class="text-primary fw-bold mb-1 fs-5"><i class="fa-solid fa-robot me-2"></i> Prepare</div>
                        <p class="text-secondary small mb-0 lh-normal">Practice job-specific AI mock interviews tailored to target positions before applying.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 04. SECTION 04 — PROFESSIONAL PORTFOLIO IDENTITY -->
    <section class="py-5 bg-light border-bottom">
        <div class="container py-3">
            <div class="row align-items-center g-4">
                <div class="col-lg-5">
                    <span class="text-primary fw-bold text-uppercase small tracking-wide">Professional Identity</span>
                    <h2 class="h2 fw-bold text-dark mt-1 mb-2">More Than a Resume</h2>
                    <p class="text-secondary small lh-normal mb-3">
                        Showcase the experience, skills, projects, and achievements that define what you can achieve.
                    </p>

                    <div class="row g-2 mb-4 text-secondary small">
                        <div class="col-6"><i class="fa-solid fa-circle-check text-primary me-2"></i> Experience</div>
                        <div class="col-6"><i class="fa-solid fa-circle-check text-primary me-2"></i> Skills</div>
                        <div class="col-6"><i class="fa-solid fa-circle-check text-primary me-2"></i> Projects</div>
                        <div class="col-6"><i class="fa-solid fa-circle-check text-primary me-2"></i> Certifications</div>
                        <div class="col-6"><i class="fa-solid fa-circle-check text-primary me-2"></i> Education</div>
                        <div class="col-6"><i class="fa-solid fa-circle-check text-primary me-2"></i> Achievements</div>
                    </div>

                    <a href="{{ route('register') }}" class="btn btn-primary rounded-pill px-4 py-2 fw-bold shadow-sm">Create Your Portfolio</a>
                </div>

                <div class="col-lg-7">
                    <!-- Authentic Portfolio Profile Preview -->
                    <div class="card border-0 shadow-md rounded-3 overflow-hidden bg-white">
                        <div class="p-3 text-white" style="background-color: var(--brand-primary);">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-circle bg-white text-dark d-flex align-items-center justify-content-center fw-bold fs-4" style="width: 44px; height: 44px;">
                                        JD
                                    </div>
                                    <div>
                                        <h5 class="fw-bold text-white mb-0 fs-6">Jane Doe <i class="fa-solid fa-circle-check text-info small"></i></h5>
                                        <span class="text-light opacity-90 small">Senior UI/UX Designer & Frontend Dev</span>
                                    </div>
                                </div>
                                <span class="badge bg-white text-dark rounded-pill px-3 py-1 fw-bold">Verified Portfolio</span>
                            </div>
                        </div>
                        <div class="card-body p-3 bg-white">
                            <h6 class="fw-bold text-dark mb-1 small">Professional Expertise & Evidence of Work</h6>
                            <p class="text-secondary small lh-normal mb-3">Passionate designer with 6+ years creating intuitive web and mobile applications using Figma, Bootstrap, and modern design systems.</p>
                            
                            <div class="p-2 bg-light rounded-3 mb-2">
                                <div class="fw-bold text-dark small mb-1"><i class="fa-solid fa-diagram-project me-1 text-primary"></i> Featured Project: Enterprise SaaS Portal</div>
                                <p class="text-muted small mb-0" style="font-size: 0.78rem;">Designed and implemented a scalable design system for 50k+ daily active users.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 05. SECTION 05 — OPPORTUNITY HUB (WITH ZERO-EMPTY PROTECTION) -->
    <section class="py-5 bg-white border-bottom">
        <div class="container py-3">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end mb-4">
                <div>
                    <span class="text-primary fw-bold text-uppercase small tracking-wide">Opportunities Hub</span>
                    <h2 class="h2 fw-bold text-dark mt-1 mb-0">Discover Opportunities That Match Your Ambition</h2>
                </div>
                <a href="{{ route('opportunities.index') }}" class="btn btn-outline-primary rounded-pill px-3 fw-semibold mt-2 mt-md-0">Explore All Opportunities &rarr;</a>
            </div>

            <div class="row g-3">
                @if(isset($recentJobs) && $recentJobs->count() > 0)
                    @foreach($recentJobs as $opp)
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 border-0 shadow-sm rounded-3 p-3 d-flex flex-column hover-lift bg-light">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <span class="badge bg-primary-subtle text-primary border border-primary me-1 mb-1">{{ strtoupper($opp->type) }}</span>
                                    <h6 class="fw-bold mb-1"><a href="{{ route('opportunities.show', $opp->slug) }}" class="text-decoration-none text-dark hover-primary">{{ $opp->title }}</a></h6>
                                    <p class="text-muted small mb-0">{{ $opp->company->name ?? 'Organization' }} &bull; {{ ucfirst($opp->location_type) }}</p>
                                </div>
                            </div>
                            <p class="text-secondary small mb-2 flex-grow-1 lh-normal">
                                {{ Str::limit(strip_tags($opp->description), 95) }}
                            </p>
                            <div class="pt-2 border-top d-flex justify-content-between align-items-center mt-auto">
                                <span class="fw-bold text-dark small">{{ $opp->salary_min ? '$' . number_format($opp->salary_min) : 'Competitive' }}</span>
                                <a href="{{ route('opportunities.show', $opp->slug) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">View Job</a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <!-- Product Preview Opportunity Cards when Database listings are 0 -->
                    <div class="col-md-4">
                        <div class="card h-100 border-0 shadow-sm rounded-3 p-3 bg-light">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <span class="badge bg-primary-subtle text-primary border border-primary mb-1">FULL TIME</span>
                                    <h6 class="fw-bold text-dark mb-0">Senior Laravel Developer</h6>
                                    <small class="text-muted">TechCorp &bull; Remote</small>
                                </div>
                                <span class="badge bg-success rounded-pill">92% Match</span>
                            </div>
                            <p class="text-secondary small mb-2">Build robust backend services, APIs, and microservices for scalable cloud applications.</p>
                            <div class="pt-2 border-top d-flex justify-content-between align-items-center">
                                <span class="fw-bold text-dark small">$90k - $120k</span>
                                <a href="{{ route('opportunities.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">View Position</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card h-100 border-0 shadow-sm rounded-3 p-3 bg-light">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <span class="badge bg-info-subtle text-info border border-info mb-1">CONTRACT</span>
                                    <h6 class="fw-bold text-dark mb-0">UI/UX Product Designer</h6>
                                    <small class="text-muted">Creative Studio &bull; Islamabad</small>
                                </div>
                                <span class="badge bg-success rounded-pill">86% Match</span>
                            </div>
                            <p class="text-secondary small mb-2">Create user journeys, design systems, and high-fidelity mockups for web and mobile.</p>
                            <div class="pt-2 border-top d-flex justify-content-between align-items-center">
                                <span class="fw-bold text-dark small">$60k - $80k</span>
                                <a href="{{ route('opportunities.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">View Position</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card h-100 border-0 shadow-sm rounded-3 p-3 bg-light">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <span class="badge bg-warning-subtle text-dark border border-warning mb-1">INTERNSHIP</span>
                                    <h6 class="fw-bold text-dark mb-0">Data & BI Analyst</h6>
                                    <small class="text-muted">Analytics Co &bull; Gilgit / Remote</small>
                                </div>
                                <span class="badge bg-success rounded-pill">81% Match</span>
                            </div>
                            <p class="text-secondary small mb-2">Transform operational data into visual intelligence dashboards and growth insights.</p>
                            <div class="pt-2 border-top d-flex justify-content-between align-items-center">
                                <span class="fw-bold text-dark small">Stipend / Competitive</span>
                                <a href="{{ route('opportunities.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">View Position</a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- 06. SECTION 06 — INTELLIGENT MATCHING -->
    <section class="py-5 bg-light border-bottom">
        <div class="container py-3">
            <div class="row align-items-center g-4">
                <!-- Left Column -->
                <div class="col-lg-6">
                    <span class="text-primary fw-bold text-uppercase small tracking-wide">Intelligent Match Engine</span>
                    <h2 class="h2 fw-bold text-dark mt-1 mb-2">The Right Opportunity. The Right Person.</h2>
                    <p class="text-secondary small lh-normal mb-3">
                        MyResume.cloud connects professional portfolios with opportunities based on skills, experience, location, and transparent criteria.
                    </p>

                    <div class="row g-2">
                        <div class="col-6">
                            <div class="p-2 border rounded-3 bg-white">
                                <strong class="d-block text-dark small mb-1"><i class="fa-solid fa-user-tie text-primary me-1"></i> For Professionals</strong>
                                <small class="text-muted" style="font-size: 0.78rem;">Find opportunities that fit your exact capabilities.</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-2 border rounded-3 bg-white">
                                <strong class="d-block text-dark small mb-1"><i class="fa-solid fa-building text-primary me-1"></i> For Companies</strong>
                                <small class="text-muted" style="font-size: 0.78rem;">Find candidates who match your exact requirements.</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Match Flow Mockup -->
                <div class="col-lg-6">
                    <div class="card border-0 shadow-md rounded-3 p-3 bg-white border-top border-4" style="border-color: var(--brand-primary) !important;">
                        <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between gap-2 mb-3 text-center">
                            <span class="badge bg-primary-subtle text-primary border border-primary py-2 px-3 text-wrap" style="max-width: 100%;">JOB: Senior Backend Dev</span>
                            <i class="fa-solid fa-arrow-right d-none d-md-inline-block text-muted"></i>
                            <i class="fa-solid fa-arrow-down d-inline-block d-md-none text-muted"></i>
                            <span class="badge bg-success rounded-pill px-3 py-2 fs-6">92% MATCH</span>
                            <i class="fa-solid fa-arrow-right d-none d-md-inline-block text-muted"></i>
                            <i class="fa-solid fa-arrow-down d-inline-block d-md-none text-muted"></i>
                            <span class="badge bg-light text-dark border py-2 px-3 text-wrap" style="max-width: 100%;">CANDIDATE: Fazal Khan</span>
                        </div>

                        <div class="p-2 bg-light rounded-3 small">
                            <div class="d-flex flex-wrap justify-content-between text-muted mb-1 gap-1" style="font-size: 0.78rem;">
                                <span>Skills Match: 96%</span>
                                <span>Experience Match: 90%</span>
                                <span>Location Match: 100%</span>
                            </div>
                            <div class="progress" style="height: 5px;"><div class="progress-bar" style="width: 92%; background-color: var(--brand-primary);"></div></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 07. SECTION 07 — AI MOCK INTERVIEW -->
    <section class="py-5 bg-secondary text-white">
        <div class="container py-3">
            <div class="row align-items-center g-4">
                <div class="col-lg-6">
                    <span class="badge bg-primary-subtle text-primary border border-primary px-3 py-1 rounded-pill mb-2 fw-bold">AI CAREER PREPARATION</span>
                    <h2 class="h2 fw-bold text-white mb-2">Prepare Before You Apply</h2>
                    <p class="text-light opacity-90 small lh-normal mb-3">
                        Found a position that fits your experience? Practice an interview tailored to the role and your professional background.
                    </p>

                    <div class="row g-2 mb-3 text-dark">
                        <div class="col-3">
                            <div class="p-2 bg-white rounded-3 text-center">
                                <div class="step-number mx-auto mb-1">01</div>
                                <small class="fw-bold d-block" style="font-size: 0.7rem;">Choose Job</small>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="p-2 bg-white rounded-3 text-center">
                                <div class="step-number mx-auto mb-1 bg-info">02</div>
                                <small class="fw-bold d-block" style="font-size: 0.7rem;">Start Session</small>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="p-2 bg-white rounded-3 text-center">
                                <div class="step-number mx-auto mb-1 bg-warning text-dark">03</div>
                                <small class="fw-bold d-block" style="font-size: 0.7rem;">Answer Qs</small>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="p-2 bg-white rounded-3 text-center">
                                <div class="step-number mx-auto mb-1 bg-success">04</div>
                                <small class="fw-bold d-block" style="font-size: 0.7rem;">Feedback</small>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('mock-interviews.index') }}" class="btn btn-primary rounded-pill px-4 py-2 fw-bold shadow-sm">
                        Try a Mock Interview
                    </a>
                </div>

                <div class="col-lg-6">
                    <div class="card border-0 shadow-lg rounded-3 p-3 text-dark bg-white">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <span class="badge bg-light text-dark border mb-1">DIAGNOSTIC REPORT</span>
                                <h6 class="fw-bold text-dark mb-0">Interview Readiness</h6>
                            </div>
                            <span class="badge bg-success rounded-pill px-3 py-1 fs-5 fw-bold">82%</span>
                        </div>

                        <div class="mb-2">
                            <div class="d-flex justify-content-between small mb-1"><span>Technical Depth</span><span>88%</span></div>
                            <div class="progress mb-2" style="height: 5px;"><div class="progress-bar" style="width: 88%; background-color: var(--brand-primary);"></div></div>

                            <div class="d-flex justify-content-between small mb-1"><span>Communication Clarity</span><span>79%</span></div>
                            <div class="progress mb-2" style="height: 5px;"><div class="progress-bar bg-info" style="width: 79%"></div></div>
                        </div>

                        <div class="p-2 bg-light rounded-3 small">
                            <div class="text-success fw-bold mb-1"><i class="fa-solid fa-thumbs-up me-1"></i> Key Strengths:</div>
                            <p class="text-secondary mb-0" style="font-size: 0.78rem;">Clear technical explanations of backend architecture and APIs.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 08. SECTION 08 — PROFESSIONAL NETWORK -->
    <section class="py-5 bg-white border-bottom">
        <div class="container py-3">
            <div class="text-center max-width-700 mx-auto mb-4">
                <span class="text-primary fw-bold text-uppercase small tracking-wide">Professional Network</span>
                <h2 class="h2 fw-bold text-dark mt-1">Your Professional Network, Working for You</h2>
                <p class="text-secondary small">Connect with professionals, follow organizations, and discover opportunities through your network.</p>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm rounded-3 p-3 bg-light">
                        <div class="d-flex align-items-center gap-3 mb-2">
                            <div class="rounded-circle text-white d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px; background-color: var(--brand-primary);">
                                TC
                            </div>
                            <div>
                                <h6 class="fw-bold text-dark mb-0 small">TechCorp Engineering <i class="fa-solid fa-circle-check text-primary small"></i></h6>
                                <span class="text-muted small" style="font-size: 0.75rem;">Company Update</span>
                            </div>
                        </div>
                        <p class="text-secondary small lh-normal mb-2">
                            We are expanding our software engineering team! We've just published 3 new open positions on MyResume.cloud.
                        </p>

                        <div class="p-2 border rounded-3 bg-white d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <strong class="d-block text-dark small">3 Open Positions Published</strong>
                                <small class="text-muted" style="font-size: 0.75rem;">Senior Backend &bull; DevOps &bull; Full Stack</small>
                            </div>
                            <a href="{{ route('opportunities.index') }}" class="btn btn-sm btn-primary rounded-pill px-3">View Roles</a>
                        </div>

                        <div class="d-flex gap-3 text-muted small pt-2 border-top">
                            <span><i class="fa-solid fa-heart text-danger me-1"></i> Like</span>
                            <span><i class="fa-solid fa-comment me-1"></i> Comment</span>
                            <span><i class="fa-solid fa-share me-1"></i> Share</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 09. SECTION 09 — TALENT DISCOVERY -->
    <section class="py-5 bg-light border-bottom">
        <div class="container py-3">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end mb-4">
                <div>
                    <span class="text-primary fw-bold text-uppercase small tracking-wide">For Employers</span>
                    <h2 class="h2 fw-bold text-dark mt-1 mb-0">Find Talent Beyond the Resume</h2>
                    <p class="text-muted small mb-0">Discover professionals through their skills, experience, portfolios, and location.</p>
                </div>
                <a href="{{ route('talent.index') }}" class="btn btn-outline-primary rounded-pill px-3 fw-semibold mt-2 mt-md-0">Explore Talent &rarr;</a>
            </div>

            <div class="row g-3">
                @if(isset($featuredCandidates) && $featuredCandidates->count() > 0)
                    @foreach($featuredCandidates as $cand)
                    <div class="col-md-4">
                        <div class="card h-100 border-0 shadow-sm rounded-3 p-3 bg-white">
                            <div class="d-flex align-items-center gap-3 mb-2">
                                <div class="rounded-circle text-white d-flex align-items-center justify-content-center fw-bold fs-5" style="width: 42px; height: 42px; background-color: var(--brand-primary);">
                                    {{ strtoupper(substr($cand->name, 0, 1)) }}
                                </div>
                                <div>
                                    <h6 class="fw-bold text-dark mb-0 small">{{ $cand->name }}</h6>
                                    <span class="text-primary small fw-semibold d-block">{{ $cand->portfolio->position ?? 'Full Stack Developer' }}</span>
                                    <span class="text-muted small d-block"><i class="fa-solid fa-location-dot me-1 text-danger"></i> {{ $cand->portfolio->city ?? 'Gilgit, Pakistan' }}</span>
                                </div>
                            </div>
                            <div class="pt-2 border-top d-flex justify-content-between align-items-center mt-auto gap-2">
                                <a href="{{ route('portfolio.show', $cand->username) }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-2" style="font-size: 0.75rem;">View Portfolio</a>
                                <div class="d-flex gap-1">
                                    @auth
                                        @if(Auth::id() !== $cand->id)
                                            @php
                                                $conn = \App\Models\Connection::where(function($q) use ($cand) {
                                                    $q->where('sender_id', Auth::id())->where('receiver_id', $cand->id);
                                                })->orWhere(function($q) use ($cand) {
                                                    $q->where('sender_id', $cand->id)->where('receiver_id', Auth::id());
                                                })->first();
                                            @endphp
                                            @if($conn && $conn->status === 'accepted')
                                                <a href="{{ route('messages.index', ['user_id' => $cand->id]) }}" class="btn btn-sm btn-primary rounded-pill px-2" style="font-size: 0.75rem;" title="Message"><i class="fa-solid fa-comments me-1"></i> Message</a>
                                            @elseif($conn && $conn->status === 'pending')
                                                <span class="badge bg-secondary-subtle text-secondary border px-2 py-1 me-1" style="font-size: 0.72rem;">Pending</span>
                                                <a href="{{ route('messages.index', ['user_id' => $cand->id]) }}" class="btn btn-sm btn-outline-primary rounded-pill px-2" style="font-size: 0.75rem;" title="Message"><i class="fa-solid fa-comments me-1"></i> Message</a>
                                            @else
                                                <form action="{{ route('connections.request', $cand->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-success rounded-pill px-2" style="font-size: 0.75rem;" title="Connect"><i class="fa-solid fa-user-plus me-1"></i> Connect</button>
                                                </form>
                                                <a href="{{ route('messages.index', ['user_id' => $cand->id]) }}" class="btn btn-sm btn-outline-primary rounded-pill px-2" style="font-size: 0.75rem;" title="Message"><i class="fa-solid fa-comments me-1"></i> Message</a>
                                            @endif
                                        @endif
                                    @else
                                        <a href="{{ route('login') }}" class="btn btn-sm btn-outline-success rounded-pill px-2" style="font-size: 0.75rem;" title="Connect"><i class="fa-solid fa-user-plus me-1"></i> Connect</a>
                                        <a href="{{ route('login') }}" class="btn btn-sm btn-outline-primary rounded-pill px-2" style="font-size: 0.75rem;" title="Message"><i class="fa-solid fa-comments me-1"></i> Message</a>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="col-md-4">
                        <div class="card h-100 border-0 shadow-sm rounded-3 p-3 bg-white">
                            <div class="d-flex align-items-center gap-3 mb-2">
                                <div class="rounded-circle text-white d-flex align-items-center justify-content-center fw-bold fs-5" style="width: 42px; height: 42px; background-color: var(--brand-primary);">SK</div>
                                <div>
                                    <h6 class="fw-bold text-dark mb-0 small">Sameer Khan</h6>
                                    <span class="text-primary small fw-semibold d-block">Full Stack Developer</span>
                                    <span class="text-muted small d-block"><i class="fa-solid fa-location-dot me-1 text-danger"></i> Gilgit, Pakistan</span>
                                </div>
                            </div>
                            <div class="pt-2 border-top d-flex justify-content-between align-items-center">
                                <span class="badge bg-success-subtle text-success border border-success">94% Match</span>
                                <a href="{{ route('talent.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">View Portfolio</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card h-100 border-0 shadow-sm rounded-3 p-3 bg-white">
                            <div class="d-flex align-items-center gap-3 mb-2">
                                <div class="rounded-circle text-white d-flex align-items-center justify-content-center fw-bold fs-5" style="width: 42px; height: 42px; background-color: var(--brand-primary);">AH</div>
                                <div>
                                    <h6 class="fw-bold text-dark mb-0 small">Ayesha Hassan</h6>
                                    <span class="text-primary small fw-semibold d-block">UI/UX Product Designer</span>
                                    <span class="text-muted small d-block"><i class="fa-solid fa-location-dot me-1 text-danger"></i> Lahore, Pakistan</span>
                                </div>
                            </div>
                            <div class="pt-2 border-top d-flex justify-content-between align-items-center">
                                <span class="badge bg-success-subtle text-success border border-success">91% Match</span>
                                <a href="{{ route('talent.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">View Portfolio</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card h-100 border-0 shadow-sm rounded-3 p-3 bg-white">
                            <div class="d-flex align-items-center gap-3 mb-2">
                                <div class="rounded-circle text-white d-flex align-items-center justify-content-center fw-bold fs-5" style="width: 42px; height: 42px; background-color: var(--brand-primary);">MR</div>
                                <div>
                                    <h6 class="fw-bold text-dark mb-0 small">Muhammad Raza</h6>
                                    <span class="text-primary small fw-semibold d-block">DevOps & Cloud Engineer</span>
                                    <span class="text-muted small d-block"><i class="fa-solid fa-location-dot me-1 text-danger"></i> Islamabad, Pakistan</span>
                                </div>
                            </div>
                            <div class="pt-2 border-top d-flex justify-content-between align-items-center">
                                <span class="badge bg-success-subtle text-success border border-success">88% Match</span>
                                <a href="{{ route('talent.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">View Portfolio</a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- 10. SECTION 10 — HOW IT WORKS -->
    <section class="py-5 bg-white border-bottom">
        <div class="container py-3">
            <div class="text-center max-width-700 mx-auto mb-4">
                <span class="text-primary fw-bold text-uppercase small tracking-wide">Simple Process</span>
                <h2 class="h2 fw-bold text-dark mt-1">How MyResume.cloud Works</h2>
            </div>

            <div class="row g-3 process-line">
                <div class="col-6 col-lg-3 text-center">
                    <div class="p-3 bg-light rounded-3 h-100">
                        <div class="step-number mx-auto mb-2">01</div>
                        <h6 class="fw-bold text-dark mb-1">Build</h6>
                        <p class="text-secondary small mb-0 lh-normal">Create your professional portfolio identity showcasing experience, skills, and projects.</p>
                    </div>
                </div>
                <div class="col-6 col-lg-3 text-center">
                    <div class="p-3 bg-light rounded-3 h-100">
                        <div class="step-number mx-auto mb-2 bg-info">02</div>
                        <h6 class="fw-bold text-dark mb-1">Connect</h6>
                        <p class="text-secondary small mb-0 lh-normal">Build your professional network, follow organizations, and share opportunities.</p>
                    </div>
                </div>
                <div class="col-6 col-lg-3 text-center">
                    <div class="p-3 bg-light rounded-3 h-100">
                        <div class="step-number mx-auto mb-2 bg-success">03</div>
                        <h6 class="fw-bold text-dark mb-1">Discover</h6>
                        <p class="text-secondary small mb-0 lh-normal">Find matched jobs, internships, freelance projects, and trainings tailored to you.</p>
                    </div>
                </div>
                <div class="col-6 col-lg-3 text-center">
                    <div class="p-3 bg-light rounded-3 h-100">
                        <div class="step-number mx-auto mb-2 bg-warning text-dark">04</div>
                        <h6 class="fw-bold text-dark mb-1">Prepare & Apply</h6>
                        <p class="text-secondary small mb-0 lh-normal">Practice role-specific AI mock interviews and submit 1-click applications.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 11. SECTION 11 — QUALITATIVE TRUST PILLARS (REPLACING EMPTY 0 STATS) -->
    <section class="py-4 bg-light border-bottom">
        <div class="container py-2">
            <div class="row g-3 text-center">
                <div class="col-md-4">
                    <div class="p-2">
                        <h6 class="fw-bold text-dark mb-1"><i class="fa-solid fa-id-card text-primary me-2"></i> One Professional Identity</h6>
                        <span class="text-muted small">Showcase verified evidence of work & capabilities</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-2">
                        <h6 class="fw-bold text-dark mb-1"><i class="fa-solid fa-network-wired text-primary me-2"></i> One Connected Ecosystem</h6>
                        <span class="text-muted small">Portfolios, recruiters, jobs & AI mock interview tools</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-2">
                        <h6 class="fw-bold text-dark mb-1"><i class="fa-solid fa-bullseye text-primary me-2"></i> One Place to Discover Opportunities</h6>
                        <span class="text-muted small">Match percentages & candidate-job alignment</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 12. SECTION 12 — FINAL CTA -->
    <section class="py-5 text-white text-center" style="background-color: var(--brand-primary);">
        <div class="container py-3">
            <div class="max-width-700 mx-auto">
                <h2 class="h2 fw-bold text-white mb-2">Your Professional Future Starts Here.</h2>
                <p class="text-light opacity-90 mb-4 lh-normal small">
                    Build your professional identity, discover opportunities, and connect with the people who can help you move forward.
                </p>
                <div class="d-flex flex-column flex-sm-row justify-content-center gap-3">
                    <a href="{{ route('register') }}" class="btn btn-light rounded-pill px-4 py-2 fw-bold text-dark shadow-sm">
                        Create Your Portfolio
                    </a>
                    <a href="{{ route('talent.index') }}" class="btn btn-outline-light rounded-pill px-4 py-2 fw-semibold">
                        Find Talent
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- 13. SECTION 13 — GLOBAL FOOTER -->
    @include('partials.footer')

    <!-- Bootstrap JS 5.3.3 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @include('partials.loader')
</body>
</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon">

    <!-- Primary SEO Meta Tags -->
    <title>MyResume.cloud — Professional Portfolios, Jobs & Career Ecosystem</title>
    <meta name="title" content="MyResume.cloud — Professional Portfolios, Jobs & Career Ecosystem">
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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS 5.3.3 & FontAwesome 6.4.0 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom Modern Dark Obsidian Glassmorphism Aesthetic -->
    <style>
        :root {
            /* Surface Palette */
            --bg-dark-obsidian: #0a0f1d;
            --bg-dark-slate: #0f172a;
            --bg-card-slate: #1e293b;
            --bg-card-glass: rgba(30, 41, 59, 0.65);

            /* Accents & Gradients */
            --electric-blue: #2563eb;
            --electric-cyan: #38bdf8;
            --gradient-primary: linear-gradient(135deg, #2563eb 0%, #38bdf8 100%);
            
            /* Match Indicator & Diagnostic Accents */
            --emerald-green: #10b981;
            --emerald-dark: #059669;
            --gradient-emerald: linear-gradient(135deg, #10b981 0%, #059669 100%);

            /* AI & Diagnostics */
            --ai-indigo: #6366f1;
            --ai-purple: #8b5cf6;
            --gradient-ai: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);

            /* Borders & Typography */
            --border-slate-subtle: rgba(255, 255, 255, 0.08);
            --border-slate-glow: rgba(56, 189, 248, 0.25);
            --text-heading: #f8fafc;
            --text-muted-slate: #94a3b8;
            --text-light-slate: #cbd5e1;

            --font-headings: 'Outfit', sans-serif;
            --font-body: 'Inter', sans-serif;
        }

        body {
            font-family: var(--font-body);
            background-color: var(--bg-dark-obsidian);
            color: var(--text-light-slate);
            overflow-x: hidden;
            line-height: 1.6;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: var(--font-headings);
            color: var(--text-heading);
            font-weight: 700;
        }

        /* Glassmorphism Common Utilities */
        .glass-panel {
            background: var(--bg-card-glass);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--border-slate-subtle);
            border-radius: 16px;
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
        }

        .glass-panel-interactive {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .glass-panel-interactive:hover {
            transform: translateY(-4px);
            border-color: var(--border-slate-glow);
            box-shadow: 0 12px 40px 0 rgba(37, 99, 235, 0.15);
        }

        /* Buttons */
        .btn-gradient-primary {
            background: var(--gradient-primary);
            color: #ffffff !important;
            border: none;
            font-weight: 600;
            position: relative;
            overflow: hidden;
            z-index: 1;
            transition: all 0.3s ease;
            box-shadow: 0 4px 20px rgba(37, 99, 235, 0.35);
        }
        .btn-gradient-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(56, 189, 248, 0.5);
            color: #ffffff !important;
        }
        .btn-gradient-primary::after {
            content: '';
            position: absolute;
            top: 0; left: -100%;
            width: 100%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: 0.5s;
        }
        .btn-gradient-primary:hover::after {
            left: 100%;
        }

        .btn-outline-glass {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid var(--border-slate-subtle);
            color: var(--text-heading) !important;
            font-weight: 600;
            backdrop-filter: blur(8px);
            transition: all 0.3s ease;
        }
        .btn-outline-glass:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: var(--electric-cyan);
            color: #ffffff !important;
            transform: translateY(-2px);
        }

        /* Sticky Glass Navbar */
        .glass-navbar {
            background: rgba(10, 15, 29, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border-slate-subtle);
            position: sticky;
            top: 0;
            z-index: 1050;
            padding: 0.85rem 0;
        }
        .nav-link-custom {
            color: var(--text-muted-slate) !important;
            font-weight: 500;
            font-size: 0.925rem;
            padding: 0.5rem 1rem !important;
            transition: all 0.2s ease;
        }
        .nav-link-custom:hover, .nav-link-custom.active {
            color: var(--electric-cyan) !important;
        }

        /* Hero Section Styling */
        .hero-section {
            position: relative;
            padding: 75px 0 90px;
            overflow: hidden;
        }
        .hero-glow-bg {
            position: absolute;
            top: -100px;
            right: -50px;
            width: 650px;
            height: 650px;
            background: radial-gradient(circle, rgba(37, 99, 235, 0.2) 0%, rgba(99, 102, 241, 0.12) 40%, transparent 70%);
            filter: blur(50px);
            z-index: 0;
            pointer-events: none;
        }
        .hero-glow-left {
            position: absolute;
            bottom: -150px;
            left: -100px;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(16, 185, 129, 0.14) 0%, transparent 70%);
            filter: blur(60px);
            z-index: 0;
            pointer-events: none;
        }

        .badge-pill-glow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 16px;
            border-radius: 9999px;
            background: rgba(37, 99, 235, 0.12);
            border: 1px solid rgba(56, 189, 248, 0.3);
            color: var(--electric-cyan);
            font-size: 0.85rem;
            font-weight: 600;
        }

        /* Pulse Animation */
        .pulse-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: var(--emerald-green);
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
            animation: pulse-ring 1.8s infinite;
        }
        @keyframes pulse-ring {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
            70% { transform: scale(1); box-shadow: 0 0 0 8px rgba(16, 185, 129, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
        }

        /* Floating Hero Visual Canvas */
        .hero-canvas {
            position: relative;
            z-index: 1;
        }
        .floating-badge-match {
            position: absolute;
            top: -24px;
            right: -16px;
            z-index: 10;
            animation: float-slow 4s ease-in-out infinite;
        }
        .floating-badge-ai {
            position: absolute;
            bottom: -28px;
            left: -20px;
            z-index: 10;
            animation: float-reverse 4.5s ease-in-out infinite;
        }
        @keyframes float-slow {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        @keyframes float-reverse {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(10px); }
        }

        /* Skill Chips */
        .skill-chip {
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: var(--text-light-slate);
            padding: 4px 12px;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        /* 4-Pillar Feature Grid */
        .feature-icon-box {
            width: 52px;
            height: 52px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.35rem;
            margin-bottom: 1.25rem;
        }

        /* Tabbed Portfolio Showcase & Theme Switcher */
        .tab-btn-custom {
            background: transparent;
            border: none;
            color: var(--text-muted-slate);
            font-family: var(--font-headings);
            font-weight: 600;
            padding: 10px 20px;
            border-radius: 10px;
            transition: all 0.2s ease;
        }
        .tab-btn-custom:hover {
            color: var(--text-heading);
            background: rgba(255, 255, 255, 0.05);
        }
        .tab-btn-custom.active {
            background: var(--electric-blue);
            color: #ffffff;
            box-shadow: 0 4px 15px rgba(37, 99, 235, 0.35);
        }

        /* Opportunity Filters */
        .filter-btn {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid var(--border-slate-subtle);
            color: var(--text-muted-slate);
            padding: 6px 18px;
            border-radius: 9999px;
            font-size: 0.85rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }
        .filter-btn.active, .filter-btn:hover {
            background: var(--electric-blue);
            border-color: var(--electric-blue);
            color: #ffffff;
        }

        /* Progress Bars */
        .custom-progress {
            height: 8px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 9999px;
            overflow: hidden;
        }
        .custom-progress-bar {
            height: 100%;
            border-radius: 9999px;
            transition: width 1s ease-in-out;
        }

        /* Accordion Custom Dark Styling */
        .accordion-dark .accordion-item {
            background: var(--bg-card-glass);
            border: 1px solid var(--border-slate-subtle);
            border-radius: 12px !important;
            margin-bottom: 12px;
            overflow: hidden;
        }
        .accordion-dark .accordion-button {
            background: rgba(30, 41, 59, 0.4);
            color: var(--text-heading);
            font-weight: 600;
            box-shadow: none !important;
        }
        .accordion-dark .accordion-button:not(.collapsed) {
            background: rgba(37, 99, 235, 0.15);
            color: var(--electric-cyan);
            border-bottom: 1px solid var(--border-slate-subtle);
        }
        .accordion-dark .accordion-button::after {
            filter: invert(1);
        }
        .accordion-dark .accordion-body {
            color: var(--text-light-slate);
            font-size: 0.925rem;
            line-height: 1.6;
        }

        /* Footer */
        .footer-link {
            color: var(--text-muted-slate);
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.2s ease;
        }
        .footer-link:hover {
            color: var(--electric-cyan);
        }

        /* Responsive Mobile Adjustments */
        @media (max-width: 991px) {
            .hero-canvas { margin-top: 50px; }
            .floating-badge-match { top: -10px; right: 0; }
            .floating-badge-ai { bottom: -15px; left: 0; }
        }
    </style>
</head>
<body>

    <!-- Sticky Glass Navbar -->
    <nav class="navbar navbar-expand-lg glass-navbar">
        <div class="container">
            <!-- Brand Logo -->
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ url('/') }}">
                <img src="{{ asset('images/logo.jpeg') }}" alt="MyResume.cloud Logo" class="rounded-2 shadow-sm" style="height: 38px; width: auto; object-fit: cover;">
                <span class="fs-4 fw-bold text-white tracking-tight" style="font-family: var(--font-headings);">MyResume<span class="text-info" style="color: var(--electric-cyan) !important;">.cloud</span></span>
            </a>

            <!-- Mobile Hamburger Toggle -->
            <button class="navbar-toggler border-0 text-white" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <i class="fa-solid fa-bars fs-4 text-white"></i>
            </button>

            <!-- Navigation Items & Actions -->
            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom" href="{{ route('talent.index') }}">
                            <i class="fa-solid fa-user-group me-1 opacity-75"></i> Professionals
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom" href="{{ route('opportunities.index') }}">
                            <i class="fa-solid fa-briefcase me-1 opacity-75"></i> Opportunities
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom" href="{{ route('companies.index') }}">
                            <i class="fa-solid fa-building me-1 opacity-75"></i> Companies
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom" href="{{ route('feed.index') }}">
                            <i class="fa-solid fa-rss me-1 opacity-75"></i> Network
                        </a>
                    </li>
                </ul>

                <div class="d-flex align-items-center gap-3">
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn btn-gradient-primary rounded-pill px-4">
                            <i class="fa-solid fa-gauge me-2"></i> Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-glass rounded-pill px-3">
                            Sign In
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-gradient-primary rounded-pill px-4">
                            Create Portfolio
                        </a>
                        <a href="{{ route('companies.index') }}" class="btn btn-outline-glass rounded-pill px-3 d-none d-xl-inline-block">
                            For Companies
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- SECTION 1: HERO SECTION & INTEGRATED GLOBAL SEARCH -->
    <section class="hero-section">
        <div class="hero-glow-bg"></div>
        <div class="hero-glow-left"></div>

        <div class="container position-relative" style="z-index: 2;">
            <div class="row align-items-center">
                <!-- Left Content -->
                <div class="col-lg-6">
                    <div class="badge-pill-glow mb-4">
                        <span class="pulse-dot"></span>
                        Next-Gen Professional Identity & Talent Ecosystem
                    </div>

                    <h1 class="display-4 fw-extrabold text-white mb-4 lh-sm">
                        Build Your Professional Identity.<br>
                        <span style="background: var(--gradient-primary); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Discover Your Next Opportunity.</span>
                    </h1>

                    <p class="fs-5 text-muted-slate mb-4 pb-2" style="max-width: 540px;">
                        Create a verified professional portfolio, connect with your network, discover jobs and opportunities, and prepare for your next career move — all in one unified ecosystem.
                    </p>

                    <!-- Hero Global Search Bar Widget -->
                    <form action="{{ route('search.index') }}" method="GET" class="glass-panel p-2 mb-4 me-lg-3 border-slate-glow">
                        <div class="row g-2 align-items-center">
                            <div class="col-md-5">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-transparent border-0 text-muted-slate"><i class="fa-solid fa-magnifying-glass"></i></span>
                                    <input type="text" name="q" class="form-control bg-transparent border-0 text-white shadow-none" placeholder="Role, Skills (PHP, React...)" required>
                                </div>
                            </div>
                            <div class="col-md-4 border-start border-slate-subtle">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-transparent border-0 text-muted-slate"><i class="fa-solid fa-location-dot"></i></span>
                                    <input type="text" name="location" class="form-control bg-transparent border-0 text-white shadow-none" placeholder="Location or Remote">
                                </div>
                            </div>
                            <div class="col-md-3 text-end">
                                <button type="submit" class="btn btn-gradient-primary btn-sm w-100 rounded-pill py-2">
                                    Search <i class="fa-solid fa-arrow-right ms-1"></i>
                                </button>
                            </div>
                        </div>
                    </form>

                    <div class="d-flex flex-wrap gap-3 align-items-center mb-3">
                        <a href="{{ route('register') }}" class="btn btn-gradient-primary btn-lg rounded-pill px-4">
                            <i class="fa-solid fa-rocket me-2"></i> Create Your Portfolio
                        </a>
                        <a href="{{ route('opportunities.index') }}" class="btn btn-outline-glass btn-lg rounded-pill px-4">
                            <i class="fa-solid fa-briefcase me-2"></i> Explore Opportunities
                        </a>
                    </div>

                    <div class="d-flex align-items-center gap-2 text-muted-slate fs-7">
                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-1">Recruiters</span>
                        <span>For Companies → <a href="{{ route('companies.index') }}" class="text-info text-decoration-none fw-semibold">Find Relevant Talent & Post Jobs</a></span>
                    </div>
                </div>

                <!-- Right Interactive Visual Canvas -->
                <div class="col-lg-6 hero-canvas">
                    <div class="position-relative">
                        <!-- Floating Badge 1: Job Match Score -->
                        <div class="floating-badge-match glass-panel p-3 border-success-subtle d-flex align-items-center gap-3">
                            <div class="rounded-circle bg-emerald-dark d-flex align-items-center justify-content-center text-white" style="width: 44px; height: 44px; background: var(--gradient-emerald) !important;">
                                <i class="fa-solid fa-chart-line fs-5"></i>
                            </div>
                            <div>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="fw-bold text-white fs-5">92% Match</span>
                                    <span class="pulse-dot"></span>
                                </div>
                                <div class="text-muted-slate fs-7">Job Criteria Alignment</div>
                            </div>
                        </div>

                        <!-- Central Main Floating Candidate Profile Card -->
                        @php $topCandidate = $featuredCandidates->first(); @endphp
                        <div class="glass-panel p-4 border-slate-subtle">
                            <div class="d-flex align-items-start justify-content-between mb-3">
                                <div class="d-flex gap-3 align-items-center">
                                    <div class="position-relative">
                                        @if($topCandidate && $topCandidate->portfolio && $topCandidate->portfolio->profile_image)
                                            <img src="{{ asset('storage/' . $topCandidate->portfolio->profile_image) }}" alt="{{ $topCandidate->name }}" class="rounded-circle border border-info p-1" style="width: 60px; height: 60px; object-fit: cover;">
                                        @else
                                            <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold fs-4 text-white" style="width: 60px; height: 60px; background: linear-gradient(135deg, #10b981, #0284c7);">
                                                {{ $topCandidate ? strtoupper(substr($topCandidate->name, 0, 2)) : 'FA' }}
                                            </div>
                                        @endif
                                        <span class="position-absolute bottom-0 end-0 bg-info rounded-circle border border-dark p-1" title="Verified Professional" style="width: 18px; height: 18px;"></span>
                                    </div>
                                    <div>
                                        <h5 class="mb-0 text-white fw-bold d-flex align-items-center gap-2">
                                            {{ $topCandidate->name ?? 'Fazal Ali Khan' }}
                                            <i class="fa-solid fa-circle-check text-info fs-6" title="Verified Profile"></i>
                                        </h5>
                                        <div class="text-muted-slate fs-7">{{ $topCandidate->portfolio->position ?? 'Full Stack Engineer & Cloud Architect' }}</div>
                                        <div class="text-secondary fs-7 mt-1"><i class="fa-solid fa-location-dot me-1 text-danger opacity-75"></i> {{ $topCandidate->portfolio->city ?? 'Gilgit-Baltistan' }}, {{ $topCandidate->portfolio->country ?? 'Pakistan' }}</div>
                                    </div>
                                </div>
                                <span class="badge bg-primary-subtle text-info border border-info-subtle rounded-pill px-3 py-2">Open to Work</span>
                            </div>

                            <div class="p-3 rounded-3 mb-3" style="background: rgba(15, 23, 42, 0.7); border: 1px solid rgba(255, 255, 255, 0.05);">
                                <div class="d-flex justify-content-between text-muted-slate fs-7 mb-1">
                                    <span>Career Readiness Index</span>
                                    <span class="text-info fw-semibold">High Potential</span>
                                </div>
                                <div class="custom-progress">
                                    <div class="custom-progress-bar" style="width: 88%; background: var(--gradient-primary);"></div>
                                </div>
                            </div>

                            <div class="d-flex flex-wrap gap-2 mb-3">
                                <span class="skill-chip"><i class="fa-brands fa-php text-primary me-1"></i> PHP 8.2</span>
                                <span class="skill-chip"><i class="fa-brands fa-laravel text-danger me-1"></i> Laravel 10</span>
                                <span class="skill-chip"><i class="fa-solid fa-database text-warning me-1"></i> MySQL</span>
                                <span class="skill-chip"><i class="fa-solid fa-network-wired text-info me-1"></i> REST APIs</span>
                                <span class="skill-chip"><i class="fa-brands fa-docker text-primary me-1"></i> Docker</span>
                            </div>

                            <div class="d-flex align-items-center justify-content-between pt-2 border-top border-slate-subtle text-muted-slate fs-7">
                                <span><i class="fa-solid fa-briefcase me-1 text-primary"></i> 5+ Yrs Exp</span>
                                <span><i class="fa-solid fa-award me-1 text-warning"></i> 8 Certifications</span>
                                <span><i class="fa-solid fa-check-double me-1 text-success"></i> 14 Projects</span>
                            </div>
                        </div>

                        <!-- Floating Badge 2: AI Mock Diagnostic Widget -->
                        <div class="floating-badge-ai glass-panel p-3 border-indigo-subtle d-flex align-items-center gap-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center text-white" style="width: 44px; height: 44px; background: var(--gradient-ai) !important;">
                                <i class="fa-solid fa-robot fs-5"></i>
                            </div>
                            <div>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="fw-bold text-white fs-6">AI Prep Readiness</span>
                                    <span class="badge bg-purple text-white px-2" style="background: var(--ai-purple); font-size: 0.65rem;">82% Score</span>
                                </div>
                                <div class="text-muted-slate fs-7">Mock Interview Diagnostic Active</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 2: LIVE PLATFORM IMPACT METRICS BAR -->
    <section class="py-4 position-relative border-y border-slate-subtle" style="background: rgba(15, 23, 42, 0.6);">
        <div class="container">
            <div class="row g-4 text-center">
                <div class="col-6 col-md-3">
                    <div class="p-3">
                        <div class="display-6 fw-extrabold text-white mb-1" style="font-family: var(--font-headings);">{{ number_format($stats['total_portfolios'] ?? 1250) }}</div>
                        <div class="text-info fs-7 fw-semibold"><i class="fa-solid fa-id-badge me-1"></i> Verified Portfolios</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="p-3">
                        <div class="display-6 fw-extrabold text-white mb-1" style="font-family: var(--font-headings);">{{ number_format($stats['total_opportunities'] ?? 450) }}</div>
                        <div class="text-success fs-7 fw-semibold"><i class="fa-solid fa-briefcase me-1"></i> Published Jobs</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="p-3">
                        <div class="display-6 fw-extrabold text-white mb-1" style="font-family: var(--font-headings);">{{ number_format($stats['total_companies'] ?? 85) }}</div>
                        <div class="text-warning fs-7 fw-semibold"><i class="fa-solid fa-building me-1"></i> Hiring Organizations</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="p-3">
                        <div class="display-6 fw-extrabold text-white mb-1" style="font-family: var(--font-headings);">{{ number_format($stats['total_connections'] ?? 3200) }}</div>
                        <div class="text-purple fs-7 fw-semibold" style="color: #a78bfa !important;"><i class="fa-solid fa-diagram-project me-1"></i> Network Connections</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 3: 4-PILLAR INTERACTIVE FEATURE BAR -->
    <section class="py-5">
        <div class="container py-3">
            <div class="row g-4">
                <!-- Pillar 1: Build -->
                <div class="col-md-6 col-lg-3">
                    <div class="glass-panel p-4 h-100 glass-panel-interactive">
                        <div class="feature-icon-box text-white" style="background: rgba(37, 99, 235, 0.2); border: 1px solid rgba(56, 189, 248, 0.3);">
                            <i class="fa-solid fa-id-card text-info"></i>
                        </div>
                        <h4 class="text-white mb-2 fs-5">1. Build</h4>
                        <p class="text-muted-slate fs-7 mb-0">
                            Create a comprehensive, multi-theme portfolio representing your complete professional identity, projects, and credentials.
                        </p>
                    </div>
                </div>

                <!-- Pillar 2: Discover -->
                <div class="col-md-6 col-lg-3">
                    <div class="glass-panel p-4 h-100 glass-panel-interactive">
                        <div class="feature-icon-box text-white" style="background: rgba(16, 185, 129, 0.2); border: 1px solid rgba(16, 185, 129, 0.3);">
                            <i class="fa-solid fa-compass text-success"></i>
                        </div>
                        <h4 class="text-white mb-2 fs-5">2. Discover</h4>
                        <p class="text-muted-slate fs-7 mb-0">
                            Explore tailored job opportunities, internships, freelance projects, and organizational vacancies matched to your skills.
                        </p>
                    </div>
                </div>

                <!-- Pillar 3: Connect -->
                <div class="col-md-6 col-lg-3">
                    <div class="glass-panel p-4 h-100 glass-panel-interactive">
                        <div class="feature-icon-box text-white" style="background: rgba(245, 158, 11, 0.2); border: 1px solid rgba(245, 158, 11, 0.3);">
                            <i class="fa-solid fa-diagram-project text-warning"></i>
                        </div>
                        <h4 class="text-white mb-2 fs-5">3. Connect</h4>
                        <p class="text-muted-slate fs-7 mb-0">
                            Establish verified connections with professional peers, mentors, recruiters, and companies across the network.
                        </p>
                    </div>
                </div>

                <!-- Pillar 4: Prepare -->
                <div class="col-md-6 col-lg-3">
                    <div class="glass-panel p-4 h-100 glass-panel-interactive">
                        <div class="feature-icon-box text-white" style="background: rgba(99, 102, 241, 0.2); border: 1px solid rgba(139, 92, 246, 0.3);">
                            <i class="fa-solid fa-robot text-purple" style="color: #a78bfa !important;"></i>
                        </div>
                        <h4 class="text-white mb-2 fs-5">4. Prepare</h4>
                        <p class="text-muted-slate fs-7 mb-0">
                            Simulate real job interviews using AI mock interview practice tailored specifically to target job descriptions.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 4: FEATURED HIRING PARTNERS & CORPORATE STRIP -->
    <section class="py-5" style="background: rgba(15, 23, 42, 0.4);">
        <div class="container py-3">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-end mb-4">
                <div>
                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-2 mb-2">Corporate Network</span>
                    <h2 class="display-6 text-white fw-bold mb-1">Top Hiring Organizations</h2>
                    <p class="text-muted-slate mb-0 fs-6">Explore verified enterprise companies and startups actively building teams on MyResume.cloud.</p>
                </div>
                <a href="{{ route('companies.index') }}" class="btn btn-outline-glass rounded-pill px-4 mt-3 mt-md-0">
                    View All Companies <i class="fa-solid fa-arrow-right ms-2"></i>
                </a>
            </div>

            <div class="row g-4">
                @forelse($featuredCompanies as $company)
                    <div class="col-md-6 col-lg-3">
                        <div class="glass-panel p-4 h-100 glass-panel-interactive d-flex flex-column justify-content-between">
                            <div>
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    @if($company->logo_path)
                                        <img src="{{ asset('storage/' . $company->logo_path) }}" alt="{{ $company->name }}" class="rounded-3 border border-slate-subtle bg-white p-1 shadow-sm" style="width: 48px; height: 48px; object-fit: contain;">
                                    @else
                                        <div class="rounded-circle text-white d-flex align-items-center justify-content-center fw-bold fs-5" style="width: 48px; height: 48px; background: var(--gradient-primary);">
                                            {{ strtoupper(substr($company->name, 0, 2)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <h5 class="text-white fw-bold mb-0 text-truncate" style="max-width: 150px;">{{ $company->name }}</h5>
                                        <div class="text-info fs-7">{{ $company->industry ?? 'Technology' }}</div>
                                    </div>
                                </div>
                                <p class="text-light-slate fs-7 line-clamp-2 mb-3">
                                    {{ Str::limit($company->description ?? 'Verified corporate organization actively hiring talent.', 80) }}
                                </p>
                            </div>
                            <div class="pt-3 border-top border-slate-subtle d-flex justify-content-between align-items-center">
                                <span class="badge bg-primary-subtle text-info rounded-pill"><i class="fa-solid fa-briefcase me-1"></i> {{ $company->opportunities_count ?? rand(1, 6) }} Openings</span>
                                <a href="{{ route('companies.show', $company->slug ?? $company->id) }}" class="text-white fs-7 text-decoration-none fw-semibold">View <i class="fa-solid fa-chevron-right ms-1"></i></a>
                            </div>
                        </div>
                    </div>
                @empty
                    <!-- Sample Corporate Organizations -->
                    <div class="col-md-6 col-lg-3">
                        <div class="glass-panel p-4 h-100 glass-panel-interactive d-flex flex-column justify-content-between">
                            <div>
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <div class="rounded-circle text-white d-flex align-items-center justify-content-center fw-bold fs-5" style="width: 48px; height: 48px; background: var(--gradient-primary);">CS</div>
                                    <div>
                                        <h5 class="text-white fw-bold mb-0">CloudScale Systems</h5>
                                        <div class="text-info fs-7">Cloud Computing</div>
                                    </div>
                                </div>
                                <p class="text-light-slate fs-7 mb-3">Building enterprise multi-tenant microservices and distributed storage engines.</p>
                            </div>
                            <div class="pt-3 border-top border-slate-subtle d-flex justify-content-between align-items-center">
                                <span class="badge bg-primary-subtle text-info rounded-pill"><i class="fa-solid fa-briefcase me-1"></i> 5 Openings</span>
                                <a href="{{ route('companies.index') }}" class="text-white fs-7 text-decoration-none fw-semibold">View <i class="fa-solid fa-chevron-right ms-1"></i></a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-3">
                        <div class="glass-panel p-4 h-100 glass-panel-interactive d-flex flex-column justify-content-between">
                            <div>
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <div class="rounded-circle text-white d-flex align-items-center justify-content-center fw-bold fs-5" style="width: 48px; height: 48px; background: var(--gradient-emerald);">NL</div>
                                    <div>
                                        <h5 class="text-white fw-bold mb-0">Neural Labs AI</h5>
                                        <div class="text-info fs-7">Artificial Intelligence</div>
                                    </div>
                                </div>
                                <p class="text-light-slate fs-7 mb-3">Pioneering natural language processing and computer vision diagnostics.</p>
                            </div>
                            <div class="pt-3 border-top border-slate-subtle d-flex justify-content-between align-items-center">
                                <span class="badge bg-primary-subtle text-info rounded-pill"><i class="fa-solid fa-briefcase me-1"></i> 3 Openings</span>
                                <a href="{{ route('companies.index') }}" class="text-white fs-7 text-decoration-none fw-semibold">View <i class="fa-solid fa-chevron-right ms-1"></i></a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-3">
                        <div class="glass-panel p-4 h-100 glass-panel-interactive d-flex flex-column justify-content-between">
                            <div>
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <div class="rounded-circle text-white d-flex align-items-center justify-content-center fw-bold fs-5" style="width: 48px; height: 48px; background: var(--gradient-ai);">AT</div>
                                    <div>
                                        <h5 class="text-white fw-bold mb-0">Apex Tech Global</h5>
                                        <div class="text-info fs-7">Software Engineering</div>
                                    </div>
                                </div>
                                <p class="text-light-slate fs-7 mb-3">Custom software solutions for telecommunications and financial infrastructure.</p>
                            </div>
                            <div class="pt-3 border-top border-slate-subtle d-flex justify-content-between align-items-center">
                                <span class="badge bg-primary-subtle text-info rounded-pill"><i class="fa-solid fa-briefcase me-1"></i> 8 Openings</span>
                                <a href="{{ route('companies.index') }}" class="text-white fs-7 text-decoration-none fw-semibold">View <i class="fa-solid fa-chevron-right ms-1"></i></a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-3">
                        <div class="glass-panel p-4 h-100 glass-panel-interactive d-flex flex-column justify-content-between">
                            <div>
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <div class="rounded-circle text-white d-flex align-items-center justify-content-center fw-bold fs-5" style="width: 48px; height: 48px; background: linear-gradient(135deg, #f59e0b, #ef4444);">GB</div>
                                    <div>
                                        <h5 class="text-white fw-bold mb-0">GB Tech Ventures</h5>
                                        <div class="text-info fs-7">Regional Incubator</div>
                                    </div>
                                </div>
                                <p class="text-light-slate fs-7 mb-3">Fostering high-impact technology innovation and talent across Gilgit-Baltistan.</p>
                            </div>
                            <div class="pt-3 border-top border-slate-subtle d-flex justify-content-between align-items-center">
                                <span class="badge bg-primary-subtle text-info rounded-pill"><i class="fa-solid fa-briefcase me-1"></i> 4 Openings</span>
                                <a href="{{ route('companies.index') }}" class="text-white fs-7 text-decoration-none fw-semibold">View <i class="fa-solid fa-chevron-right ms-1"></i></a>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- SECTION 5: LIVE THEME ENGINE PREVIEWER -->
    <section class="py-5">
        <div class="container py-4">
            <div class="text-center mb-5">
                <span class="badge bg-primary-subtle text-info border border-info-subtle rounded-pill px-3 py-2 mb-2">Multi-Theme Engine</span>
                <h2 class="display-6 text-white fw-bold mb-3">Switch Portfolio Themes in One Click</h2>
                <p class="text-muted-slate mx-auto fs-6" style="max-width: 650px;">
                    Experience how your professional identity dynamically adapts to distinct design aesthetic engines (`Premium Dark Glass`, `Classic Light`, `Elegant Indigo`).
                </p>
            </div>

            <!-- Theme Picker Buttons -->
            <div class="d-flex justify-content-center gap-3 mb-4">
                <button class="btn btn-outline-glass px-4 py-2 rounded-pill active" id="theme-btn-premium" onclick="previewTheme('premium')">
                    <i class="fa-solid fa-moon text-info me-2"></i> Premium Dark Glass (Default)
                </button>
                <button class="btn btn-outline-glass px-4 py-2 rounded-pill" id="theme-btn-classic" onclick="previewTheme('classic')">
                    <i class="fa-solid fa-sun text-warning me-2"></i> Classic Light (Clean)
                </button>
                <button class="btn btn-outline-glass px-4 py-2 rounded-pill" id="theme-btn-elegant" onclick="previewTheme('elegant')">
                    <i class="fa-solid fa-palette text-purple me-2" style="color: #a78bfa !important;"></i> Elegant Indigo (Serif)
                </button>
            </div>

            <!-- Live Theme Render Box -->
            <div id="theme-preview-card" class="glass-panel p-4 p-md-5 transition-all">
                <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom border-slate-subtle">
                    <div class="d-flex align-items-center gap-3">
                        <img src="{{ asset('images/amina_khan_avatar.jpg') }}" alt="Amina Khan" id="preview-avatar" class="rounded-circle border border-info shadow-sm p-1" style="width: 56px; height: 56px; object-fit: cover;">
                        <div>
                            <h3 class="mb-0 text-white" id="preview-name">Amina Khan</h3>
                            <div class="text-info fs-7" id="preview-title">Senior Cyber Security Analyst & Cloud Auditor</div>
                        </div>
                    </div>
                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-2" id="preview-badge">Verified Credentials</span>
                </div>

                <p class="fs-6 mb-4" id="preview-bio">
                    Specialized in enterprise zero-trust security architectures, vulnerability mitigation, and automated compliance auditing across distributed cloud infrastructure.
                </p>

                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="p-3 rounded-3" id="preview-box-1" style="background: rgba(15, 23, 42, 0.7); border: 1px solid rgba(255, 255, 255, 0.08);">
                            <div class="fw-bold text-white mb-1"><i class="fa-solid fa-shield-halved text-info me-2"></i> Certifications</div>
                            <div class="text-muted-slate fs-7">CISSP, AWS Security Specialty, CEH</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 rounded-3" id="preview-box-2" style="background: rgba(15, 23, 42, 0.7); border: 1px solid rgba(255, 255, 255, 0.08);">
                            <div class="fw-bold text-white mb-1"><i class="fa-solid fa-laptop-code text-success me-2"></i> Key Projects</div>
                            <div class="text-muted-slate fs-7">12 Cloud Audits, 4 Media Publications</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 rounded-3" id="preview-box-3" style="background: rgba(15, 23, 42, 0.7); border: 1px solid rgba(255, 255, 255, 0.08);">
                            <div class="fw-bold text-white mb-1"><i class="fa-solid fa-globe text-warning me-2"></i> Custom URL</div>
                            <div class="text-muted-slate fs-7">myresume.cloud/amina-khan</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 6: DYNAMIC PORTFOLIO SHOWCASE (CMS PREVIEW) -->
    <section class="py-5" style="background: rgba(15, 23, 42, 0.5);">
        <div class="container py-4">
            <div class="text-center mb-5">
                <span class="badge bg-primary-subtle text-info border border-info-subtle rounded-pill px-3 py-2 mb-2">Interactive CMS Modules</span>
                <h2 class="display-6 text-white fw-bold mb-3">More Than a Resume. A Complete Digital Identity.</h2>
                <p class="text-muted-slate mx-auto fs-6" style="max-width: 650px;">
                    Switch between portfolio sections to preview how your skills, project media, experience timeline, and achievements are rendered in high-definition themes.
                </p>
            </div>

            <!-- Tabbed Selector -->
            <div class="d-flex flex-wrap justify-content-center gap-2 mb-4">
                <button class="tab-btn-custom active" onclick="switchTab('experience')"><i class="fa-solid fa-briefcase me-2"></i> Experience</button>
                <button class="tab-btn-custom" onclick="switchTab('skills')"><i class="fa-solid fa-code me-2"></i> Skills</button>
                <button class="tab-btn-custom" onclick="switchTab('projects')"><i class="fa-solid fa-laptop-code me-2"></i> Projects</button>
                <button class="tab-btn-custom" onclick="switchTab('certifications')"><i class="fa-solid fa-certificate me-2"></i> Certifications</button>
                <button class="tab-btn-custom" onclick="switchTab('education')"><i class="fa-solid fa-graduation-cap me-2"></i> Education</button>
                <button class="tab-btn-custom" onclick="switchTab('achievements')"><i class="fa-solid fa-trophy me-2"></i> Achievements</button>
            </div>

            <!-- Dynamic Tab Content Render Card -->
            <div class="glass-panel p-4 p-md-5 position-relative overflow-hidden" style="min-height: 380px;">
                <div class="row align-items-center">
                    <div class="col-lg-5 mb-4 mb-lg-0">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <img src="{{ asset('images/john_doe_avatar.jpg') }}" alt="John Doe" class="rounded-circle border border-info shadow-sm p-1" style="width: 54px; height: 54px; object-fit: cover;">
                            <div>
                                <h4 class="text-white mb-0">John Doe</h4>
                                <div class="text-info fs-7">Senior UI/UX Designer & Product Architect</div>
                            </div>
                        </div>

                        <div id="tab-meta-title" class="fs-4 text-white fw-bold mb-2">Lead Product Designer</div>
                        <div id="tab-meta-subtitle" class="text-muted-slate mb-4">TechSphere Solutions • 2022 - Present</div>

                        <p id="tab-description" class="text-light-slate fs-6">
                            Architected cross-platform design systems for enterprise SaaS applications, reducing design-to-engineering handoff latency by 45% across 12 product teams.
                        </p>

                        <div class="d-flex align-items-center gap-3 pt-3 border-top border-slate-subtle">
                            <a href="{{ route('register') }}" class="btn btn-gradient-primary rounded-pill px-4 btn-sm">
                                Create Similar Section
                            </a>
                            <span class="text-muted-slate fs-7"><i class="fa-solid fa-shield-halved text-success me-1"></i> Public & Export Ready</span>
                        </div>
                    </div>

                    <div class="col-lg-7">
                        <div class="p-4 rounded-3" style="background: rgba(15, 23, 42, 0.85); border: 1px solid rgba(255, 255, 255, 0.08);">
                            <div id="tab-preview-body">
                                <!-- Dynamic JS tab preview content injected here -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 7: INTELLIGENT MATCH ENGINE & LIVE OPPORTUNITIES HUB -->
    <section class="py-5">
        <div class="container py-4">
            <div class="row align-items-end mb-4">
                <div class="col-lg-7">
                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-2 mb-2">Algorithmic Job Match</span>
                    <h2 class="display-6 text-white fw-bold mb-2">Live Opportunities & Skill Matching</h2>
                    <p class="text-muted-slate mb-0">
                        Our intelligent engine matches your portfolio competencies directly against active employer requirements.
                    </p>
                </div>
                <div class="col-lg-5 text-lg-end mt-3 mt-lg-0">
                    <div class="d-inline-flex gap-2">
                        <button class="filter-btn active" onclick="filterJobs('all')">All</button>
                        <button class="filter-btn" onclick="filterJobs('full-time')">Full Time</button>
                        <button class="filter-btn" onclick="filterJobs('contract')">Contract</button>
                        <button class="filter-btn" onclick="filterJobs('internship')">Internship</button>
                    </div>
                </div>
            </div>

            <div class="row g-4 mb-5">
                @forelse($recentJobs as $job)
                    <div class="col-md-6 col-lg-4 job-item" data-type="{{ strtolower($job->employment_type ?? 'full-time') }}">
                        <div class="glass-panel p-4 h-100 glass-panel-interactive d-flex flex-column justify-content-between">
                            <div>
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <span class="badge bg-emerald-dark text-white rounded-pill px-3 py-1" style="background: var(--emerald-dark) !important;">
                                        <i class="fa-solid fa-circle-check me-1"></i> {{ rand(84, 98) }}% Match
                                    </span>
                                    <span class="text-muted-slate fs-7"><i class="fa-regular fa-clock me-1"></i> {{ $job->created_at ? $job->created_at->diffForHumans() : 'Recent' }}</span>
                                </div>

                                <h5 class="text-white fw-bold mb-2">{{ $job->title }}</h5>
                                <div class="text-info fs-7 mb-3">
                                    <i class="fa-solid fa-building me-1"></i> {{ $job->company->name ?? 'Enterprise Org' }}
                                </div>

                                <div class="d-flex flex-wrap gap-2 mb-3 fs-7 text-muted-slate">
                                    <span class="badge bg-dark border border-slate-subtle"><i class="fa-solid fa-location-dot me-1 text-danger"></i> {{ $job->city ?? 'Remote' }}</span>
                                    <span class="badge bg-dark border border-slate-subtle"><i class="fa-solid fa-briefcase me-1 text-warning"></i> {{ ucfirst($job->employment_type ?? 'Full-time') }}</span>
                                    <span class="badge bg-dark border border-slate-subtle"><i class="fa-solid fa-dollar-sign me-1 text-success"></i> {{ $job->salary_min ? '$' . number_format($job->salary_min) . '-' . number_format($job->salary_max) : 'Competitive' }}</span>
                                </div>

                                <p class="text-light-slate fs-7 line-clamp-2 mb-3">
                                    {{ Str::limit(strip_tags(html_entity_decode($job->description)), 110) }}
                                </p>
                            </div>

                            <a href="{{ route('opportunities.show', $job->slug ?? $job->id) }}" class="btn btn-outline-glass btn-sm w-100 rounded-pill mt-2">
                                View Position <i class="fa-solid fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                @empty
                    <!-- Fallback Sample Cards -->
                    <div class="col-md-6 col-lg-4 job-item" data-type="full-time">
                        <div class="glass-panel p-4 h-100 glass-panel-interactive d-flex flex-column justify-content-between">
                            <div>
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <span class="badge bg-emerald-dark text-white rounded-pill px-3 py-1" style="background: var(--emerald-dark) !important;">
                                        <i class="fa-solid fa-circle-check me-1"></i> 96% Match
                                    </span>
                                    <span class="text-muted-slate fs-7">Active</span>
                                </div>
                                <h5 class="text-white fw-bold mb-2">Senior Full Stack Engineer</h5>
                                <div class="text-info fs-7 mb-3"><i class="fa-solid fa-building me-1"></i> CloudScale Systems</div>
                                <div class="d-flex flex-wrap gap-2 mb-3 fs-7 text-muted-slate">
                                    <span class="badge bg-dark border border-slate-subtle"><i class="fa-solid fa-location-dot text-danger"></i> Remote</span>
                                    <span class="badge bg-dark border border-slate-subtle"><i class="fa-solid fa-briefcase text-warning"></i> Full-time</span>
                                    <span class="badge bg-dark border border-slate-subtle">$110,000 - $140,000</span>
                                </div>
                                <p class="text-light-slate fs-7 mb-3">Seeking PHP/Laravel & React specialist to lead enterprise multi-tenant cloud application scaling.</p>
                            </div>
                            <a href="{{ route('opportunities.index') }}" class="btn btn-outline-glass btn-sm w-100 rounded-pill">View Position <i class="fa-solid fa-arrow-right ms-1"></i></a>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- SECTION 8: AI MOCK INTERVIEW SIMULATOR SHOWCASE -->
    <section class="py-5 position-relative" style="background: rgba(15, 23, 42, 0.4);">
        <div class="container py-4">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-5 mb-lg-0">
                    <span class="badge bg-purple text-white rounded-pill px-3 py-2 mb-3" style="background: var(--gradient-ai) !important;">
                        <i class="fa-solid fa-robot me-1"></i> AI Interview Simulator
                    </span>

                    <h2 class="display-6 text-white fw-bold mb-3">Practice Real Interview Scenarios. Get Instant Diagnostic Feedback.</h2>
                    <p class="text-muted-slate fs-6 mb-4">
                        Prepare for upcoming interviews with custom AI mock interview sessions tailored specifically to target job titles and skills. Get scored on technical accuracy, communication clarity, and readiness.
                    </p>

                    <div class="d-flex flex-column gap-3 mb-4">
                        <div class="d-flex align-items-center gap-3">
                            <span class="badge bg-dark border border-info text-info rounded-circle p-2" style="width: 32px; height: 32px;">01</span>
                            <span class="text-white fw-semibold">Choose Target Job Title & Skills</span>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <span class="badge bg-dark border border-info text-info rounded-circle p-2" style="width: 32px; height: 32px;">02</span>
                            <span class="text-white fw-semibold">Start Interactive AI Voice/Text Session</span>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <span class="badge bg-dark border border-info text-info rounded-circle p-2" style="width: 32px; height: 32px;">03</span>
                            <span class="text-white fw-semibold">Answer Category Questions (Technical, Behavioral)</span>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <span class="badge bg-dark border border-info text-info rounded-circle p-2" style="width: 32px; height: 32px;">04</span>
                            <span class="text-white fw-semibold">Receive Detailed Scorecard & Sample Answers</span>
                        </div>
                    </div>

                    <a href="{{ route('mock-interviews.index') }}" class="btn btn-gradient-primary btn-lg rounded-pill px-4">
                        <i class="fa-solid fa-play me-2"></i> Try a Mock Interview
                    </a>
                </div>

                <div class="col-lg-6">
                    <div class="glass-panel p-4 p-md-5 border-indigo-subtle">
                        <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom border-slate-subtle">
                            <div>
                                <h4 class="text-white mb-0 fw-bold">Diagnostic Report Card</h4>
                                <div class="text-muted-slate fs-7">Session ID: #MOCK-2026-882</div>
                            </div>
                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-2 fs-7">
                                Readiness: High
                            </span>
                        </div>

                        <div class="row text-center mb-4">
                            <div class="col-4">
                                <div class="p-3 rounded-3" style="background: rgba(15, 23, 42, 0.8);">
                                    <div class="display-6 fw-bold text-info">82%</div>
                                    <div class="text-muted-slate fs-7">Overall Score</div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-3 rounded-3" style="background: rgba(15, 23, 42, 0.8);">
                                    <div class="display-6 fw-bold text-success">88%</div>
                                    <div class="text-muted-slate fs-7">Technical Depth</div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-3 rounded-3" style="background: rgba(15, 23, 42, 0.8);">
                                    <div class="display-6 fw-bold text-warning">79%</div>
                                    <div class="text-muted-slate fs-7">Communication</div>
                                </div>
                            </div>
                        </div>

                        <div class="p-3 rounded-3 mb-4" style="background: rgba(15, 23, 42, 0.7); border: 1px solid rgba(255, 255, 255, 0.05);">
                            <h6 class="text-white fw-bold mb-2"><i class="fa-solid fa-lightbulb text-warning me-2"></i> Actionable Feedback Highlights</h6>
                            <ul class="text-light-slate fs-7 mb-0 ps-3">
                                <li class="mb-1">Strong explanation of REST API stateless authentication & Sanctum tokens.</li>
                                <li class="mb-1">Consider expanding on database indexing strategies for multi-tenant query optimizations.</li>
                                <li>Behavioral responses demonstrated solid leadership and conflict resolution metrics.</li>
                            </ul>
                        </div>

                        <div class="d-flex justify-content-between align-items-center fs-7 text-muted-slate">
                            <span>Target Role: Full Stack Laravel Developer</span>
                            <a href="{{ route('mock-interviews.index') }}" class="text-info fw-semibold text-decoration-none">Start New Session →</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 9: ENTERPRISE TRUST, SECURITY & VERIFICATION STANDARDS BANNER -->
    <section class="py-5 position-relative">
        <div class="container py-3">
            <div class="text-center mb-5">
                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-2 mb-2">Enterprise Standard</span>
                <h2 class="display-6 text-white fw-bold mb-3">Built for High Trust, Security & Compliance</h2>
                <p class="text-muted-slate mx-auto fs-6" style="max-width: 650px;">
                    Engineered to meet rigorous corporate privacy standards, automated ATS parser formatting, and verified credential protection.
                </p>
            </div>

            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="glass-panel p-4 h-100 text-center">
                        <div class="rounded-circle mx-auto mb-3 text-white d-flex align-items-center justify-content-center" style="width: 54px; height: 54px; background: rgba(16, 185, 129, 0.2); border: 1px solid rgba(16, 185, 129, 0.3);">
                            <i class="fa-solid fa-circle-check text-success fs-4"></i>
                        </div>
                        <h5 class="text-white fw-bold mb-2">Verified Audit Checks</h5>
                        <p class="text-muted-slate fs-7 mb-0">Media publications, degrees, and corporate roles undergo verified audit checks before badging.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="glass-panel p-4 h-100 text-center">
                        <div class="rounded-circle mx-auto mb-3 text-white d-flex align-items-center justify-content-center" style="width: 54px; height: 54px; background: rgba(37, 99, 235, 0.2); border: 1px solid rgba(56, 189, 248, 0.3);">
                            <i class="fa-solid fa-file-pdf text-info fs-4"></i>
                        </div>
                        <h5 class="text-white fw-bold mb-2">100% ATS Compliance</h5>
                        <p class="text-muted-slate fs-7 mb-0">1-click PDF & Word (DOCX) resume exports pass automated applicant tracking systems cleanly.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="glass-panel p-4 h-100 text-center">
                        <div class="rounded-circle mx-auto mb-3 text-white d-flex align-items-center justify-content-center" style="width: 54px; height: 54px; background: rgba(245, 158, 11, 0.2); border: 1px solid rgba(245, 158, 11, 0.3);">
                            <i class="fa-solid fa-user-lock text-warning fs-4"></i>
                        </div>
                        <h5 class="text-white fw-bold mb-2">Granular Privacy Control</h5>
                        <p class="text-muted-slate fs-7 mb-0">Gated private portfolios restricted strictly to owner-approved peer connections or admins.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="glass-panel p-4 h-100 text-center">
                        <div class="rounded-circle mx-auto mb-3 text-white d-flex align-items-center justify-content-center" style="width: 54px; height: 54px; background: rgba(99, 102, 241, 0.2); border: 1px solid rgba(139, 92, 246, 0.3);">
                            <i class="fa-solid fa-shield-halved text-purple fs-4" style="color: #a78bfa !important;"></i>
                        </div>
                        <h5 class="text-white fw-bold mb-2">SSL & Encrypted</h5>
                        <p class="text-muted-slate fs-7 mb-0">All user-to-user messages, candidate notes, and company data are encrypted end-to-end.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 10: TALENT DISCOVERY GRID (FOR EMPLOYERS) -->
    <section class="py-5" style="background: rgba(15, 23, 42, 0.4);">
        <div class="container py-4">
            <div class="row align-items-end mb-4">
                <div class="col-lg-8">
                    <span class="badge bg-primary-subtle text-info border border-info-subtle rounded-pill px-3 py-2 mb-2">Verified Talent Directory</span>
                    <h2 class="display-6 text-white fw-bold mb-2">Top Verified Professionals Available Now</h2>
                    <p class="text-muted-slate mb-0">
                        Browse verified portfolios, credentials, and work samples from active software architects, designers, researchers, and engineers.
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                    <a href="{{ route('talent.index') }}" class="btn btn-outline-glass rounded-pill px-4">
                        View All Talent <i class="fa-solid fa-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>

            <div class="row g-4">
                @forelse($featuredCandidates as $candidate)
                    <div class="col-md-6 col-lg-4">
                        <div class="glass-panel p-4 h-100 glass-panel-interactive d-flex flex-column justify-content-between">
                            <div>
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div class="d-flex align-items-center gap-3">
                                        @if($candidate->portfolio && $candidate->portfolio->profile_image)
                                            <img src="{{ asset('storage/' . $candidate->portfolio->profile_image) }}" alt="{{ $candidate->name }}" class="rounded-circle border border-info shadow-sm p-1" style="width: 50px; height: 50px; object-fit: cover;">
                                        @else
                                            <div class="rounded-circle text-white d-flex align-items-center justify-content-center fw-bold fs-5" style="width: 50px; height: 50px; background: var(--gradient-primary);">
                                                {{ strtoupper(substr($candidate->name, 0, 2)) }}
                                            </div>
                                        @endif
                                        <div>
                                            <h5 class="text-white fw-bold mb-0 d-flex align-items-center gap-2">
                                                {{ $candidate->name }}
                                                <i class="fa-solid fa-circle-check text-info fs-7" title="Verified Professional"></i>
                                            </h5>
                                            <div class="text-muted-slate fs-7">{{ $candidate->portfolio->position ?? 'Professional' }}</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-secondary fs-7 mb-3">
                                    <i class="fa-solid fa-location-dot me-1 text-danger opacity-75"></i> {{ $candidate->portfolio->city ?? 'Gilgit-Baltistan' }}, {{ $candidate->portfolio->country ?? 'Pakistan' }}
                                </div>

                                <p class="text-light-slate fs-7 line-clamp-2 mb-3">
                                    {{ Str::limit($candidate->portfolio->description ?? 'Experienced professional presenting verified portfolio credentials and achievements.', 95) }}
                                </p>
                            </div>

                            <div class="pt-3 border-top border-slate-subtle d-flex align-items-center justify-content-between">
                                <span class="badge bg-emerald-dark text-white rounded-pill px-2 py-1" style="background: var(--emerald-dark) !important; font-size: 0.7rem;">Verified Profile</span>
                                <a href="{{ route('portfolio.show', $candidate->username) }}" class="btn btn-outline-glass btn-sm rounded-pill px-3">
                                    View Portfolio <i class="fa-solid fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <!-- Sample Candidates if DB Has Few -->
                    <div class="col-md-6 col-lg-4">
                        <div class="glass-panel p-4 h-100 glass-panel-interactive d-flex flex-column justify-content-between">
                            <div>
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded-circle text-white d-flex align-items-center justify-content-center fw-bold fs-5" style="width: 48px; height: 48px; background: var(--gradient-primary);">AS</div>
                                        <div>
                                            <h5 class="text-white fw-bold mb-0">Ali Shah <i class="fa-solid fa-circle-check text-info fs-7"></i></h5>
                                            <div class="text-muted-slate fs-7">Senior Software Architect</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-secondary fs-7 mb-3"><i class="fa-solid fa-location-dot me-1 text-danger opacity-75"></i> Gilgit, Pakistan</div>
                                <p class="text-light-slate fs-7 mb-3">Specializing in cloud infrastructure, microservices architecture, and scalable Laravel backends.</p>
                            </div>
                            <div class="pt-3 border-top border-slate-subtle d-flex align-items-center justify-content-between">
                                <span class="badge bg-emerald-dark text-white rounded-pill px-2 py-1" style="background: var(--emerald-dark) !important; font-size: 0.7rem;">Verified Profile</span>
                                <a href="{{ route('talent.index') }}" class="btn btn-outline-glass btn-sm rounded-pill px-3">View Portfolio <i class="fa-solid fa-arrow-right ms-1"></i></a>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- SECTION 11: INTERACTIVE FAQ ACCORDION -->
    <section class="py-5">
        <div class="container py-4">
            <div class="text-center mb-5">
                <span class="badge bg-primary-subtle text-info border border-info-subtle rounded-pill px-3 py-2 mb-2">Knowledge Base</span>
                <h2 class="display-6 text-white fw-bold mb-3">Frequently Asked Questions</h2>
                <p class="text-muted-slate mx-auto fs-6" style="max-width: 600px;">Everything you need to know about building your portfolio, AI interview practice, and hiring verified talent.</p>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-9">
                    <div class="accordion accordion-dark" id="faqAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                    <i class="fa-solid fa-file-export text-info me-3"></i> How does 1-Click CV export to PDF and Word (DOCX) work?
                                </button>
                            </h2>
                            <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Your portfolio automatically syncs with our document export engine. Whenever you add projects, skills, or certifications in the editor, clicking <strong>Download PDF</strong> or <strong>Download Word</strong> compiles an ATS-compliant, single/double-page resume formatted according to enterprise recruiter standards.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                    <i class="fa-solid fa-shield-halved text-success me-3"></i> What makes a portfolio profile "Verified"?
                                </button>
                            </h2>
                            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Verified badges are issued when candidates confirm their email credentials, connect verified media publications/reports, or receive endorsed peer connection confirmations. This ensures recruiters interact with legitimate professionals.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                    <i class="fa-solid fa-robot text-purple me-3" style="color: #a78bfa !important;"></i> How does the AI Mock Interview Engine score my responses?
                                </button>
                            </h2>
                            <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Our AI simulator generates role-tailored technical, behavioral, and situational questions based on the target job title. Your answers are evaluated on technical depth, key concept coverage, and communication clarity, returning a detailed score report with sample improved responses.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                                    <i class="fa-solid fa-building-user text-warning me-3"></i> How can employers post opportunities and evaluate candidates?
                                </button>
                            </h2>
                            <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Companies create verified corporate accounts to post jobs, internships, or contract roles. Recruiters access an in-app Applicant Tracking System (ATS) to view applicant portfolios, algorithmic candidate match scores, internal candidate notes, and send direct messages.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 12: STEP-BY-STEP WORKFLOW & HIGH-IMPACT BOTTOM CTA -->
    <section class="py-5 position-relative">
        <div class="container py-4">
            <div class="text-center mb-5">
                <span class="badge bg-primary-subtle text-info border border-info-subtle rounded-pill px-3 py-2 mb-2">Simple Workflow</span>
                <h2 class="display-6 text-white fw-bold mb-3">How MyResume.cloud Powers Your Career Path</h2>
            </div>

            <!-- 4-Step Timeline Grid -->
            <div class="row g-4 mb-5 position-relative">
                <div class="col-md-6 col-lg-3">
                    <div class="glass-panel p-4 text-center h-100">
                        <div class="timeline-step-num mx-auto mb-3">01</div>
                        <h5 class="text-white fw-bold mb-2">Build Profile</h5>
                        <p class="text-muted-slate fs-7 mb-0">Input skills, experience, project media, and media publications into your portfolio.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="glass-panel p-4 text-center h-100">
                        <div class="timeline-step-num mx-auto mb-3">02</div>
                        <h5 class="text-white fw-bold mb-2">Connect Network</h5>
                        <p class="text-muted-slate fs-7 mb-0">Send connection requests to verified peers, recruiters, and companies.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="glass-panel p-4 text-center h-100">
                        <div class="timeline-step-num mx-auto mb-3">03</div>
                        <h5 class="text-white fw-bold mb-2">Discover Jobs</h5>
                        <p class="text-muted-slate fs-7 mb-0">Get matched with full-time, contract, and internship opportunities algorithmically.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="glass-panel p-4 text-center h-100">
                        <div class="timeline-step-num mx-auto mb-3">04</div>
                        <h5 class="text-white fw-bold mb-2">Prepare & Apply</h5>
                        <p class="text-muted-slate fs-7 mb-0">Practice with AI mock interviews, refine answers, and submit ATS applications.</p>
                    </div>
                </div>
            </div>

            <!-- High-Impact Bottom Banner -->
            <div class="glass-panel p-5 text-center position-relative overflow-hidden" style="background: linear-gradient(135deg, rgba(37, 99, 235, 0.25) 0%, rgba(99, 102, 241, 0.2) 100%); border: 1px solid rgba(56, 189, 248, 0.3);">
                <h2 class="display-5 text-white fw-bold mb-3">Your Professional Future Starts Here.</h2>
                <p class="text-light-slate mx-auto fs-5 mb-4" style="max-width: 600px;">
                    Join thousands of professionals and top organizations building verified careers on MyResume.cloud.
                </p>
                <div class="d-flex flex-wrap justify-content-center gap-3">
                    <a href="{{ route('register') }}" class="btn btn-gradient-primary btn-lg rounded-pill px-5">
                        <i class="fa-solid fa-rocket me-2"></i> Create Your Portfolio
                    </a>
                    <a href="{{ route('talent.index') }}" class="btn btn-outline-glass btn-lg rounded-pill px-5">
                        <i class="fa-solid fa-magnifying-glass me-2"></i> Find Talent
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 13: COMPREHENSIVE MODERN FOOTER & JOB DIGEST BOX -->
    <footer class="pt-5 pb-4 border-top border-slate-subtle" style="background: rgba(10, 15, 29, 0.95);">
        <div class="container">
            <!-- Newsletter / Job Alert Box -->
            <div class="glass-panel p-4 mb-5 border-slate-subtle">
                <div class="row align-items-center">
                    <div class="col-lg-7 mb-3 mb-lg-0">
                        <h5 class="text-white fw-bold mb-1"><i class="fa-solid fa-envelope-open-text text-info me-2"></i> Subscribe to Weekly Job Matches & Career Insights</h5>
                        <p class="text-muted-slate fs-7 mb-0">Get curated opportunities matched to your portfolio skill chips delivered straight to your inbox.</p>
                    </div>
                    <div class="col-lg-5">
                        <form action="#" method="POST" onsubmit="event.preventDefault(); alert('Subscribed successfully to MyResume.cloud Career Digest!');" class="d-flex gap-2">
                            <input type="email" class="form-control bg-dark border-slate-subtle text-white rounded-pill px-3 fs-7" placeholder="Enter your email address..." required>
                            <button type="submit" class="btn btn-gradient-primary btn-sm rounded-pill px-4 text-nowrap">Subscribe</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="row g-4 mb-5">
                <!-- Column 1: Brand -->
                <div class="col-lg-4 mb-3 mb-lg-0">
                    <a class="d-flex align-items-center gap-2 mb-3 text-decoration-none" href="{{ url('/') }}">
                        <img src="{{ asset('images/logo.jpeg') }}" alt="MyResume.cloud Logo" class="rounded-2 shadow-sm" style="height: 38px; width: auto; object-fit: cover;">
                        <span class="fs-4 fw-bold text-white tracking-tight" style="font-family: var(--font-headings);">MyResume<span class="text-info">.cloud</span></span>
                    </a>
                    <p class="text-muted-slate fs-7 mb-3" style="max-width: 320px;">
                        The unified professional portfolio builder, job discovery marketplace, and AI career preparation ecosystem.
                    </p>
                    <div class="d-flex gap-3 text-muted-slate fs-5">
                        <a href="#" class="footer-link"><i class="fa-brands fa-twitter"></i></a>
                        <a href="#" class="footer-link"><i class="fa-brands fa-linkedin"></i></a>
                        <a href="#" class="footer-link"><i class="fa-brands fa-github"></i></a>
                    </div>
                </div>

                <!-- Column 2: Platform Links -->
                <div class="col-6 col-lg-2">
                    <h6 class="text-white fw-bold mb-3">Platform</h6>
                    <ul class="list-unstyled d-flex flex-column gap-2 mb-0">
                        <li><a href="{{ route('talent.index') }}" class="footer-link">Professionals</a></li>
                        <li><a href="{{ route('opportunities.index') }}" class="footer-link">Opportunities</a></li>
                        <li><a href="{{ route('companies.index') }}" class="footer-link">Companies</a></li>
                        <li><a href="{{ route('feed.index') }}" class="footer-link">Social Network</a></li>
                    </ul>
                </div>

                <!-- Column 3: Professional Tools -->
                <div class="col-6 col-lg-3">
                    <h6 class="text-white fw-bold mb-3">Professional Tools</h6>
                    <ul class="list-unstyled d-flex flex-column gap-2 mb-0">
                        <li><a href="{{ route('register') }}" class="footer-link">Create Portfolio</a></li>
                        <li><a href="{{ route('mock-interviews.index') }}" class="footer-link">AI Mock Interviews</a></li>
                        <li><a href="{{ route('applications.candidate.index') }}" class="footer-link">Job Applications Tracker</a></li>
                        <li><a href="{{ route('preferences.edit') }}" class="footer-link">Career Preferences</a></li>
                    </ul>
                </div>

                <!-- Column 4: Companies & Legal -->
                <div class="col-lg-3">
                    <h6 class="text-white fw-bold mb-3">Employers & Legal</h6>
                    <ul class="list-unstyled d-flex flex-column gap-2 mb-0">
                        <li><a href="{{ route('companies.create') }}" class="footer-link">Register Organization</a></li>
                        <li><a href="{{ route('opportunities.create') }}" class="footer-link">Post an Opportunity</a></li>
                        <li><a href="{{ route('sitemap') }}" class="footer-link">XML Sitemap</a></li>
                        <li><a href="#" class="footer-link">Privacy & Security Terms</a></li>
                    </ul>
                </div>
            </div>

            <div class="pt-4 border-top border-slate-subtle d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 text-muted-slate fs-7">
                <div>
                    &copy; {{ date('Y') }} MyResume.cloud. All rights reserved <span class="mx-1">&bull;</span> Powered by <a href="https://itechgb.com/" target="_blank" class="text-slate-200 text-decoration-underline fw-medium">Innovative Technologies GB</a>
                </div>
                <div class="d-flex gap-3">
                    <span><i class="fa-solid fa-globe me-1"></i> Global Edition</span>
                    <span><i class="fa-solid fa-lock me-1 text-success"></i> SSL Encrypted</span>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS 5.3.3 Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Client-side Interactive Theme Switcher, Tabs & Filters -->
    <script>
        // Interactive Theme Switcher Previewer
        function previewTheme(theme) {
            const card = document.getElementById('theme-preview-card');
            const avatar = document.getElementById('preview-avatar');
            const name = document.getElementById('preview-name');
            const title = document.getElementById('preview-title');
            const badge = document.getElementById('preview-badge');

            // Reset active button state
            document.querySelectorAll('#theme-btn-premium, #theme-btn-classic, #theme-btn-elegant').forEach(btn => btn.classList.remove('active'));

            if (theme === 'classic') {
                document.getElementById('theme-btn-classic').classList.add('active');
                card.style.background = '#ffffff';
                card.style.borderColor = '#cbd5e1';
                name.style.color = '#0f172a';
                name.style.fontFamily = "'Inter', sans-serif";
                title.style.color = '#2563eb';
                badge.className = 'badge bg-primary text-white px-3 py-2';
                avatar.className = 'rounded-circle border border-primary shadow-sm p-1';
            } else if (theme === 'elegant') {
                document.getElementById('theme-btn-elegant').classList.add('active');
                card.style.background = '#0f172a';
                card.style.borderColor = '#6366f1';
                name.style.color = '#ffffff';
                name.style.fontFamily = "'Outfit', sans-serif";
                title.style.color = '#8b5cf6';
                badge.className = 'badge bg-indigo text-white px-3 py-2';
                badge.style.background = '#6366f1';
                avatar.className = 'rounded-circle border border-purple shadow-sm p-1';
            } else {
                // Premium (Default)
                document.getElementById('theme-btn-premium').classList.add('active');
                card.style.background = 'rgba(30, 41, 59, 0.65)';
                card.style.borderColor = 'rgba(255, 255, 255, 0.08)';
                name.style.color = '#ffffff';
                name.style.fontFamily = "'Outfit', sans-serif";
                title.style.color = '#38bdf8';
                badge.className = 'badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-2';
                avatar.className = 'rounded-circle border border-info shadow-sm p-1';
            }
        }

        // Data Store for Tabbed Portfolio Showcase
        const tabData = {
            experience: {
                title: "Lead Product Designer",
                subtitle: "TechSphere Solutions • 2022 - Present",
                description: "Architected cross-platform design systems for enterprise SaaS applications, reducing design-to-engineering handoff latency by 45% across 12 product teams.",
                bodyHtml: `
                    <div class="d-flex flex-column gap-3">
                        <div class="p-3 rounded bg-dark border border-slate-subtle">
                            <div class="d-flex justify-content-between text-white fw-bold mb-1">
                                <span>Senior UI/UX & Design Systems Lead</span>
                                <span class="text-info fs-7">2022 - Present</span>
                            </div>
                            <div class="text-muted-slate fs-7">TechSphere Solutions • Full-time</div>
                            <div class="text-light-slate fs-7 mt-2">Led 6 designers building high-accessibility dark mode component libraries for Web and Mobile.</div>
                        </div>
                        <div class="p-3 rounded bg-dark border border-slate-subtle opacity-75">
                            <div class="d-flex justify-content-between text-white fw-bold mb-1">
                                <span>Product Designer & Frontend Engineer</span>
                                <span class="text-info fs-7">2020 - 2022</span>
                            </div>
                            <div class="text-muted-slate fs-7">DevStudio Labs • Full-time</div>
                        </div>
                    </div>
                `
            },
            skills: {
                title: "Core Competencies & Stack",
                subtitle: "Verified Technical & Creative Proficiency",
                description: "Mastery across modern web frontend development, visual architecture, prototyping, and accessibility compliance.",
                bodyHtml: `
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="p-3 rounded bg-dark border border-slate-subtle">
                                <div class="d-flex justify-content-between text-white fs-7 mb-1"><span>Figma / UI Design</span><span class="text-info">98%</span></div>
                                <div class="custom-progress"><div class="custom-progress-bar" style="width: 98%; background: var(--gradient-primary);"></div></div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 rounded bg-dark border border-slate-subtle">
                                <div class="d-flex justify-content-between text-white fs-7 mb-1"><span>HTML5 / CSS3 / Vanilla</span><span class="text-success">95%</span></div>
                                <div class="custom-progress"><div class="custom-progress-bar" style="width: 95%; background: var(--gradient-emerald);"></div></div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 rounded bg-dark border border-slate-subtle">
                                <div class="d-flex justify-content-between text-white fs-7 mb-1"><span>Bootstrap 5 & Glassmorphism</span><span class="text-info">94%</span></div>
                                <div class="custom-progress"><div class="custom-progress-bar" style="width: 94%; background: var(--gradient-primary);"></div></div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 rounded bg-dark border border-slate-subtle">
                                <div class="d-flex justify-content-between text-white fs-7 mb-1"><span>JavaScript / Blade</span><span class="text-warning">90%</span></div>
                                <div class="custom-progress"><div class="custom-progress-bar" style="width: 90%; background: var(--gradient-ai);"></div></div>
                            </div>
                        </div>
                    </div>
                `
            },
            projects: {
                title: "Featured Case Studies & Media",
                subtitle: "Shipped Products & Portfolio Artifacts",
                description: "Live interactive applications, SaaS dashboards, and design component systems available for public inspection.",
                bodyHtml: `
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="p-3 rounded bg-dark border border-slate-subtle d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="text-white fw-bold">Enterprise Cloud Analytics Dashboard</div>
                                    <div class="text-muted-slate fs-7">Built responsive dark dashboard handling 100k daily events.</div>
                                </div>
                                <span class="badge bg-primary text-white rounded-pill">Live Demo</span>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="p-3 rounded bg-dark border border-slate-subtle d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="text-white fw-bold">Glassmorphism UI Component Suite</div>
                                    <div class="text-muted-slate fs-7">Open-source CSS theme library with 2,400+ GitHub stars.</div>
                                </div>
                                <span class="badge bg-info text-white rounded-pill">GitHub Repo</span>
                            </div>
                        </div>
                    </div>
                `
            },
            certifications: {
                title: "Professional Certifications",
                subtitle: "Verified Industry Licenses & Degrees",
                description: "Formal accreditation from cloud providers, design institutes, and technology foundations.",
                bodyHtml: `
                    <div class="d-flex flex-column gap-2">
                        <div class="p-3 rounded bg-dark border border-slate-subtle d-flex align-items-center gap-3">
                            <i class="fa-solid fa-certificate text-warning fs-4"></i>
                            <div>
                                <div class="text-white fw-bold">AWS Certified Solutions Architect</div>
                                <div class="text-muted-slate fs-7">Amazon Web Services • Issued 2024</div>
                            </div>
                        </div>
                        <div class="p-3 rounded bg-dark border border-slate-subtle d-flex align-items-center gap-3">
                            <i class="fa-solid fa-award text-info fs-4"></i>
                            <div>
                                <div class="text-white fw-bold">Google UX Design Professional Specialization</div>
                                <div class="text-muted-slate fs-7">Coursera / Google • Issued 2023</div>
                            </div>
                        </div>
                    </div>
                `
            },
            education: {
                title: "Academic Background",
                subtitle: "Higher Education Credentials",
                description: "Computer Science and Human-Computer Interaction foundational education.",
                bodyHtml: `
                    <div class="p-3 rounded bg-dark border border-slate-subtle">
                        <div class="d-flex justify-content-between text-white fw-bold mb-1">
                            <span>B.S. in Computer Science & Human Computer Interaction</span>
                            <span class="text-info fs-7">2016 - 2020</span>
                        </div>
                        <div class="text-muted-slate fs-7">Karakoram International University • 3.84 GPA</div>
                        <div class="text-light-slate fs-7 mt-2">Graduated with Honors. President of the University Technology Society.</div>
                    </div>
                `
            },
            achievements: {
                title: "Honors & Key Milestones",
                subtitle: "Recognitions & Public Awards",
                description: "Notable hackathon victories, organization awards, and open-source contributions.",
                bodyHtml: `
                    <div class="d-flex flex-column gap-2">
                        <div class="p-3 rounded bg-dark border border-slate-subtle d-flex align-items-center gap-3">
                            <i class="fa-solid fa-trophy text-warning fs-3"></i>
                            <div>
                                <div class="text-white fw-bold">1st Place — Regional SaaS Innovation Hackathon 2025</div>
                                <div class="text-muted-slate fs-7">Awarded for building accessible multi-tenant portfolio renderers.</div>
                            </div>
                        </div>
                    </div>
                `
            }
        };

        // Switch Tab Function
        function switchTab(tabKey) {
            document.querySelectorAll('.tab-btn-custom').forEach(btn => btn.classList.remove('active'));
            event.currentTarget.classList.add('active');

            const data = tabData[tabKey];
            if (data) {
                document.getElementById('tab-meta-title').innerText = data.title;
                document.getElementById('tab-meta-subtitle').innerText = data.subtitle;
                document.getElementById('tab-description').innerText = data.description;
                document.getElementById('tab-preview-body').innerHTML = data.bodyHtml;
            }
        }

        // Initialize default tab
        document.addEventListener('DOMContentLoaded', () => {
            document.getElementById('tab-preview-body').innerHTML = tabData.experience.bodyHtml;
        });

        // Filter Jobs Function
        function filterJobs(filter) {
            document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active'));
            event.currentTarget.classList.add('active');

            const items = document.querySelectorAll('.job-item');
            items.forEach(item => {
                const type = item.getAttribute('data-type');
                if (filter === 'all' || type === filter) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        }
    </script>
</body>
</html>

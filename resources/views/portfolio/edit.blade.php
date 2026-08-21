<x-app-layout>
    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet">
        <style>
            :root {
                --workspace-sidebar-width: 270px;
                --brand-primary-light: rgba(76, 117, 161, 0.08);
                --brand-primary-border: rgba(76, 117, 161, 0.25);
            }

            .career-workspace {
                min-height: calc(100vh - 65px);
                background-color: var(--brand-light);
            }

            .workspace-sidebar {
                width: var(--workspace-sidebar-width);
                min-width: var(--workspace-sidebar-width);
                background: #ffffff;
                border-right: 1px solid var(--border-color);
            }

            .sidebar-group-title {
                font-size: 0.6875rem;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 0.08em;
                color: #94a3b8;
                padding: 0.75rem 1rem 0.35rem 1rem;
            }

            .sidebar-menu .nav-link {
                color: #475569;
                font-size: 0.84rem;
                font-weight: 500;
                padding: 0.55rem 0.9rem;
                border-radius: 8px;
                margin-bottom: 2px;
                transition: all 0.2s ease;
                display: flex;
                align-items: center;
                gap: 0.65rem;
                min-width: 0;
                max-width: 100%;
                overflow: hidden;
            }

            .sidebar-menu .nav-link span {
                min-width: 0;
                max-width: 100%;
                word-break: break-word;
                overflow-wrap: anywhere;
            }

            .sidebar-menu .nav-link:hover {
                color: var(--brand-primary);
                background-color: var(--brand-tint);
            }

            .sidebar-menu .nav-link.active {
                color: var(--brand-primary);
                background-color: var(--brand-primary-light);
                font-weight: 600;
                border-left: 3px solid var(--brand-primary);
            }

            .sidebar-menu .nav-link i {
                font-size: 0.95rem;
                width: 20px;
                text-align: center;
            }

            .workspace-header-card {
                background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
                color: #ffffff;
                border-radius: 12px;
                position: relative;
                overflow: hidden;
            }

            .profile-identity-card {
                background: #ffffff;
                border: 1px solid var(--border-color);
                border-radius: 12px;
            }

            .completion-bar-outer {
                height: 8px;
                background-color: #e2e8f0;
                border-radius: 999px;
                overflow: hidden;
            }

            .completion-bar-inner {
                height: 100%;
                background: linear-gradient(90deg, #4c75a1 0%, #10b981 100%);
                border-radius: 999px;
                transition: width 0.5s ease;
            }

            .metric-stat-card {
                background: #ffffff;
                border: 1px solid var(--border-color);
                border-radius: 10px;
                padding: 1rem;
                transition: transform 0.2s ease, box-shadow 0.2s ease;
            }

            .metric-stat-card:hover {
                transform: translateY(-2px);
                box-shadow: var(--shadow-md);
            }

            .metric-number {
                font-family: var(--font-headings);
                font-size: 1.5rem;
                font-weight: 700;
                color: var(--brand-secondary);
                line-height: 1.1;
            }

            .job-match-card {
                background: #ffffff;
                border: 1px solid var(--border-color);
                border-radius: 10px;
                transition: all 0.2s ease;
                overflow: hidden;
                position: relative;
                min-width: 0;
            }

            .job-match-card:hover {
                border-color: var(--brand-primary);
                box-shadow: 0 4px 14px rgba(76, 117, 161, 0.12);
                transform: translateY(-2px);
            }

            .match-badge {
                background-color: rgba(16, 185, 129, 0.12);
                color: #047857;
                font-weight: 700;
                font-size: 0.68rem;
                padding: 0.2rem 0.45rem;
                border-radius: 999px;
                border: 1px solid rgba(16, 185, 129, 0.25);
                white-space: nowrap;
                display: inline-flex;
                align-items: center;
                line-height: 1;
                flex-shrink: 0;
                max-width: 100%;
            }

            .theme-card {
                background: #ffffff;
                border: 1px solid var(--border-color);
                border-radius: 12px;
                transition: all 0.2s ease;
            }

            .theme-card.active-theme {
                border-color: var(--brand-primary);
                box-shadow: 0 0 0 2px var(--brand-primary);
            }

            .dropdown-menu.show {
                display: block !important;
                z-index: 1060 !important;
            }

            /* Comprehensive Mobile & Responsive Media Queries */
            .pipeline-wrapper {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            .pipeline-container {
                min-width: 520px;
            }

            @media (max-width: 1199.98px) {
                .workspace-header-card {
                    padding: 1.5rem !important;
                }
                .metric-number {
                    font-size: 1.35rem;
                }
            }

            @media (max-width: 991.98px) {
                .workspace-sidebar {
                    width: 100%;
                    min-width: 100%;
                    border-right: none;
                }
                .career-workspace {
                    flex-direction: column !important;
                }
                .job-match-card {
                    margin-bottom: 0.5rem;
                }
            }

            @media (max-width: 767.98px) {
                .workspace-header-card {
                    padding: 1.25rem !important;
                }
                .workspace-header-card .d-flex {
                    flex-direction: column !important;
                    align-items: stretch !important;
                }
                .workspace-header-card .btn {
                    width: 100%;
                }
                .profile-identity-card {
                    padding: 1.25rem !important;
                }
                .metric-stat-card {
                    padding: 0.85rem !important;
                }
                .metric-number {
                    font-size: 1.2rem;
                }
                .table-responsive {
                    border-radius: 8px;
                    border: 1px solid var(--border-color);
                }
                .modal-dialog {
                    margin: 0.5rem;
                }
            }

            @media (max-width: 575.98px) {
                .btn-sm {
                    padding: 0.35rem 0.65rem;
                    font-size: 0.72rem;
                }
                .badge {
                    font-size: 0.65rem;
                }
            }
        </style>
    @endpush

    <!-- App Shell Container -->
    <div class="career-workspace d-flex flex-column flex-lg-row">
        <!-- Sidebar Navigation -->
        <div class="workspace-sidebar p-3 d-none d-lg-block">
            <div class="d-flex align-items-center gap-2 mb-4 pb-3 border-bottom overflow-hidden">
                @if($portfolio->profile_image)
                    <img src="{{ Storage::url($portfolio->profile_image) }}" class="rounded-circle border flex-shrink-0" style="width: 42px; height: 42px; object-fit: cover;">
                @else
                    <div class="rounded-circle text-white d-flex align-items-center justify-content-center fw-bold flex-shrink-0" style="width: 42px; height: 42px; background-color: var(--brand-primary); font-size: 1rem;">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                @endif
                <div class="flex-grow-1 overflow-hidden" style="min-width: 0;">
                    <h6 class="fw-bold text-dark mb-0 text-truncate" style="font-size: 0.9rem;" title="{{ Auth::user()->name }}">{{ Auth::user()->name }}</h6>
                    <small class="text-muted d-block text-truncate" style="font-size: 0.72rem;" title="{{ $portfolio->position ?? 'Professional User' }}">{{ $portfolio->position ?? 'Professional User' }}</small>
                </div>
            </div>

            <!-- Sidebar Navigation Tabs -->
            <div class="nav flex-column sidebar-menu" id="careerWorkspaceTabs" role="tablist">
                <div class="sidebar-group-title">Main</div>
                <button class="nav-link active text-start border-0 bg-transparent" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overviewPane" type="button" role="tab">
                    <i class="fa-solid fa-gauge-high"></i> <span>Career Overview</span>
                </button>

                <!-- 1. PORTFOLIO -->
                <div class="sidebar-group-title mt-2">Portfolio</div>
                <button class="nav-link text-start border-0 bg-transparent" id="cms-tab" data-bs-toggle="tab" data-bs-target="#cmsPane" type="button" role="tab">
                    <i class="fa-solid fa-id-card"></i> <span>My Portfolio CMS</span>
                </button>
                <button class="nav-link text-start border-0 bg-transparent" id="settings-tab" data-bs-toggle="tab" data-bs-target="#settingsPane" type="button" role="tab">
                    <i class="fa-solid fa-sliders"></i> <span>Portfolio Settings</span>
                </button>
                <button class="nav-link text-start border-0 bg-transparent" id="themes-tab" data-bs-toggle="tab" data-bs-target="#themesPane" type="button" role="tab">
                    <i class="fa-solid fa-palette"></i> <span>Portfolio Themes</span>
                </button>
                <button class="nav-link text-start border-0 bg-transparent" id="network-tab" data-bs-toggle="tab" data-bs-target="#networkPane" type="button" role="tab">
                    <i class="fa-solid fa-users"></i> <span>Connections</span>
                    @if($pendingReceived->count() > 0)
                        <span class="badge bg-warning text-dark rounded-pill ms-auto" style="font-size: 0.65rem;">{{ $pendingReceived->count() }}</span>
                    @endif
                </button>

                <!-- 2. COMPANY & ORGANIZATION -->
                <div class="sidebar-group-title mt-2">Company & Organization</div>
                <a class="nav-link text-start border-0 bg-transparent text-decoration-none" href="{{ route('companies.create') }}">
                    <i class="fa-solid fa-building-user text-primary flex-shrink-0"></i> <span>Create Company Profile</span>
                </a>
                @if(Auth::user()->companies->isNotEmpty())
                    @foreach(Auth::user()->companies as $myCompany)
                        <a class="nav-link text-start border-0 bg-transparent text-decoration-none py-2" href="{{ route('companies.dashboard', $myCompany->id) }}" title="{{ $myCompany->name }} Dashboard" style="min-width: 0; max-width: 100%;">
                            <i class="fa-solid fa-building text-success flex-shrink-0 mt-1"></i>
                            <span class="flex-grow-1 min-w-0" style="word-break: break-word; overflow-wrap: anywhere; white-space: normal; line-height: 1.35; font-size: 0.82rem;">{{ Str::limit($myCompany->name, 28) }}</span>
                        </a>
                    @endforeach
                @endif

                <!-- 3. CAREER -->
                <div class="sidebar-group-title mt-2">Career</div>
                <button class="nav-link text-start border-0 bg-transparent" id="jobs-tab" data-bs-toggle="tab" data-bs-target="#jobsPane" type="button" role="tab">
                    <i class="fa-solid fa-briefcase"></i> <span>Jobs & Opportunities</span>
                </button>
                <button class="nav-link text-start border-0 bg-transparent" id="applications-tab" data-bs-toggle="tab" data-bs-target="#applicationsPane" type="button" role="tab">
                    <i class="fa-solid fa-paper-plane"></i> <span>My Applications</span>
                    @if($myApplications->count() > 0)
                        <span class="badge bg-primary rounded-pill ms-auto" style="font-size: 0.65rem;">{{ $myApplications->count() }}</span>
                    @endif
                </button>
                <button class="nav-link text-start border-0 bg-transparent" id="saved-tab" data-bs-toggle="tab" data-bs-target="#savedPane" type="button" role="tab">
                    <i class="fa-solid fa-bookmark"></i> <span>Saved Jobs</span>
                    @if($savedOpportunities->count() > 0)
                        <span class="badge bg-secondary rounded-pill ms-auto" style="font-size: 0.65rem;">{{ $savedOpportunities->count() }}</span>
                    @endif
                </button>
                @if(\App\Models\SystemSetting::isAiMockEnabled())
                    <button class="nav-link text-start border-0 bg-transparent" id="interview-tab" data-bs-toggle="tab" data-bs-target="#interviewPane" type="button" role="tab">
                        <i class="fa-solid fa-robot"></i> <span>AI Mock Interview</span>
                    </button>
                @endif

                <!-- 4. NETWORK & COMMUNITY -->
                <div class="sidebar-group-title mt-2">Network & Community</div>
                <a class="nav-link text-start border-0 bg-transparent text-decoration-none" href="{{ route('feed.index') }}">
                    <i class="fa-solid fa-rss"></i> <span>Social Feed</span>
                </a>

                <!-- 5. COMMUNICATION -->
                <div class="sidebar-group-title mt-2">Communication</div>
                <button class="nav-link text-start border-0 bg-transparent" id="inbox-tab" data-bs-toggle="tab" data-bs-target="#inboxPane" type="button" role="tab">
                    <i class="fa-solid fa-envelope"></i> <span>Inquiries & Messages</span>
                    @php $unreadMsg = $portfolio->messages->where('is_read', false)->count(); @endphp
                    @if($unreadMsg > 0)
                        <span class="badge bg-danger rounded-pill ms-auto" style="font-size: 0.65rem;">{{ $unreadMsg }}</span>
                    @endif
                </button>
                <a class="nav-link text-start border-0 bg-transparent text-decoration-none" href="{{ route('notifications.index') }}">
                    <i class="fa-solid fa-bell"></i> <span>Notifications</span>
                    @if($unreadNotificationsCount > 0)
                        <span class="badge bg-danger rounded-pill ms-auto" style="font-size: 0.65rem;">{{ $unreadNotificationsCount }}</span>
                    @endif
                </a>

                <!-- 6. ACCOUNT -->
                <div class="sidebar-group-title mt-2">Account</div>
                <a class="nav-link text-start border-0 bg-transparent text-decoration-none" href="{{ route('preferences.edit') }}">
                    <i class="fa-solid fa-user-gear"></i> <span>Career Preferences</span>
                </a>
            </div>
        </div>

        <!-- Mobile Drawer Toggler Bar -->
        <div class="d-lg-none bg-white p-3 border-bottom d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-2">
                <button class="btn btn-outline-secondary btn-sm" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebarDrawer">
                    <i class="fa-solid fa-bars me-1"></i> Menu
                </button>
                <span class="fw-bold text-dark small">Career Workspace</span>
            </div>
            <a href="{{ route('portfolio.show', Auth::user()->username) }}" target="_blank" class="btn btn-outline-primary btn-sm rounded-pill">
                <i class="fa-solid fa-eye me-1"></i> View Live
            </a>
        </div>

        <!-- Mobile Sidebar Offcanvas Drawer -->
        <div class="offcanvas offcanvas-start" tabindex="-1" id="mobileSidebarDrawer">
            <div class="offcanvas-header border-bottom">
                <h5 class="offcanvas-title fw-bold text-dark" style="font-size: 1rem;">Navigation Workspace</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body p-3">
                <div class="nav flex-column sidebar-menu" id="mobileCareerWorkspaceTabs" role="tablist">
                    <button class="nav-link active text-start border-0 bg-transparent" data-bs-toggle="tab" data-bs-target="#overviewPane" type="button" data-bs-dismiss="offcanvas">
                        <i class="fa-solid fa-gauge-high"></i> <span>Career Overview</span>
                    </button>

                    <!-- 1. PORTFOLIO -->
                    <div class="sidebar-group-title mt-2">Portfolio</div>
                    <button class="nav-link text-start border-0 bg-transparent" data-bs-toggle="tab" data-bs-target="#cmsPane" type="button" data-bs-dismiss="offcanvas">
                        <i class="fa-solid fa-id-card"></i> <span>My Portfolio CMS</span>
                    </button>
                    <button class="nav-link text-start border-0 bg-transparent" data-bs-toggle="tab" data-bs-target="#settingsPane" type="button" data-bs-dismiss="offcanvas">
                        <i class="fa-solid fa-sliders"></i> <span>Portfolio Settings</span>
                    </button>
                    <button class="nav-link text-start border-0 bg-transparent" data-bs-toggle="tab" data-bs-target="#themesPane" type="button" data-bs-dismiss="offcanvas">
                        <i class="fa-solid fa-palette"></i> <span>Portfolio Themes</span>
                    </button>
                    <button class="nav-link text-start border-0 bg-transparent" data-bs-toggle="tab" data-bs-target="#networkPane" type="button" data-bs-dismiss="offcanvas">
                        <i class="fa-solid fa-users"></i> <span>Connections</span>
                    </button>

                    <!-- 2. COMPANY & ORGANIZATION -->
                    <div class="sidebar-group-title mt-2">Company & Organization</div>
                    <a class="nav-link text-start border-0 bg-transparent text-decoration-none" href="{{ route('companies.create') }}">
                        <i class="fa-solid fa-building-user text-primary flex-shrink-0"></i> <span>Create Company Profile</span>
                    </a>
                    @if(Auth::user()->companies->isNotEmpty())
                        @foreach(Auth::user()->companies as $myCompany)
                            <a class="nav-link text-start border-0 bg-transparent text-decoration-none py-2" href="{{ route('companies.dashboard', $myCompany->id) }}" title="{{ $myCompany->name }} Dashboard" style="min-width: 0; max-width: 100%;">
                                <i class="fa-solid fa-building text-success flex-shrink-0 mt-1"></i>
                                <span class="flex-grow-1 min-w-0" style="word-break: break-word; overflow-wrap: anywhere; white-space: normal; line-height: 1.35; font-size: 0.82rem;">{{ Str::limit($myCompany->name, 28) }}</span>
                            </a>
                        @endforeach
                    @endif

                    <!-- 3. CAREER -->
                    <div class="sidebar-group-title mt-2">Career</div>
                    <button class="nav-link text-start border-0 bg-transparent" data-bs-toggle="tab" data-bs-target="#jobsPane" type="button" data-bs-dismiss="offcanvas">
                        <i class="fa-solid fa-briefcase"></i> <span>Jobs & Opportunities</span>
                    </button>
                    <button class="nav-link text-start border-0 bg-transparent" data-bs-toggle="tab" data-bs-target="#applicationsPane" type="button" data-bs-dismiss="offcanvas">
                        <i class="fa-solid fa-paper-plane"></i> <span>My Applications</span>
                    </button>
                    <button class="nav-link text-start border-0 bg-transparent" data-bs-toggle="tab" data-bs-target="#savedPane" type="button" data-bs-dismiss="offcanvas">
                        <i class="fa-solid fa-bookmark"></i> <span>Saved Jobs</span>
                    </button>
                    @if(\App\Models\SystemSetting::isAiMockEnabled())
                        <button class="nav-link text-start border-0 bg-transparent" data-bs-toggle="tab" data-bs-target="#interviewPane" type="button" data-bs-dismiss="offcanvas">
                            <i class="fa-solid fa-robot"></i> <span>AI Mock Interview</span>
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="flex-grow-1 p-3 p-md-4 overflow-auto">
            <!-- Flash Alerts -->
            @if (session('status'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
                    <i class="fa-solid fa-circle-check me-2"></i> {{ str_replace('-', ' ', ucfirst(session('status'))) }} successfully.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
                    <i class="fa-solid fa-circle-exclamation me-2"></i> <strong>Please correct the following errors:</strong>
                    <ul class="mb-0 ps-3 mt-1 small">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="tab-content" id="careerWorkspaceTabContent">
                <!-- ========================================== -->
                <!-- 1. OVERVIEW DASHBOARD PANE -->
                <!-- ========================================== -->
                <div class="tab-pane fade show active" id="overviewPane" role="tabpanel">
                    <!-- Personalized Welcome Banner -->
                    <div class="workspace-header-card p-4 mb-4 shadow-sm">
                        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 position-relative" style="z-index: 2;">
                            <div>
                                <span class="badge bg-white bg-opacity-20 text-white rounded-pill px-3 py-1 mb-2 fw-semibold" style="font-size: 0.72rem;">
                                    <i class="fa-solid fa-sparkles me-1 text-warning"></i> Professional Career Command Center
                                </span>
                                <h1 class="h3 fw-bold text-white mb-1">
                                    @php
                                        $hour = date('H');
                                        $greeting = $hour < 12 ? 'Good morning' : ($hour < 17 ? 'Good afternoon' : 'Good evening');
                                    @endphp
                                    {{ $greeting }}, {{ Auth::user()->name }}!
                                </h1>
                                <p class="text-white-50 small mb-0">
                                    @if($completionScore < 100)
                                        Your professional profile is <strong>{{ $completionScore }}% complete</strong>. Complete remaining items to maximize your job visibility.
                                    @elseif($myApplications->count() > 0)
                                        You have <strong>{{ $myApplications->count() }} active job applications</strong>. Keep track of updates below.
                                    @else
                                        Welcome back! Discover top-matched jobs and build your professional presence.
                                    @endif
                                </p>
                            </div>
                            <div class="d-flex gap-2 flex-shrink-0">
                                <a href="{{ route('portfolio.show', Auth::user()->username) }}" target="_blank" class="btn btn-light btn-sm rounded-pill px-3 shadow-sm fw-bold">
                                    <i class="fa-solid fa-arrow-up-right-from-square me-1 text-primary"></i> Live Portfolio
                                </a>
                                <button onclick="document.getElementById('settings-tab').click();" class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm fw-bold">
                                    <i class="fa-solid fa-gear me-1"></i> Portfolio Settings
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Career Snapshot KPI Bar -->
                    <div class="row row-cols-2 row-cols-md-3 row-cols-xl-5 g-3 mb-4">
                        <div class="col">
                            <div class="metric-stat-card">
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <span class="text-muted small fw-semibold">Profile Views</span>
                                    <span class="badge bg-primary-subtle text-primary rounded-circle p-2"><i class="fa-solid fa-eye"></i></span>
                                </div>
                                <div class="metric-number">{{ $portfolio->views_count ?? 124 }}</div>
                                <small class="text-success small fw-semibold" style="font-size: 0.72rem;"><i class="fa-solid fa-arrow-trend-up me-1"></i>+12% this week</small>
                            </div>
                        </div>
                        <div class="col">
                            <div class="metric-stat-card">
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <span class="text-muted small fw-semibold">Job Matches</span>
                                    <span class="badge bg-success-subtle text-success rounded-circle p-2"><i class="fa-solid fa-bullseye"></i></span>
                                </div>
                                <div class="metric-number">{{ $recommendedOpportunities->count() }}</div>
                                <small class="text-muted small" style="font-size: 0.72rem;">Top match: {{ $recommendedOpportunities->first()->match_evaluation['overall_score'] ?? 85 }}%</small>
                            </div>
                        </div>
                        <div class="col">
                            <div class="metric-stat-card">
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <span class="text-muted small fw-semibold">Applications</span>
                                    <span class="badge bg-info-subtle text-info rounded-circle p-2"><i class="fa-solid fa-paper-plane"></i></span>
                                </div>
                                <div class="metric-number">{{ $myApplications->count() }}</div>
                                <small class="text-muted small" style="font-size: 0.72rem;">{{ $pipelineCounts['under_review'] + $pipelineCounts['interview'] }} active review</small>
                            </div>
                        </div>
                        <div class="col">
                            <div class="metric-stat-card">
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <span class="text-muted small fw-semibold">Connections</span>
                                    <span class="badge bg-warning-subtle text-warning rounded-circle p-2"><i class="fa-solid fa-users"></i></span>
                                </div>
                                <div class="metric-number">{{ $connectionsCount }}</div>
                                <small class="text-muted small" style="font-size: 0.72rem;">{{ $pendingReceived->count() }} pending request</small>
                            </div>
                        </div>
                        <div class="col col-md-12 col-xl-auto flex-grow-1">
                            <div class="metric-stat-card">
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <span class="text-muted small fw-semibold">Saved Jobs</span>
                                    <span class="badge bg-secondary-subtle text-secondary rounded-circle p-2"><i class="fa-solid fa-bookmark"></i></span>
                                </div>
                                <div class="metric-number">{{ $savedOpportunities->count() }}</div>
                                <small class="text-muted small" style="font-size: 0.72rem;">Saved opportunities</small>
                            </div>
                        </div>
                    </div>

                    <!-- Main Grid: 2 Columns Layout -->
                    <div class="row g-4 mb-4">
                        <!-- Left Column (Primary Content) -->
                        <div class="col-lg-8">
                            <!-- Job Matching Section (Primary Feature) -->
                            <div class="card border-0 shadow-sm rounded-3 mb-4">
                                <div class="card-header bg-white py-3 px-4 d-flex align-items-center justify-content-between rounded-top-3 border-bottom">
                                    <div>
                                        <h5 class="fw-bold text-dark mb-0"><i class="fa-solid fa-wand-magic-sparkles text-primary me-2"></i> Recommended Opportunities For You</h5>
                                        <small class="text-muted">Matched using your skills, experience, and career preferences</small>
                                    </div>
                                    <button onclick="document.getElementById('jobs-tab').click();" class="btn btn-outline-primary btn-sm rounded-pill">Explore All</button>
                                </div>
                                <div class="card-body p-3">
                                    <div class="row g-3">
                                        @forelse($recommendedOpportunities->take(4) as $opp)
                                            <div class="col-12 col-md-6">
                                                <div class="job-match-card p-3 h-100 d-flex flex-column">
                                                    <div class="d-flex align-items-start justify-content-between gap-2 mb-2 w-100" style="min-width: 0;">
                                                        <div class="d-flex align-items-center gap-2 flex-grow-1" style="min-width: 0; max-width: calc(100% - 85px);">
                                                            @if($opp->company && $opp->company->logo_path)
                                                                <img src="{{ Storage::url($opp->company->logo_path) }}" class="rounded border flex-shrink-0" style="width: 34px; height: 34px; object-fit: cover;">
                                                            @else
                                                                <div class="rounded bg-light border d-flex align-items-center justify-content-center text-primary fw-bold flex-shrink-0" style="width: 34px; height: 34px; font-size: 0.85rem;">
                                                                    {{ strtoupper(substr($opp->company->name ?? 'C', 0, 1)) }}
                                                                </div>
                                                            @endif
                                                            <div class="min-w-0 flex-grow-1" style="min-width: 0;">
                                                                <a href="{{ route('opportunities.show', $opp->slug) }}" class="fw-bold text-dark text-decoration-none small text-truncate d-block">{{ $opp->title }}</a>
                                                                <span class="text-muted d-block text-truncate" style="font-size: 0.73rem;">{{ $opp->company->name ?? 'Organization' }}</span>
                                                            </div>
                                                        </div>
                                                        <span class="match-badge flex-shrink-0"><i class="fa-solid fa-bullseye me-1"></i>{{ $opp->match_evaluation['overall_score'] }}% Match</span>
                                                    </div>

                                                    <div class="d-flex flex-wrap gap-1 mb-2">
                                                        <span class="badge bg-light text-secondary border" style="font-size: 0.68rem;"><i class="fa-solid fa-location-dot me-1"></i>{{ $opp->location ?? 'Remote' }}</span>
                                                        <span class="badge bg-light text-secondary border" style="font-size: 0.68rem;"><i class="fa-solid fa-clock me-1"></i>{{ ucfirst($opp->employment_type ?? 'Full-time') }}</span>
                                                    </div>

                                                    <div class="mt-auto pt-2 border-top d-flex align-items-center justify-content-between">
                                                        <small class="text-muted" style="font-size: 0.7rem;">{{ $opp->created_at->diffForHumans() }}</small>
                                                        <a href="{{ route('opportunities.show', $opp->slug) }}" class="btn btn-sm btn-primary py-1 px-3">View Job</a>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="col-12 text-center py-4">
                                                <i class="fa-solid fa-briefcase fa-2x text-muted mb-2"></i>
                                                <h6 class="fw-bold text-dark mb-1">No recommended jobs yet</h6>
                                                <p class="text-muted small mb-3">Add skills and experience to your portfolio to activate intelligent job matching.</p>
                                                <button onclick="document.getElementById('cms-tab').click();" class="btn btn-primary btn-sm rounded-pill">Add Skills to Portfolio</button>
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>

                            <!-- Application Pipeline Tracker -->
                            <div class="card border-0 shadow-sm rounded-3 mb-4">
                                <div class="card-header bg-white py-3 px-4 d-flex align-items-center justify-content-between rounded-top-3 border-bottom">
                                    <div>
                                        <h5 class="fw-bold text-dark mb-0"><i class="fa-solid fa-list-check text-info me-2"></i> Application Pipeline</h5>
                                        <small class="text-muted">Live application status tracking</small>
                                    </div>
                                    <a href="{{ route('applications.candidate.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill">View All</a>
                                </div>
                                <div class="card-body p-3 p-md-4">
                                    <div class="pipeline-wrapper mb-4">
                                        <div class="pipeline-container d-flex justify-content-between align-items-center">
                                            <div class="pipeline-step {{ $pipelineCounts['applied'] > 0 ? 'completed' : 'active' }}">
                                                <div class="pipeline-dot"><i class="fa-solid fa-paper-plane"></i></div>
                                                <div class="small fw-semibold mt-1">Applied ({{ $pipelineCounts['applied'] }})</div>
                                            </div>
                                            <div class="pipeline-step {{ $pipelineCounts['under_review'] > 0 ? 'active' : '' }}">
                                                <div class="pipeline-dot"><i class="fa-solid fa-magnifying-glass"></i></div>
                                                <div class="small fw-semibold mt-1">Review ({{ $pipelineCounts['under_review'] }})</div>
                                            </div>
                                            <div class="pipeline-step {{ $pipelineCounts['shortlisted'] > 0 ? 'active' : '' }}">
                                                <div class="pipeline-dot"><i class="fa-solid fa-star"></i></div>
                                                <div class="small fw-semibold mt-1">Shortlisted ({{ $pipelineCounts['shortlisted'] }})</div>
                                            </div>
                                            <div class="pipeline-step {{ $pipelineCounts['interview'] > 0 ? 'active' : '' }}">
                                                <div class="pipeline-dot"><i class="fa-solid fa-comments"></i></div>
                                                <div class="small fw-semibold mt-1">Interview ({{ $pipelineCounts['interview'] }})</div>
                                            </div>
                                            <div class="pipeline-step {{ $pipelineCounts['selected'] > 0 ? 'completed' : '' }}">
                                                <div class="pipeline-dot"><i class="fa-solid fa-circle-check"></i></div>
                                                <div class="small fw-semibold mt-1">Selected ({{ $pipelineCounts['selected'] }})</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column (Profile & Actions) -->
                        <div class="col-lg-4">
                            <!-- Professional Profile Card -->
                            <div class="profile-identity-card p-4 mb-4">
                                <div class="d-flex align-items-center gap-3 mb-3 overflow-hidden">
                                    @if($portfolio->profile_image)
                                        <img src="{{ Storage::url($portfolio->profile_image) }}" class="rounded-circle border shadow-sm flex-shrink-0" style="width: 56px; height: 56px; object-fit: cover;">
                                    @else
                                        <div class="rounded-circle text-white d-flex align-items-center justify-content-center fw-bold fs-4 flex-shrink-0" style="width: 56px; height: 56px; background-color: var(--brand-primary);">
                                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div class="flex-grow-1 overflow-hidden" style="min-width: 0;">
                                        <h6 class="fw-bold text-dark mb-0 text-truncate" title="{{ Auth::user()->name }}">{{ Auth::user()->name }}</h6>
                                        <small class="text-primary fw-semibold d-block text-truncate" style="font-size: 0.78rem;" title="{{ $portfolio->position ?? 'Professional Candidate' }}">{{ $portfolio->position ?? 'Professional Candidate' }}</small>
                                        <small class="text-muted d-block text-truncate" style="font-size: 0.72rem;"><i class="fa-solid fa-location-dot me-1"></i>{{ $portfolio->city ?? 'Location' }}, {{ $portfolio->country ?? 'Pakistan' }}</small>
                                    </div>
                                </div>

                                <!-- Visibility Status Badge -->
                                <div class="d-flex align-items-center justify-content-between bg-light p-2 rounded-3 mb-3 border">
                                    <span class="small fw-semibold text-muted" style="font-size: 0.75rem;">Visibility Status</span>
                                    <span class="badge rounded-pill {{ $portfolio->is_public ? 'bg-success' : 'bg-secondary' }}">
                                        <i class="fa-solid {{ $portfolio->is_public ? 'fa-globe' : 'fa-lock' }} me-1"></i>{{ $portfolio->is_public ? 'PUBLIC' : 'PRIVATE' }}
                                    </span>
                                </div>

                                <!-- Completion Score Bar -->
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between align-items-center small mb-1">
                                        <span class="fw-semibold text-dark" style="font-size: 0.78rem;">Profile Completeness</span>
                                        <span class="fw-bold text-primary" style="font-size: 0.82rem;">{{ $completionScore }}%</span>
                                    </div>
                                    <div class="completion-bar-outer">
                                        <div class="completion-bar-inner" style="width: {{ $completionScore }}%;"></div>
                                    </div>
                                </div>

                                <div class="d-grid gap-2">
                                    <button onclick="document.getElementById('settings-tab').click();" class="btn btn-primary btn-sm rounded-pill fw-semibold">
                                        <i class="fa-solid fa-sliders me-1"></i> Portfolio Settings
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ========================================== -->
                <!-- 2. PORTFOLIO SETTINGS PANE -->
                <!-- ========================================== -->
                <div class="tab-pane fade" id="settingsPane" role="tabpanel">
                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4 pb-3 border-bottom">
                        <div>
                            <h3 class="fw-bold text-dark mb-0"><i class="fa-solid fa-sliders text-primary me-2"></i> Portfolio Settings & Controls</h3>
                            <p class="text-secondary small mb-0">Configure your profile image, contact details, pitch tagline, and visibility parameters.</p>
                        </div>
                        <a href="{{ route('portfolio.show', Auth::user()->username) }}" target="_blank" class="btn btn-outline-primary btn-sm rounded-pill flex-shrink-0">
                            <i class="fa-solid fa-eye me-1"></i> View Live Portfolio
                        </a>
                    </div>

                    <form action="{{ route('portfolio.update') }}" method="POST" enctype="multipart/form-data" class="bg-white p-3 p-md-4 rounded-3 border shadow-sm mb-4">
                        @csrf
                        <input type="hidden" name="active_tab" value="settingsPane">
                        
                        <!-- Profile Picture Upload & Primary Identity -->
                        <h6 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="fa-solid fa-image text-primary me-2"></i> Profile Photo & Core Info</h6>
                        <div class="row g-3 mb-4 align-items-center">
                            <div class="col-auto">
                                @if($portfolio->profile_image)
                                    <img src="{{ Storage::url($portfolio->profile_image) }}" class="rounded-circle border shadow-sm" style="width: 65px; height: 65px; object-fit: cover;">
                                @else
                                    <div class="rounded-circle text-white d-flex align-items-center justify-content-center fw-bold fs-3" style="width: 65px; height: 65px; background-color: var(--brand-primary);">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            <div class="col">
                                <label class="form-label fw-bold">Upload New Profile Picture</label>
                                <input type="file" name="profile_image" class="form-control" accept="image/*">
                                <small class="text-muted d-block mt-1">PNG or JPG, max 5MB.</small>
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-bold">Portfolio Title / Headline</label>
                                <input name="title" class="form-control" value="{{ $portfolio->title }}" required>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-bold">Professional Position / Role</label>
                                <input name="position" class="form-control" value="{{ $portfolio->position }}">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-bold">Organization / Current Company</label>
                                <input name="organization" class="form-control" value="{{ $portfolio->organization }}">
                            </div>
                            <div class="col-6 col-md-3">
                                <label class="form-label fw-bold">City</label>
                                <input name="city" class="form-control" value="{{ $portfolio->city }}">
                            </div>
                            <div class="col-6 col-md-3">
                                <label class="form-label fw-bold">Country</label>
                                <input name="country" class="form-control" value="{{ $portfolio->country }}">
                            </div>
                        </div>

                        <!-- Contact Details & Social Links -->
                        <h6 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="fa-solid fa-address-book text-success me-2"></i> Contact Details & Links</h6>
                        <div class="row g-3 mb-4">
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-bold">Account Email</label>
                                <div class="input-group">
                                    <input type="text" class="form-control bg-light" value="{{ Auth::user()->email }}" disabled>
                                    <select name="show_email" class="form-select bg-white" style="max-width: 140px;">
                                        <option value="show" {{ $portfolio->show_email ? 'selected' : '' }}>Show Email</option>
                                        <option value="hide" {{ !$portfolio->show_email ? 'selected' : '' }}>Hide Email</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-bold">Contact Number</label>
                                <div class="input-group">
                                    <input name="contact_number" class="form-control" value="{{ $portfolio->contact_number }}" placeholder="+92 300 1234567">
                                    <select name="show_phone" class="form-select bg-white" style="max-width: 140px;">
                                        <option value="show" {{ $portfolio->show_phone ? 'selected' : '' }}>Show Number</option>
                                        <option value="hide" {{ !$portfolio->show_phone ? 'selected' : '' }}>Hide Number</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold">LinkedIn Profile URL</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa-brands fa-linkedin text-primary"></i></span>
                                    <input name="linkedin_url" class="form-control" value="{{ $portfolio->linkedin_url }}" placeholder="https://linkedin.com/in/username">
                                    <select name="show_linkedin" class="form-select bg-white" style="max-width: 150px;">
                                        <option value="show" {{ $portfolio->show_linkedin ? 'selected' : '' }}>Show LinkedIn</option>
                                        <option value="hide" {{ !$portfolio->show_linkedin ? 'selected' : '' }}>Hide LinkedIn</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Pitch Tagline & Summary -->
                        <h6 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="fa-solid fa-comment-dots text-warning me-2"></i> Pitch Hook & Summary</h6>
                        <div class="mb-4">
                            <label class="form-label fw-bold">Short Pitch Hook / Summary</label>
                            <textarea name="description" class="form-control js-summernote" data-height="120">{{ $portfolio->description }}</textarea>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold">Detailed Bio</label>
                            <textarea name="detailed_bio" class="form-control js-summernote" data-height="160">{{ $portfolio->detailed_bio }}</textarea>
                        </div>

                        <!-- Visibility Controls -->
                        <h6 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="fa-solid fa-eye text-info me-2"></i> Portfolio Link Status & Visibility</h6>
                        <div class="row g-3 mb-4">
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-bold">Portfolio Link Status</label>
                                <select name="is_active" class="form-select">
                                    <option value="active" {{ $portfolio->is_active === 'active' ? 'selected' : '' }}>Active (Portfolio link is live)</option>
                                    <option value="inactive" {{ $portfolio->is_active === 'inactive' ? 'selected' : '' }}>Inactive (Portfolio link is disabled)</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-bold">Public Search Visibility</label>
                                <select name="is_public" class="form-select">
                                    <option value="public" {{ $portfolio->is_public ? 'selected' : '' }}>Public (Discoverable by employers)</option>
                                    <option value="private" {{ !$portfolio->is_public ? 'selected' : '' }}>Private (Connections only)</option>
                                </select>
                            </div>
                        </div>

                        <!-- Section Display Toggles -->
                        <h6 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="fa-solid fa-layer-group text-primary me-2"></i> Content Section Display Toggles</h6>
                        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-3 mb-4">
                            <div class="col">
                                <label class="form-label fw-bold small">Technical Skills</label>
                                <select name="show_skills" class="form-select form-select-sm">
                                    <option value="show" {{ $portfolio->show_skills ? 'selected' : '' }}>Show Section</option>
                                    <option value="hide" {{ !$portfolio->show_skills ? 'selected' : '' }}>Hide Section</option>
                                </select>
                            </div>
                            <div class="col">
                                <label class="form-label fw-bold small">Projects Showcase</label>
                                <select name="show_projects" class="form-select form-select-sm">
                                    <option value="show" {{ $portfolio->show_projects ? 'selected' : '' }}>Show Section</option>
                                    <option value="hide" {{ !$portfolio->show_projects ? 'selected' : '' }}>Hide Section</option>
                                </select>
                            </div>
                            <div class="col">
                                <label class="form-label fw-bold small">Work Experience</label>
                                <select name="show_experience" class="form-select form-select-sm">
                                    <option value="show" {{ $portfolio->show_experience ? 'selected' : '' }}>Show Section</option>
                                    <option value="hide" {{ !$portfolio->show_experience ? 'selected' : '' }}>Hide Section</option>
                                </select>
                            </div>
                            <div class="col">
                                <label class="form-label fw-bold small">Education Records</label>
                                <select name="show_education" class="form-select form-select-sm">
                                    <option value="show" {{ $portfolio->show_education ? 'selected' : '' }}>Show Section</option>
                                    <option value="hide" {{ !$portfolio->show_education ? 'selected' : '' }}>Hide Section</option>
                                </select>
                            </div>
                            <div class="col">
                                <label class="form-label fw-bold small">Services Offered</label>
                                <select name="show_services" class="form-select form-select-sm">
                                    <option value="show" {{ $portfolio->show_services ? 'selected' : '' }}>Show Section</option>
                                    <option value="hide" {{ !$portfolio->show_services ? 'selected' : '' }}>Hide Section</option>
                                </select>
                            </div>
                            <div class="col">
                                <label class="form-label fw-bold small">Certifications</label>
                                <select name="show_certifications" class="form-select form-select-sm">
                                    <option value="show" {{ $portfolio->show_certifications ? 'selected' : '' }}>Show Section</option>
                                    <option value="hide" {{ !$portfolio->show_certifications ? 'selected' : '' }}>Hide Section</option>
                                </select>
                            </div>
                            <div class="col">
                                <label class="form-label fw-bold small">Trainings & Courses</label>
                                <select name="show_trainings" class="form-select form-select-sm">
                                    <option value="show" {{ $portfolio->show_trainings ? 'selected' : '' }}>Show Section</option>
                                    <option value="hide" {{ !$portfolio->show_trainings ? 'selected' : '' }}>Hide Section</option>
                                </select>
                            </div>
                            <div class="col">
                                <label class="form-label fw-bold small">Achievements</label>
                                <select name="show_achievements" class="form-select form-select-sm">
                                    <option value="show" {{ $portfolio->show_achievements ? 'selected' : '' }}>Show Section</option>
                                    <option value="hide" {{ !$portfolio->show_achievements ? 'selected' : '' }}>Hide Section</option>
                                </select>
                            </div>
                            <div class="col">
                                <label class="form-label fw-bold small">Contributions</label>
                                <select name="show_contributions" class="form-select form-select-sm">
                                    <option value="show" {{ $portfolio->show_contributions ? 'selected' : '' }}>Show Section</option>
                                    <option value="hide" {{ !$portfolio->show_contributions ? 'selected' : '' }}>Hide Section</option>
                                </select>
                            </div>
                            <div class="col">
                                <label class="form-label fw-bold small">Testimonials</label>
                                <select name="show_testimonials" class="form-select form-select-sm">
                                    <option value="show" {{ $portfolio->show_testimonials ? 'selected' : '' }}>Show Section</option>
                                    <option value="hide" {{ !$portfolio->show_testimonials ? 'selected' : '' }}>Hide Section</option>
                                </select>
                            </div>
                            <div class="col">
                                <label class="form-label fw-bold small">Media Appearances</label>
                                <select name="show_media" class="form-select form-select-sm">
                                    <option value="show" {{ $portfolio->show_media ? 'selected' : '' }}>Show Section</option>
                                    <option value="hide" {{ !$portfolio->show_media ? 'selected' : '' }}>Hide Section</option>
                                </select>
                            </div>
                            <div class="col">
                                <label class="form-label fw-bold small">Publications</label>
                                <select name="show_publications" class="form-select form-select-sm">
                                    <option value="show" {{ $portfolio->show_publications ? 'selected' : '' }}>Show Section</option>
                                    <option value="hide" {{ !$portfolio->show_publications ? 'selected' : '' }}>Hide Section</option>
                                </select>
                            </div>
                        </div>

                        <div class="pt-3 border-top d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary px-4 rounded-pill fw-bold shadow-sm w-100 w-sm-auto">
                                <i class="fa-solid fa-floppy-disk me-1"></i> Save Portfolio Settings
                            </button>
                        </div>
                    </form>
                </div>

                <!-- ========================================== -->
                <!-- 3. PORTFOLIO THEMES PANE -->
                <!-- ========================================== -->
                <div class="tab-pane fade" id="themesPane" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                        <div>
                            <h3 class="fw-bold text-dark mb-0"><i class="fa-solid fa-palette text-primary me-2"></i> Portfolio Themes & Visual Engine</h3>
                            <p class="text-secondary small mb-0">Select an active visual layout theme for your public portfolio.</p>
                        </div>
                    </div>

                    <div class="row g-3 g-md-4">
                        @foreach($themes as $theme)
                            @php
                                $tKey = str_contains(strtolower($theme->name), 'premium') ? 'premium' : (str_contains(strtolower($theme->name), 'elegant') ? 'elegant' : 'classic');
                                $currentThemeKey = str_contains(strtolower($portfolio->theme ?? 'classic'), 'premium') ? 'premium' : (str_contains(strtolower($portfolio->theme ?? 'classic'), 'elegant') ? 'elegant' : 'classic');
                                $isCurrentActive = ($currentThemeKey === $tKey);
                            @endphp
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="theme-card p-4 h-100 d-flex flex-column {{ $isCurrentActive ? 'active-theme' : '' }}">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h5 class="fw-bold text-dark mb-0">{{ $theme->name }} Theme</h5>
                                        @if($isCurrentActive)
                                            <span class="badge bg-primary rounded-pill"><i class="fa-solid fa-check me-1"></i> Active</span>
                                        @endif
                                    </div>
                                    <p class="text-muted small mb-4 flex-grow-1">{{ $theme->description ?? 'Modern layout theme.' }}</p>
                                    
                                    <form action="{{ route('portfolio.update') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="active_tab" value="themesPane">
                                        <input type="hidden" name="theme" value="{{ $tKey }}">
                                        @if($isCurrentActive)
                                            <button type="button" class="btn btn-outline-primary btn-sm w-100 rounded-pill" disabled>Currently Selected</button>
                                        @else
                                            <button type="submit" class="btn btn-primary btn-sm w-100 rounded-pill fw-bold">Apply Theme</button>
                                        @endif
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- ========================================== -->
                <!-- 4. MY PORTFOLIO CMS PANE -->
                <!-- ========================================== -->
                <div class="tab-pane fade" id="cmsPane" role="tabpanel">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4 pb-3 border-bottom">
                        <div>
                            <h3 class="fw-bold text-dark mb-0"><i class="fa-solid fa-id-card text-primary me-2"></i> Portfolio Content Modules</h3>
                            <p class="text-secondary small mb-0">Manage your skills, projects, work history, credentials, and resume documents.</p>
                        </div>
                    </div>

                    <!-- Profile Completeness Progress Card -->
                    <div class="card border-0 shadow-sm rounded-3 p-4 mb-4 bg-white">
                        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-3">
                            <div>
                                <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-1 mb-1 fw-bold" style="font-size: 0.75rem;">
                                    <i class="fa-solid fa-chart-line me-1"></i> Real-time Profile Optimization Engine
                                </span>
                                <h5 class="fw-bold text-dark mb-0">Profile Completeness: <span class="text-primary">{{ $completionScore }}%</span></h5>
                                <small class="text-muted">Populating items across all 13 content modules maximizes your job matching score & employer reach.</small>
                            </div>
                            <div class="flex-shrink-0 text-md-end">
                                <span class="badge {{ $completionScore >= 100 ? 'bg-success' : ($completionScore >= 60 ? 'bg-primary' : 'bg-warning text-dark') }} fs-6 rounded-pill px-3 py-2">
                                    <i class="fa-solid {{ $completionScore >= 100 ? 'fa-circle-check' : 'fa-spinner fa-spin-pulse' }} me-1"></i>
                                    {{ $completionScore >= 100 ? 'Fully Optimized (100%)' : $completionScore . '% Completed' }}
                                </span>
                            </div>
                        </div>

                        <!-- Progress Bar -->
                        <div class="progress rounded-pill mb-3" style="height: 12px;">
                            <div class="progress-bar progress-bar-striped progress-bar-animated {{ $completionScore >= 100 ? 'bg-success' : ($completionScore >= 60 ? 'bg-primary' : 'bg-warning') }}" 
                                 role="progressbar" 
                                 style="width: {{ $completionScore }}%;" 
                                 aria-valuenow="{{ $completionScore }}" 
                                 aria-valuemin="0" 
                                 aria-valuemax="100"></div>
                        </div>

                        @if(count($missingItems) > 0)
                            <div class="bg-light p-3 rounded-3 border mb-2">
                                <small class="fw-bold text-dark d-block mb-1"><i class="fa-solid fa-circle-info text-primary me-1"></i> Core modules to add for 100% completeness:</small>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach($missingItems as $missing)
                                        <span class="badge bg-white text-secondary border rounded-pill shadow-xs" style="font-size: 0.73rem;">
                                            <i class="fa-solid fa-plus text-primary me-1"></i> {{ $missing }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="bg-success-subtle text-success p-3 rounded-3 border border-success-subtle d-flex align-items-center gap-2 mb-2">
                                <i class="fa-solid fa-circle-check fs-5"></i>
                                <span class="small fw-bold">Outstanding! Core data has been added across all essential modules. Your profile is 100% complete!</span>
                            </div>
                        @endif

                        @if(isset($optionalMissingItems) && count($optionalMissingItems) > 0)
                            <div class="bg-white p-2 px-3 rounded-3 border d-flex align-items-center justify-content-between flex-wrap gap-2">
                                <small class="text-muted" style="font-size: 0.75rem;"><i class="fa-solid fa-sparkles text-warning me-1"></i> <strong>Optional Bonus Showcase Modules Empty:</strong> {{ implode(', ', $optionalMissingItems) }}</small>
                                <small class="text-secondary" style="font-size: 0.72rem;">(Optional features to further showcase public presence)</small>
                            </div>
                        @endif
                    </div>

                    <!-- Grid of 13 Section Modules -->
                    <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-3" id="modulesGrid">
                        @php
                            $modulesConfig = [
                                'skills' => ['title' => 'Skills', 'icon' => 'fa-screwdriver-wrench', 'count' => $portfolio->skills->count(), 'desc' => 'Technical expertise, percentage bars & categories.'],
                                'projects' => ['title' => 'Projects', 'icon' => 'fa-laptop-code', 'count' => $portfolio->projects->count(), 'desc' => 'Flagship projects, showcases, and live URLs.'],
                                'experience' => ['title' => 'Work Experience', 'icon' => 'fa-briefcase', 'count' => $portfolio->experiences->count(), 'desc' => 'Job history, roles, and achievements.'],
                                'education' => ['title' => 'Education', 'icon' => 'fa-graduation-cap', 'count' => $portfolio->education->count(), 'desc' => 'Degrees, universities, and academic records.'],
                                'services' => ['title' => 'Services Offered', 'icon' => 'fa-layer-group', 'count' => $portfolio->services->count(), 'desc' => 'Freelance and consultancy services.'],
                                'certifications' => ['title' => 'Certifications', 'icon' => 'fa-certificate', 'count' => $portfolio->certifications->count(), 'desc' => 'Professional licenses and credentials.'],
                                'trainings' => ['title' => 'Trainings', 'icon' => 'fa-chalkboard-user', 'count' => $portfolio->trainings->count(), 'desc' => 'Bootcamps, workshops, and courses.'],
                                'achievements' => ['title' => 'Achievements', 'icon' => 'fa-trophy', 'count' => $portfolio->achievements->count(), 'desc' => 'Awards, honors, and soft skills.'],
                                'contributions' => ['title' => 'Contributions', 'icon' => 'fa-code-branch', 'count' => $portfolio->contributions->count(), 'desc' => 'Open source and social impact links.'],
                                'testimonials' => ['title' => 'Testimonials', 'icon' => 'fa-quote-left', 'count' => $portfolio->testimonials->count(), 'desc' => 'Recommendations from managers and clients.'],
                                'media' => ['title' => 'Media Appearances', 'icon' => 'fa-tv', 'count' => $portfolio->media->count(), 'desc' => 'Videos, interviews, and op-eds.'],
                                'publications' => ['title' => 'Publications', 'icon' => 'fa-book-open', 'count' => $portfolio->publications->count(), 'desc' => 'Research papers, books, and articles.'],
                                'resume' => ['title' => 'Resume / CV File', 'icon' => 'fa-file-pdf', 'count' => $portfolio->sections->where('type', 'resume')->count(), 'desc' => 'Direct PDF download file for employers.'],
                            ];
                        @endphp

                        @foreach($modulesConfig as $key => $config)
                            <div class="col">
                                <div class="card h-100 module-card p-3 p-md-4 rounded-3 border">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="bg-primary-subtle text-primary p-3 rounded-3 me-3 fs-4"><i class="fa-solid {{ $config['icon'] }}"></i></div>
                                        <div>
                                            <h6 class="card-title mb-0 fw-bold text-dark">{{ $config['title'] }}</h6>
                                            <small class="badge bg-secondary-subtle text-secondary rounded-pill" style="font-size: 0.65rem;">{{ $config['count'] }} items</small>
                                        </div>
                                    </div>
                                    <p class="card-text text-muted small mb-4" style="font-size: 0.78rem;">{{ $config['desc'] }}</p>
                                    <div class="mt-auto d-flex gap-2">
                                        <button type="button" class="btn btn-outline-dark btn-sm flex-grow-1" data-bs-toggle="modal" data-bs-target="#manageModal_{{ $key }}">
                                            <i class="fa-solid fa-gear me-1"></i> Manage
                                        </button>
                                        @if($key !== 'resume')
                                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addModal_{{ $key }}">
                                                <i class="fa-solid fa-plus me-1"></i> Add
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- ========================================== -->
                    <!-- PORTFOLIO CONTENT MODULE MODALS -->
                    <!-- ========================================== -->
                    @foreach($modulesConfig as $key => $config)
                        <!-- ADD MODAL FOR {{ $key }} -->
                        <div class="modal fade" id="addModal_{{ $key }}" tabindex="-1" aria-labelledby="addModal_{{ $key }}Label" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered {{ in_array($key, ['projects', 'experience', 'publications']) ? 'modal-lg' : '' }}">
                                <div class="modal-content border-0 shadow">
                                    <div class="modal-header bg-light py-3">
                                        <h5 class="modal-title fw-bold text-dark" id="addModal_{{ $key }}Label">
                                            <i class="fa-solid {{ $config['icon'] }} text-primary me-2"></i> Add New {{ $config['title'] }} Item
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ $key === 'resume' ? route('portfolio.sections.store') : route('modules.' . $key . '.store') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        @if($key === 'resume')
                                            <input type="hidden" name="type" value="resume">
                                        @endif
                                        <div class="modal-body p-4">
                                            @if($key === 'skills')
                                                 <!-- 1. Category Selection -->
                                                 <div class="mb-3">
                                                     <label class="form-label fw-bold"><i class="fa-solid fa-layer-group text-primary me-1"></i> 1. Select Skill Category <span class="text-danger">*</span></label>
                                                     <select name="category" id="skillCategorySelect" class="form-select select2-category" style="width: 100%;" required>
                                                         <option value="" selected disabled>-- Search & Select Skill Domain Category --</option>
                                                         <option value="Software Development & Engineering">Software Development & Engineering</option>
                                                         <option value="Data Science, AI & Analytics">Data Science, AI & Analytics</option>
                                                         <option value="Cloud, DevOps & IT Infrastructure">Cloud, DevOps & IT Infrastructure</option>
                                                         <option value="UI/UX & Graphic Design">UI/UX & Graphic Design</option>
                                                         <option value="Product & Project Management">Product & Project Management</option>
                                                         <option value="Digital Marketing & Growth">Digital Marketing & Growth</option>
                                                         <option value="Finance, Accounting & Business">Finance, Accounting & Business</option>
                                                         <option value="Operations & Human Resources">Operations & Human Resources</option>
                                                         <option value="Other / General Skills">Other / General Skills</option>
                                                     </select>
                                                     <small class="text-muted d-block mt-1">Select a category first to display skills available for that domain.</small>
                                                 </div>

                                                 <!-- 2. Multi-Select Skills Dropdown -->
                                                 <div class="mb-3">
                                                     <label class="form-label fw-bold"><i class="fa-solid fa-list-check text-primary me-1"></i> 2. Select Skills (Multi-Select Enabled) <span class="text-danger">*</span></label>
                                                     <select name="skills[]" id="skillNameSelect" class="form-select select2-skills" multiple="multiple" style="width: 100%;" data-placeholder="Choose category first above..." disabled required>
                                                     </select>
                                                     <small class="text-muted d-block mt-1">Select multiple skills from the dropdown or type custom skills to add them.</small>
                                                 </div>

                                                 <!-- 3. Proficiency Percentage -->
                                                 <div class="mb-2">
                                                     <label class="form-label fw-bold"><i class="fa-solid fa-gauge-high text-primary me-1"></i> 3. Proficiency Level (0 - 100%) <span class="text-danger">*</span></label>
                                                     <div class="input-group">
                                                         <input type="number" name="percentage" class="form-control" min="0" max="100" value="85" required>
                                                         <span class="input-group-text bg-light fw-bold">%</span>
                                                     </div>
                                                     <small class="text-muted d-block mt-1">Applied as the proficiency score for the selected skill batch (e.g., 85% = Advanced).</small>
                                                 </div>
                                            @elseif($key === 'projects')
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Project Title <span class="text-danger">*</span></label>
                                                    <input type="text" name="title" class="form-control" placeholder="e.g. E-Commerce SaaS Platform" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Project Description</label>
                                                    <textarea name="description" class="form-control" rows="3" placeholder="Key features, stack used, and outcomes..."></textarea>
                                                </div>
                                                <div class="row g-3">
                                                    <div class="col-12 col-md-6">
                                                        <label class="form-label fw-bold">Live Project Link / Demo URL</label>
                                                        <input type="url" name="link" class="form-control" placeholder="https://example.com">
                                                    </div>
                                                    <div class="col-12 col-md-6">
                                                        <label class="form-label fw-bold">Project Image Showcase</label>
                                                        <input type="file" name="image" class="form-control" accept="image/*">
                                                    </div>
                                                </div>
                                            @elseif($key === 'experience')
                                                <div class="row g-3 mb-3">
                                                    <div class="col-12 col-md-6">
                                                        <label class="form-label fw-bold">Company / Organization <span class="text-danger">*</span></label>
                                                        <input type="text" name="company" class="form-control" placeholder="e.g. TechCorp Global" required>
                                                    </div>
                                                    <div class="col-12 col-md-6">
                                                        <label class="form-label fw-bold">Position / Title <span class="text-danger">*</span></label>
                                                        <input type="text" name="position" class="form-control" placeholder="e.g. Senior Software Engineer" required>
                                                    </div>
                                                </div>
                                                <div class="row g-3 mb-3">
                                                    <div class="col-6">
                                                        <label class="form-label fw-bold">Start Date <span class="text-danger">*</span></label>
                                                        <input type="date" name="start_date" class="form-control" required>
                                                    </div>
                                                    <div class="col-6">
                                                        <label class="form-label fw-bold">End Date (Leave blank if present)</label>
                                                        <input type="date" name="end_date" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Key Responsibilities & Highlights</label>
                                                    <textarea name="description" class="form-control" rows="3" placeholder="Described key impacts and duties..."></textarea>
                                                </div>
                                            @elseif($key === 'education')
                                                 <div class="row g-3 mb-3">
                                                     <div class="col-12 col-md-6">
                                                         <label class="form-label fw-bold"><i class="fa-solid fa-graduation-cap text-primary me-1"></i> Degree / Program <span class="text-danger">*</span></label>
                                                         <select name="degree" id="eduDegreeSelect" class="form-select select2-edu-degree" style="width: 100%;" required>
                                                             <option value="" selected disabled>-- Search, Select or Type Degree --</option>
                                                             <option value="B.S. Computer Science">B.S. Computer Science</option>
                                                             <option value="B.S. Software Engineering">B.S. Software Engineering</option>
                                                             <option value="B.S. Information Technology">B.S. Information Technology</option>
                                                             <option value="B.S. Data Science / AI">B.S. Data Science / AI</option>
                                                             <option value="Master of Business Administration (MBA)">Master of Business Administration (MBA)</option>
                                                             <option value="B.B.A. Business Administration">B.B.A. Business Administration</option>
                                                             <option value="M.S. Computer Science">M.S. Computer Science</option>
                                                             <option value="M.S. Software Engineering">M.S. Software Engineering</option>
                                                             <option value="B.E. Electrical Engineering">B.E. Electrical Engineering</option>
                                                             <option value="Ph.D. Computer Science / Engineering">Ph.D. Computer Science / Engineering</option>
                                                             <option value="Associate Degree / Higher Diploma">Associate Degree / Higher Diploma</option>
                                                             <option value="Higher Secondary School Certificate (HSSC / F.Sc)">Higher Secondary School Certificate (HSSC / F.Sc)</option>
                                                             <option value="Secondary School Certificate (Matriculation / O-Levels)">Secondary School Certificate (Matriculation / O-Levels)</option>
                                                             <option value="Professional Certification / License">Professional Certification / License</option>
                                                             @if(isset($existingEducationDegrees) && count($existingEducationDegrees) > 0)
                                                                 @foreach($existingEducationDegrees as $dbDegree)
                                                                     <option value="{{ $dbDegree }}">{{ $dbDegree }}</option>
                                                                 @endforeach
                                                             @endif
                                                         </select>
                                                         <small class="text-muted d-block mt-1">Select an existing degree program or type your custom degree in the box.</small>
                                                     </div>
                                                     <div class="col-12 col-md-6">
                                                         <label class="form-label fw-bold"><i class="fa-solid fa-university text-primary me-1"></i> Institution / University <span class="text-danger">*</span></label>
                                                         <select name="institution" id="eduInstitutionSelect" class="form-select select2-edu-institution" style="width: 100%;" required>
                                                             <option value="" selected disabled>-- Search, Select or Type Institution --</option>
                                                             <option value="National University of Sciences and Technology (NUST)">National University of Sciences and Technology (NUST)</option>
                                                             <option value="FAST National University of Computer and Emerging Sciences">FAST National University of Computer and Emerging Sciences</option>
                                                             <option value="Lahore University of Management Sciences (LUMS)">Lahore University of Management Sciences (LUMS)</option>
                                                             <option value="COMSATS University Islamabad">COMSATS University Islamabad</option>
                                                             <option value="University of the Punjab">University of the Punjab</option>
                                                             <option value="University of Engineering & Technology (UET)">University of Engineering & Technology (UET)</option>
                                                             <option value="Institute of Business Administration (IBA Karachi)">Institute of Business Administration (IBA Karachi)</option>
                                                             <option value="Stanford University">Stanford University</option>
                                                             <option value="Harvard University">Harvard University</option>
                                                             <option value="Massachusetts Institute of Technology (MIT)">Massachusetts Institute of Technology (MIT)</option>
                                                             <option value="University of Oxford">University of Oxford</option>
                                                             <option value="Coursera / Online Platform">Coursera / Online Platform</option>
                                                             @if(isset($existingEducationInstitutions) && count($existingEducationInstitutions) > 0)
                                                                 @foreach($existingEducationInstitutions as $dbInst)
                                                                     <option value="{{ $dbInst }}">{{ $dbInst }}</option>
                                                                 @endforeach
                                                             @endif
                                                         </select>
                                                         <small class="text-muted d-block mt-1">Select your university/institution or type a custom institution name.</small>
                                                     </div>
                                                 </div>
                                                 <div class="row g-3">
                                                     <div class="col-6">
                                                         <label class="form-label fw-bold">Start Date <span class="text-danger">*</span></label>
                                                         <input type="date" name="start_date" class="form-control" required>
                                                     </div>
                                                     <div class="col-6">
                                                         <label class="form-label fw-bold">End Date <span class="text-danger">*</span></label>
                                                         <input type="date" name="end_date" class="form-control" required>
                                                     </div>
                                                 </div>
                                            @elseif($key === 'services')
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Service Title <span class="text-danger">*</span></label>
                                                    <input type="text" name="title" class="form-control" placeholder="e.g. Full-Stack Web Architecture" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Service Description <span class="text-danger">*</span></label>
                                                    <textarea name="description" class="form-control" rows="3" placeholder="What value do you offer to clients or companies?" required></textarea>
                                                </div>
                                            @elseif($key === 'certifications')
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Certification Name <span class="text-danger">*</span></label>
                                                    <input type="text" name="name" class="form-control" placeholder="e.g. AWS Certified Solutions Architect" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Issuing Organization <span class="text-danger">*</span></label>
                                                    <input type="text" name="issuer" class="form-control" placeholder="e.g. Amazon Web Services" required>
                                                </div>
                                            @elseif($key === 'trainings')
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Training / Course Title <span class="text-danger">*</span></label>
                                                    <input type="text" name="title" class="form-control" placeholder="e.g. Executive Leadership Bootcamp" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Organization / Platform <span class="text-danger">*</span></label>
                                                    <input type="text" name="institution" class="form-control" placeholder="e.g. Coursera / Harvard Online" required>
                                                </div>
                                            @elseif($key === 'achievements')
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Achievement / Award Title <span class="text-danger">*</span></label>
                                                    <input type="text" name="title" class="form-control" placeholder="e.g. Best Developer Award 2025" required>
                                                </div>
                                            @elseif($key === 'contributions')
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Contribution Title <span class="text-danger">*</span></label>
                                                    <input type="text" name="title" class="form-control" placeholder="e.g. Open Source Laravel Core Package" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Description / Impact <span class="text-danger">*</span></label>
                                                    <textarea name="description" class="form-control" rows="3" required></textarea>
                                                </div>
                                            @elseif($key === 'testimonials')
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Client / Recommender Name <span class="text-danger">*</span></label>
                                                    <input type="text" name="client_name" class="form-control" placeholder="e.g. Sarah Jenkins, CTO" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Recommendation Content <span class="text-danger">*</span></label>
                                                    <textarea name="content" class="form-control" rows="3" required></textarea>
                                                </div>
                                            @elseif($key === 'media')
                                                <div class="row g-3 mb-3">
                                                    <div class="col-6">
                                                        <label class="form-label fw-bold">Type <span class="text-danger">*</span></label>
                                                        <select name="type" class="form-select" required>
                                                            <option value="tv">TV / Video Interview</option>
                                                            <option value="oped">Newspaper / Op-Ed Article</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-6">
                                                        <label class="form-label fw-bold">Date <span class="text-danger">*</span></label>
                                                        <input type="date" name="date" class="form-control" required>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Appearance Title <span class="text-danger">*</span></label>
                                                    <input type="text" name="title" class="form-control" required>
                                                </div>
                                                <div class="row g-3 mb-3">
                                                    <div class="col-6">
                                                        <label class="form-label fw-bold">TV Channel / Platform</label>
                                                        <input type="text" name="channel_platform" class="form-control">
                                                    </div>
                                                    <div class="col-6">
                                                        <label class="form-label fw-bold">Newspaper / Journal Name</label>
                                                        <input type="text" name="newspaper_name" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Media URL / Link <span class="text-danger">*</span></label>
                                                    <input type="url" name="link" class="form-control" required>
                                                </div>
                                            @elseif($key === 'publications')
                                                <div class="row g-3 mb-3">
                                                    <div class="col-6">
                                                        <label class="form-label fw-bold">Publication Type <span class="text-danger">*</span></label>
                                                        <input type="text" name="type" class="form-control" placeholder="e.g. Research Journal, Book" required>
                                                    </div>
                                                    <div class="col-6">
                                                        <label class="form-label fw-bold">Publication Year <span class="text-danger">*</span></label>
                                                        <input type="text" name="year" class="form-control" placeholder="2025" required>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Title <span class="text-danger">*</span></label>
                                                    <input type="text" name="title" class="form-control" required>
                                                </div>
                                                <div class="row g-3 mb-3">
                                                    <div class="col-6">
                                                        <label class="form-label fw-bold">Authors <span class="text-danger">*</span></label>
                                                        <input type="text" name="authors" class="form-control" required>
                                                    </div>
                                                    <div class="col-6">
                                                        <label class="form-label fw-bold">Publisher <span class="text-danger">*</span></label>
                                                        <input type="text" name="publisher" class="form-control" required>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Publication Link / DOI</label>
                                                    <input type="url" name="link" class="form-control">
                                                </div>
                                            @elseif($key === 'resume')
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Upload Resume / CV (PDF File) <span class="text-danger">*</span></label>
                                                    <input type="file" name="file" class="form-control" accept=".pdf,.doc,.docx" required>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="modal-footer bg-light">
                                            <button type="button" class="btn btn-secondary btn-sm rounded-pill" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary btn-sm rounded-pill px-4 fw-bold">Save {{ $config['title'] }}</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- MANAGE MODAL FOR {{ $key }} -->
                        <div class="modal fade" id="manageModal_{{ $key }}" tabindex="-1" aria-labelledby="manageModal_{{ $key }}Label" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content border-0 shadow">
                                    <div class="modal-header bg-light py-3">
                                        <h5 class="modal-title fw-bold text-dark" id="manageModal_{{ $key }}Label">
                                            <i class="fa-solid {{ $config['icon'] }} text-primary me-2"></i> Manage {{ $config['title'] }} Items
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body p-4">
                                        @if($key === 'skills')
                                            @php
                                                $groupedSkills = $portfolio->skills->groupBy(function($item) {
                                                    return $item->category ?: 'General Skills';
                                                });
                                            @endphp

                                            @forelse($groupedSkills as $catName => $catItems)
                                                @php
                                                    $catIdSlug = Str::slug($catName ?: 'general') . '_' . substr(md5($catName), 0, 6);
                                                    $skillNamesArr = $catItems->pluck('name')->toArray();
                                                    $avgPercentage = round($catItems->avg('percentage'));
                                                @endphp
                                                <div class="card border-0 bg-white p-3 mb-3 rounded-3 border shadow-sm">
                                                    <div class="d-flex justify-content-between align-items-center gap-2">
                                                        <div class="min-w-0 flex-grow-1">
                                                            <div class="d-flex align-items-center gap-2 mb-1">
                                                                <h6 class="fw-bold text-dark mb-0"><i class="fa-solid fa-layer-group text-primary me-1"></i> {{ $catName }}</h6>
                                                                <span class="badge bg-primary-subtle text-primary border border-primary rounded-pill" style="font-size: 0.72rem;">{{ $catItems->count() }} {{ Str::plural('Skill', $catItems->count()) }}</span>
                                                                <span class="badge bg-light text-secondary border rounded-pill" style="font-size: 0.72rem;">Avg {{ $avgPercentage }}%</span>
                                                            </div>

                                                            <!-- Skill Chips Badges -->
                                                            <div class="d-flex flex-wrap gap-1 mt-2">
                                                                @foreach($catItems as $skItem)
                                                                    <span class="badge bg-light text-dark border px-2.5 py-1.5 rounded-pill d-inline-flex align-items-center gap-1" style="font-size: 0.78rem;">
                                                                        <span>{{ $skItem->name }}</span>
                                                                        <small class="text-primary fw-bold">({{ $skItem->percentage }}%)</small>
                                                                        <form action="{{ route('modules.skills.destroy', $skItem->id) }}" method="POST" class="d-inline ms-1" onsubmit="return confirm('Remove skill {{ addslashes($skItem->name) }}?');">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button type="submit" class="btn-close btn-close-xs text-danger p-0" style="font-size: 0.6rem;" title="Remove {{ $skItem->name }}"></button>
                                                                        </form>
                                                                    </span>
                                                                @endforeach
                                                            </div>
                                                        </div>

                                                        <div class="d-flex align-items-center gap-2 flex-shrink-0">
                                                            <button class="btn btn-outline-primary btn-sm rounded-pill py-1 px-3" type="button" data-bs-toggle="collapse" data-bs-target="#editCatCollapse_{{ $catIdSlug }}">
                                                                <i class="fa-solid fa-pen-to-square me-1"></i> Edit Category
                                                            </button>

                                                            <form action="{{ route('modules.skills.category-destroy') }}" method="POST" onsubmit="return confirm('Are you sure you want to delete the entire category \'{{ addslashes($catName) }}\' and all its skills?');">
                                                                @csrf
                                                                <input type="hidden" name="category" value="{{ $catName }}">
                                                                <button type="submit" class="btn btn-outline-danger btn-sm rounded-circle p-0 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="Delete Entire Category">
                                                                    <i class="fa-solid fa-trash-can"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>

                                                    <!-- Edit Category Collapse Form -->
                                                    <div class="collapse mt-3 pt-3 border-top" id="editCatCollapse_{{ $catIdSlug }}">
                                                        <form action="{{ route('modules.skills.category-update') }}" method="POST">
                                                            @csrf
                                                            <input type="hidden" name="old_category" value="{{ $catName }}">

                                                            <div class="mb-3">
                                                                <label class="form-label fw-bold small text-dark">Skill Category Name <span class="text-danger">*</span></label>
                                                                <select name="category" class="form-select form-select-sm edit-cat-select" style="width: 100%;" required>
                                                                    <option value="{{ $catName }}" selected>{{ $catName }}</option>
                                                                    <option value="Software Development & Engineering">Software Development & Engineering</option>
                                                                    <option value="Data Science, AI & Analytics">Data Science, AI & Analytics</option>
                                                                    <option value="Cloud, DevOps & IT Infrastructure">Cloud, DevOps & IT Infrastructure</option>
                                                                    <option value="UI/UX & Graphic Design">UI/UX & Graphic Design</option>
                                                                    <option value="Product & Project Management">Product & Project Management</option>
                                                                    <option value="Digital Marketing & Growth">Digital Marketing & Growth</option>
                                                                    <option value="Finance, Accounting & Business">Finance, Accounting & Business</option>
                                                                    <option value="Operations & Human Resources">Operations & Human Resources</option>
                                                                    <option value="Other / General Skills">Other / General Skills</option>
                                                                </select>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label class="form-label fw-bold small text-dark">Category Skills (Multi-Select / Tagging) <span class="text-danger">*</span></label>
                                                                <select name="skills[]" class="form-select form-select-sm edit-skills-select" multiple="multiple" style="width: 100%;" required>
                                                                    @foreach($skillNamesArr as $existingSkillName)
                                                                        <option value="{{ $existingSkillName }}" selected>{{ $existingSkillName }}</option>
                                                                    @endforeach
                                                                </select>
                                                                <small class="text-muted d-block mt-1" style="font-size: 0.72rem;">Add or remove skills for this category. You can also type custom skills to add them.</small>
                                                            </div>

                                                            <div class="row g-2 mb-3">
                                                                <div class="col-6">
                                                                    <label class="form-label fw-bold small text-dark">Proficiency Percentage (0-100%) <span class="text-danger">*</span></label>
                                                                    <input type="number" name="percentage" class="form-control form-control-sm" value="{{ $avgPercentage }}" min="0" max="100" required>
                                                                </div>
                                                            </div>

                                                            <div class="d-flex justify-content-end gap-2">
                                                                <button type="button" class="btn btn-light btn-sm rounded-pill" data-bs-toggle="collapse" data-bs-target="#editCatCollapse_{{ $catIdSlug }}">Cancel</button>
                                                                <button type="submit" class="btn btn-primary btn-sm rounded-pill px-3 fw-bold">Update Category Skills</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            @empty
                                                <div class="text-center py-4 text-muted">
                                                    <i class="fa-solid fa-code fa-2xl mb-2 text-secondary opacity-50"></i>
                                                    <p class="mb-0">No skill categories added yet. Click "+ Add" to create your first skill category.</p>
                                                </div>
                                            @endforelse
                                        @else
                                            @php
                                                $rel = $key === 'experience' ? 'experiences' : ($key === 'resume' ? 'sections' : $key);
                                                $rawItems = $portfolio->$rel ?? collect();
                                                $items = $key === 'resume' ? $rawItems->where('type', 'resume') : $rawItems;
                                            @endphp

                                            @forelse($items as $item)
                                            <div class="card border-0 bg-light p-3 mb-3 rounded-3 border shadow-sm">
                                                <div class="d-flex justify-content-between align-items-center gap-2">
                                                    <div class="min-w-0 flex-grow-1">
                                                        <h6 class="fw-bold text-dark mb-1">
                                                            {{ $item->name ?? $item->title ?? $item->degree ?? $item->position ?? 'Item #' . $item->id }}
                                                        </h6>
                                                        <small class="text-muted d-block" style="font-size: 0.75rem;">
                                                            @if(isset($item->category)) Category: {{ $item->category }} ({{ $item->percentage }}%) @endif
                                                            @if(isset($item->company)) {{ $item->company }} (@if($item->start_date){{ \Carbon\Carbon::parse($item->start_date)->format('M Y') }} – {{ $item->end_date ? \Carbon\Carbon::parse($item->end_date)->format('M Y') : 'Present' }}@endif) @endif
                                                            @if(isset($item->institution)) {{ $item->institution }} @endif
                                                            @if(isset($item->issuer)) {{ $item->issuer }} @endif
                                                            @if(isset($item->client_name)) {{ $item->client_name }} @endif
                                                        </small>
                                                    </div>

                                                    <div class="d-flex align-items-center gap-2 flex-shrink-0">
                                                        @if($key !== 'resume')
                                                            <button class="btn btn-outline-primary btn-sm rounded-pill py-1 px-3" type="button" data-bs-toggle="collapse" data-bs-target="#editCollapse_{{ $key }}_{{ $item->id }}">
                                                                <i class="fa-solid fa-pen-to-square me-1"></i> Edit
                                                            </button>
                                                        @endif
                                                        <form action="{{ $key === 'resume' ? route('portfolio.sections.destroy', $item->id) : route('modules.' . $key . '.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this item?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-outline-danger btn-sm rounded-circle p-0 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="Delete">
                                                                <i class="fa-solid fa-trash-can"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>

                                                <!-- Inline Edit Form Collapse -->
                                                @if($key !== 'resume')
                                                    <div class="collapse mt-3 pt-3 border-top" id="editCollapse_{{ $key }}_{{ $item->id }}">
                                                        <form action="{{ route('modules.' . $key . '.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                                                            @csrf
                                                            @method('PUT')
                                                            
                                                            @if($key === 'skills')
                                                                <div class="mb-3">
                                                                    <label class="form-label fw-bold small text-dark">Skill Name <span class="text-danger">*</span></label>
                                                                    <input type="text" name="name" class="form-control form-control-sm" value="{{ $item->name }}" required>
                                                                </div>
                                                                <div class="row g-2 mb-3">
                                                                    <div class="col-6">
                                                                        <label class="form-label fw-bold small text-dark">Proficiency Percentage (0-100) <span class="text-danger">*</span></label>
                                                                        <input type="number" name="percentage" class="form-control form-control-sm" value="{{ $item->percentage }}" min="0" max="100" required>
                                                                    </div>
                                                                    <div class="col-6">
                                                                        <label class="form-label fw-bold small text-dark">Category</label>
                                                                        <input type="text" name="category" class="form-control form-control-sm" value="{{ $item->category }}" placeholder="e.g. Backend, Frontend">
                                                                    </div>
                                                                </div>
                                                            @elseif($key === 'projects')
                                                                <div class="mb-3">
                                                                    <label class="form-label fw-bold small text-dark">Project Title <span class="text-danger">*</span></label>
                                                                    <input type="text" name="title" class="form-control form-control-sm" value="{{ $item->title }}" required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label fw-bold small text-dark">Project Description</label>
                                                                    <textarea name="description" class="form-control form-control-sm" rows="3">{{ $item->description }}</textarea>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label fw-bold small text-dark">Live Project Link / Demo URL</label>
                                                                    <input type="url" name="link" class="form-control form-control-sm" value="{{ $item->link }}" placeholder="https://example.com">
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label fw-bold small text-dark">Project Image Showcase</label>
                                                                    @if($item->image_path)
                                                                        <div class="d-flex align-items-center gap-3 mb-2 p-2 border rounded bg-white">
                                                                            <img src="{{ Storage::url($item->image_path) }}" alt="{{ $item->title }}" class="rounded border" style="width: 80px; height: 55px; object-fit: cover;">
                                                                            <div>
                                                                                <span class="badge bg-success-subtle text-success border"><i class="fa-solid fa-image me-1"></i> Current Image Uploaded</span>
                                                                                <small class="text-muted d-block" style="font-size: 0.72rem;">Select a file below to replace the existing showcase image.</small>
                                                                            </div>
                                                                        </div>
                                                                    @else
                                                                        <small class="text-muted d-block mb-1" style="font-size: 0.75rem;">No image uploaded yet for this project.</small>
                                                                    @endif
                                                                    <input type="file" name="image" class="form-control form-control-sm" accept="image/*">
                                                                </div>
                                                            @elseif($key === 'experience')
                                                                <div class="row g-2 mb-3">
                                                                    <div class="col-6">
                                                                        <label class="form-label fw-bold small text-dark">Company / Organization <span class="text-danger">*</span></label>
                                                                        <input type="text" name="company" class="form-control form-control-sm" value="{{ $item->company }}" required>
                                                                    </div>
                                                                    <div class="col-6">
                                                                        <label class="form-label fw-bold small text-dark">Position / Title <span class="text-danger">*</span></label>
                                                                        <input type="text" name="position" class="form-control form-control-sm" value="{{ $item->position }}" required>
                                                                    </div>
                                                                </div>
                                                                <div class="row g-2 mb-3">
                                                                    <div class="col-6">
                                                                        <label class="form-label fw-bold small text-dark">Start Date <span class="text-danger">*</span></label>
                                                                        <input type="date" name="start_date" class="form-control form-control-sm" value="{{ $item->start_date ? \Carbon\Carbon::parse($item->start_date)->format('Y-m-d') : '' }}" required>
                                                                    </div>
                                                                    <div class="col-6">
                                                                        <label class="form-label fw-bold small text-dark">End Date (Leave blank if present)</label>
                                                                        <input type="date" name="end_date" class="form-control form-control-sm" value="{{ $item->end_date ? \Carbon\Carbon::parse($item->end_date)->format('Y-m-d') : '' }}">
                                                                    </div>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label fw-bold small text-dark">Responsibilities / Description</label>
                                                                    <textarea name="description" class="form-control form-control-sm" rows="3">{{ $item->description }}</textarea>
                                                                </div>
                                                            @elseif($key === 'education')
                                                                <div class="row g-2 mb-3">
                                                                    <div class="col-6">
                                                                        <label class="form-label fw-bold small text-dark">Degree / Program <span class="text-danger">*</span></label>
                                                                        <select name="degree" class="form-select form-select-sm edit-edu-degree-select" style="width: 100%;" required>
                                                                             <option value="{{ $item->degree }}" selected>{{ $item->degree }}</option>
                                                                             <option value="B.S. Computer Science">B.S. Computer Science</option>
                                                                             <option value="B.S. Software Engineering">B.S. Software Engineering</option>
                                                                             <option value="B.S. Information Technology">B.S. Information Technology</option>
                                                                             <option value="B.S. Data Science / AI">B.S. Data Science / AI</option>
                                                                             <option value="Master of Business Administration (MBA)">Master of Business Administration (MBA)</option>
                                                                             <option value="B.B.A. Business Administration">B.B.A. Business Administration</option>
                                                                             <option value="M.S. Computer Science">M.S. Computer Science</option>
                                                                             <option value="M.S. Software Engineering">M.S. Software Engineering</option>
                                                                             <option value="B.E. Electrical Engineering">B.E. Electrical Engineering</option>
                                                                             <option value="Ph.D. Computer Science / Engineering">Ph.D. Computer Science / Engineering</option>
                                                                             <option value="Associate Degree / Higher Diploma">Associate Degree / Higher Diploma</option>
                                                                             <option value="Higher Secondary School Certificate (HSSC / F.Sc)">Higher Secondary School Certificate (HSSC / F.Sc)</option>
                                                                             <option value="Secondary School Certificate (Matriculation / O-Levels)">Secondary School Certificate (Matriculation / O-Levels)</option>
                                                                             <option value="Professional Certification / License">Professional Certification / License</option>
                                                                         </select>
                                                                    </div>
                                                                    <div class="col-6">
                                                                        <label class="form-label fw-bold small text-dark"><i class="fa-solid fa-university text-primary me-1"></i> Institution / University <span class="text-danger">*</span></label>
                                                                        <select name="institution" class="form-select form-select-sm edit-edu-institution-select" style="width: 100%;" required>
                                                                            <option value="{{ $item->institution }}" selected>{{ $item->institution }}</option>
                                                                            <option value="National University of Sciences and Technology (NUST)">National University of Sciences and Technology (NUST)</option>
                                                                            <option value="FAST National University of Computer and Emerging Sciences">FAST National University of Computer and Emerging Sciences</option>
                                                                            <option value="Lahore University of Management Sciences (LUMS)">Lahore University of Management Sciences (LUMS)</option>
                                                                            <option value="COMSATS University Islamabad">COMSATS University Islamabad</option>
                                                                            <option value="University of the Punjab">University of the Punjab</option>
                                                                            <option value="University of Engineering & Technology (UET)">University of Engineering & Technology (UET)</option>
                                                                            <option value="Institute of Business Administration (IBA Karachi)">Institute of Business Administration (IBA Karachi)</option>
                                                                            <option value="Stanford University">Stanford University</option>
                                                                            <option value="Harvard University">Harvard University</option>
                                                                            <option value="Massachusetts Institute of Technology (MIT)">Massachusetts Institute of Technology (MIT)</option>
                                                                            <option value="University of Oxford">University of Oxford</option>
                                                                            <option value="Coursera / Online Platform">Coursera / Online Platform</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="row g-2 mb-3">
                                                                    <div class="col-6">
                                                                        <label class="form-label fw-bold small text-dark">Start Date <span class="text-danger">*</span></label>
                                                                        <input type="date" name="start_date" class="form-control form-control-sm" value="{{ $item->start_date ? \Carbon\Carbon::parse($item->start_date)->format('Y-m-d') : '' }}" required>
                                                                    </div>
                                                                    <div class="col-6">
                                                                        <label class="form-label fw-bold small text-dark">End Date <span class="text-danger">*</span></label>
                                                                        <input type="date" name="end_date" class="form-control form-control-sm" value="{{ $item->end_date ? \Carbon\Carbon::parse($item->end_date)->format('Y-m-d') : '' }}" required>
                                                                    </div>
                                                                </div>
                                                            @elseif($key === 'services')
                                                                <div class="mb-3">
                                                                    <label class="form-label fw-bold small text-dark">Service Title <span class="text-danger">*</span></label>
                                                                    <input type="text" name="title" class="form-control form-control-sm" value="{{ $item->title }}" required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label fw-bold small text-dark">Service Description <span class="text-danger">*</span></label>
                                                                    <textarea name="description" class="form-control form-control-sm" rows="3" required>{{ $item->description }}</textarea>
                                                                </div>
                                                            @elseif($key === 'certifications')
                                                                <div class="mb-3">
                                                                    <label class="form-label fw-bold small text-dark">Certification Name <span class="text-danger">*</span></label>
                                                                    <input type="text" name="name" class="form-control form-control-sm" value="{{ $item->name }}" required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label fw-bold small text-dark">Issuing Organization <span class="text-danger">*</span></label>
                                                                    <input type="text" name="issuer" class="form-control form-control-sm" value="{{ $item->issuer }}" required>
                                                                </div>
                                                            @elseif($key === 'trainings')
                                                                <div class="mb-3">
                                                                    <label class="form-label fw-bold small text-dark">Training / Course Title <span class="text-danger">*</span></label>
                                                                    <input type="text" name="title" class="form-control form-control-sm" value="{{ $item->title }}" required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label fw-bold small text-dark">Organization / Platform <span class="text-danger">*</span></label>
                                                                    <input type="text" name="institution" class="form-control form-control-sm" value="{{ $item->institution }}" required>
                                                                </div>
                                                            @elseif($key === 'achievements')
                                                                <div class="mb-3">
                                                                    <label class="form-label fw-bold small text-dark">Achievement / Award Title <span class="text-danger">*</span></label>
                                                                    <input type="text" name="title" class="form-control form-control-sm" value="{{ $item->title }}" required>
                                                                </div>
                                                            @elseif($key === 'contributions')
                                                                <div class="mb-3">
                                                                    <label class="form-label fw-bold small text-dark">Contribution Title <span class="text-danger">*</span></label>
                                                                    <input type="text" name="title" class="form-control form-control-sm" value="{{ $item->title }}" required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label fw-bold small text-dark">Description / Impact <span class="text-danger">*</span></label>
                                                                    <textarea name="description" class="form-control form-control-sm" rows="3" required>{{ $item->description }}</textarea>
                                                                </div>
                                                            @elseif($key === 'testimonials')
                                                                <div class="mb-3">
                                                                    <label class="form-label fw-bold small text-dark">Client / Recommender Name <span class="text-danger">*</span></label>
                                                                    <input type="text" name="client_name" class="form-control form-control-sm" value="{{ $item->client_name }}" required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label fw-bold small text-dark">Recommendation Content <span class="text-danger">*</span></label>
                                                                    <textarea name="content" class="form-control form-control-sm" rows="3" required>{{ $item->content }}</textarea>
                                                                </div>
                                                            @elseif($key === 'media')
                                                                <div class="row g-2 mb-3">
                                                                    <div class="col-6">
                                                                        <label class="form-label fw-bold small text-dark">Media Type <span class="text-danger">*</span></label>
                                                                        <select name="type" class="form-select form-select-sm" required>
                                                                            <option value="tv" {{ $item->type === 'tv' ? 'selected' : '' }}>TV / Video Interview</option>
                                                                            <option value="oped" {{ $item->type === 'oped' ? 'selected' : '' }}>Newspaper / Op-Ed Article</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-6">
                                                                        <label class="form-label fw-bold small text-dark">Date <span class="text-danger">*</span></label>
                                                                        <input type="date" name="date" class="form-control form-control-sm" value="{{ $item->date ? \Carbon\Carbon::parse($item->date)->format('Y-m-d') : '' }}" required>
                                                                    </div>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label fw-bold small text-dark">Appearance Title <span class="text-danger">*</span></label>
                                                                    <input type="text" name="title" class="form-control form-control-sm" value="{{ $item->title }}" required>
                                                                </div>
                                                                <div class="row g-2 mb-3">
                                                                    <div class="col-6">
                                                                        <label class="form-label fw-bold small text-dark">TV Channel / Platform</label>
                                                                        <input type="text" name="channel_platform" class="form-control form-control-sm" value="{{ $item->channel_platform }}">
                                                                    </div>
                                                                    <div class="col-6">
                                                                        <label class="form-label fw-bold small text-dark">Newspaper / Journal Name</label>
                                                                        <input type="text" name="newspaper_name" class="form-control form-control-sm" value="{{ $item->newspaper_name }}">
                                                                    </div>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label fw-bold small text-dark">Media URL / Link <span class="text-danger">*</span></label>
                                                                    <input type="url" name="link" class="form-control form-control-sm" value="{{ $item->link }}" required>
                                                                </div>
                                                            @elseif($key === 'publications')
                                                                <div class="row g-2 mb-3">
                                                                    <div class="col-6">
                                                                        <label class="form-label fw-bold small text-dark">Publication Type <span class="text-danger">*</span></label>
                                                                        <input type="text" name="type" class="form-control form-control-sm" value="{{ $item->type }}" required>
                                                                    </div>
                                                                    <div class="col-6">
                                                                        <label class="form-label fw-bold small text-dark">Publication Year <span class="text-danger">*</span></label>
                                                                        <input type="text" name="year" class="form-control form-control-sm" value="{{ $item->year }}" required>
                                                                    </div>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label fw-bold small text-dark">Title <span class="text-danger">*</span></label>
                                                                    <input type="text" name="title" class="form-control form-control-sm" value="{{ $item->title }}" required>
                                                                </div>
                                                                <div class="row g-2 mb-3">
                                                                    <div class="col-6">
                                                                        <label class="form-label fw-bold small text-dark">Authors <span class="text-danger">*</span></label>
                                                                        <input type="text" name="authors" class="form-control form-control-sm" value="{{ $item->authors }}" required>
                                                                    </div>
                                                                    <div class="col-6">
                                                                        <label class="form-label fw-bold small text-dark">Publisher <span class="text-danger">*</span></label>
                                                                        <input type="text" name="publisher" class="form-control form-control-sm" value="{{ $item->publisher }}" required>
                                                                    </div>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label fw-bold small text-dark">Publication Link / DOI</label>
                                                                    <input type="url" name="link" class="form-control form-control-sm" value="{{ $item->link }}">
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label fw-bold small text-dark">Attach Report / Document</label>
                                                                    @if($item->report_path)
                                                                        <div class="d-flex align-items-center gap-2 mb-2 p-2 border rounded bg-white">
                                                                            <i class="fa-solid fa-file-pdf text-danger"></i>
                                                                            <a href="{{ Storage::url($item->report_path) }}" target="_blank" class="small fw-bold text-primary">View Uploaded Document</a>
                                                                        </div>
                                                                    @endif
                                                                    <input type="file" name="report" class="form-control form-control-sm" accept=".pdf,.doc,.docx,.ppt,.pptx,.zip">
                                                                </div>
                                                            @endif
                                                            
                                                            <div class="d-flex justify-content-end">
                                                                <button type="submit" class="btn btn-success btn-sm rounded-pill px-3 fw-bold">Update Item</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                @endif
                                            </div>
                                        @empty
                                            <div class="text-center py-5 text-muted">
                                                <i class="fa-solid {{ $config['icon'] }} fa-2x mb-2 text-secondary"></i>
                                                <h6 class="fw-bold text-dark">No {{ $config['title'] }} items found</h6>
                                                <p class="small text-muted mb-0">Click the "Add" button to add your first item.</p>
                                            </div>
                                        @endforelse
                                    @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- ========================================== -->
                <!-- 5. JOBS EXPLORER PANE -->
                <!-- ========================================== -->
                <div class="tab-pane fade" id="jobsPane" role="tabpanel">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4 pb-3 border-bottom">
                        <div>
                            <h3 class="fw-bold text-dark mb-0"><i class="fa-solid fa-briefcase text-primary me-2"></i> Jobs Explorer</h3>
                            <p class="text-secondary small mb-0">Browse published opportunities matching your profile.</p>
                        </div>
                        <a href="{{ route('opportunities.index') }}" class="btn btn-primary btn-sm rounded-pill fw-semibold">View Full Job Board &rarr;</a>
                    </div>

                    <div class="row g-3">
                        @forelse($recommendedOpportunities as $job)
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="job-match-card p-3 h-100 d-flex flex-column">
                                    <div class="d-flex justify-content-between align-items-start gap-2 mb-2 w-100" style="min-width: 0;">
                                        <div class="min-w-0 flex-grow-1" style="min-width: 0; max-width: calc(100% - 85px);">
                                            <a href="{{ route('opportunities.show', $job->slug) }}" class="fw-bold text-dark text-decoration-none small text-truncate d-block">{{ $job->title }}</a>
                                            <span class="text-muted small text-truncate d-block">{{ $job->company->name ?? 'Organization' }}</span>
                                        </div>
                                        <span class="match-badge flex-shrink-0"><i class="fa-solid fa-bullseye me-1"></i>{{ $job->match_evaluation['overall_score'] }}% Match</span>
                                    </div>
                                    <div class="mt-auto border-top pt-2 d-flex justify-content-between align-items-center">
                                        <small class="text-muted">{{ $job->created_at->diffForHumans() }}</small>
                                        <a href="{{ route('opportunities.show', $job->slug) }}" class="btn btn-sm btn-primary rounded-pill px-3">View Job</a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center py-5 text-muted">No open opportunities found right now.</div>
                        @endforelse
                    </div>
                </div>

                <!-- ========================================== -->
                <!-- 6. MY APPLICATIONS PANE -->
                <!-- ========================================== -->
                <div class="tab-pane fade" id="applicationsPane" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                        <h3 class="fw-bold text-dark mb-0"><i class="fa-solid fa-paper-plane text-info me-2"></i> My Applications</h3>
                    </div>

                    <div class="card border-0 shadow-sm rounded-3">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Opportunity</th>
                                            <th>Company</th>
                                            <th>Applied Date</th>
                                            <th>Status</th>
                                            <th class="text-end">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($myApplications as $app)
                                            <tr>
                                                <td class="fw-bold text-dark">{{ $app->opportunity->title ?? 'Position' }}</td>
                                                <td>{{ $app->opportunity->company->name ?? 'Company' }}</td>
                                                <td>{{ \Carbon\Carbon::parse($app->created_at)->format('M d, Y') }}</td>
                                                <td><span class="badge bg-primary">{{ ucfirst($app->status) }}</span></td>
                                                <td class="text-end"><a href="{{ route('applications.show', $app->id) }}" class="btn btn-sm btn-outline-primary">View</a></td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="5" class="text-center py-4 text-muted">No submitted applications found.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ========================================== -->
                <!-- 7. SAVED JOBS PANE -->
                <!-- ========================================== -->
                <div class="tab-pane fade" id="savedPane" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                        <h3 class="fw-bold text-dark mb-0"><i class="fa-solid fa-bookmark text-secondary me-2"></i> Saved Opportunities</h3>
                    </div>

                    <div class="row g-3">
                        @forelse($savedOpportunities as $saved)
                            <div class="col-12 col-md-6">
                                <div class="card border p-3 rounded-3 d-flex flex-row align-items-center justify-content-between">
                                    <div>
                                        <h6 class="fw-bold text-dark mb-0">{{ $saved->opportunity->title ?? 'Saved Opportunity' }}</h6>
                                        <small class="text-muted">{{ $saved->opportunity->company->name ?? 'Company' }}</small>
                                    </div>
                                    <a href="{{ route('opportunities.show', $saved->opportunity->slug ?? '#') }}" class="btn btn-sm btn-primary">Apply</a>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center py-5 text-muted">No saved opportunities yet.</div>
                        @endforelse
                    </div>
                </div>

                <!-- ========================================== -->
                <!-- 8. AI MOCK INTERVIEW PANE -->
                <!-- ========================================== -->
                @if(\App\Models\SystemSetting::isAiMockEnabled())
                <div class="tab-pane fade" id="interviewPane" role="tabpanel">
                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4 pb-3 border-bottom">
                        <div>
                            <h3 class="fw-bold text-dark mb-0"><i class="fa-solid fa-robot text-warning me-2"></i> AI Mock Interview Simulator</h3>
                            <p class="text-secondary small mb-0">Practice technical and behavioral interview scenarios.</p>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm rounded-3 p-3 p-md-4 bg-light">
                        <h5 class="fw-bold text-dark mb-2">Start Practice Session</h5>
                        <form action="{{ route('mock-interviews.start') }}" method="POST" class="row g-3">
                            @csrf
                            <div class="col-12 col-md-8">
                                <input type="text" name="job_title" class="form-control" placeholder="Target Job Title (e.g. Senior Laravel Developer)" value="{{ $portfolio->position ?? '' }}" required>
                            </div>
                            <div class="col-12 col-md-4">
                                <button type="submit" class="btn btn-warning w-100 fw-bold text-dark rounded-3"><i class="fa-solid fa-play me-1"></i> Begin AI Interview</button>
                            </div>
                        </form>
                    </div>
                </div>
                @endif

                <!-- ========================================== -->
                <!-- 9. CONNECTIONS & NETWORK PANE -->
                <!-- ========================================== -->
                <div class="tab-pane fade" id="networkPane" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                        <h3 class="fw-bold text-dark mb-0"><i class="fa-solid fa-users text-primary me-2"></i> Connections</h3>
                    </div>
                    <!-- Pending Invitations -->
                    @if($pendingReceived->isNotEmpty())
                        <div class="card border-0 shadow-sm rounded-3 mb-4">
                            <div class="card-header bg-warning bg-opacity-10 py-2 px-3 fw-bold small text-dark d-flex align-items-center justify-content-between">
                                <span><i class="fa-solid fa-user-clock me-1 text-warning"></i> Pending Connection Invitations</span>
                                <span class="badge bg-warning text-dark rounded-pill">{{ $pendingReceived->count() }}</span>
                            </div>
                            <div class="card-body p-0">
                                @foreach($pendingReceived as $conn)
                                    <div class="d-flex align-items-center justify-content-between p-3 border-bottom gap-2">
                                        <div class="d-flex align-items-center gap-2 min-w-0">
                                            @if($conn->sender->portfolio && $conn->sender->portfolio->profile_image)
                                                <img src="{{ Storage::url($conn->sender->portfolio->profile_image) }}" class="rounded-circle border flex-shrink-0" style="width: 38px; height: 38px; object-fit: cover;">
                                            @else
                                                <div class="rounded-circle bg-warning text-dark d-flex align-items-center justify-content-center fw-bold flex-shrink-0" style="width: 38px; height: 38px;">
                                                    {{ strtoupper(substr($conn->sender->name, 0, 1)) }}
                                                </div>
                                            @endif
                                            <div class="min-w-0">
                                                <div class="fw-bold small text-dark text-truncate">{{ $conn->sender->name }}</div>
                                                <small class="text-muted d-block text-truncate" style="font-size: 0.72rem;">Sent request {{ $conn->created_at->diffForHumans() }}</small>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center gap-2 flex-shrink-0">
                                            <form action="{{ route('connections.accept', $conn->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button class="btn btn-sm btn-primary rounded-pill px-3 fw-semibold">Accept</button>
                                            </form>
                                            <form action="{{ route('connections.reject', $conn->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button class="btn btn-sm btn-outline-secondary rounded-pill px-3">Ignore</button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Active Connections Grid -->
                    <div class="row g-3">
                        @forelse($acceptedConnections as $other)
                            <div class="col-12 col-sm-6 col-md-4">
                                <div class="p-3 border rounded-3 bg-white d-flex align-items-center justify-content-between gap-2 shadow-sm position-relative">
                                    <div class="d-flex align-items-center gap-3 flex-grow-1 overflow-hidden" style="min-width: 0;">
                                        @if($other->portfolio && $other->portfolio->profile_image)
                                            <img src="{{ Storage::url($other->portfolio->profile_image) }}" class="rounded-circle border flex-shrink-0" style="width: 44px; height: 44px; object-fit: cover;">
                                        @else
                                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold flex-shrink-0" style="width: 44px; height: 44px; font-size: 1rem;">
                                                {{ strtoupper(substr($other->name, 0, 1)) }}
                                            </div>
                                        @endif
                                        <div class="flex-grow-1 overflow-hidden" style="min-width: 0;">
                                            <strong class="d-block text-dark small text-truncate" title="{{ $other->name }}">{{ $other->name }}</strong>
                                            <small class="text-muted d-block text-truncate" style="font-size: 0.72rem;" title="{{ $other->portfolio->position ?? 'Connected Professional' }}">{{ $other->portfolio->position ?? 'Connected Professional' }}</small>
                                            <a href="{{ route('portfolio.show', $other->username) }}" target="_blank" class="text-primary small text-decoration-none fw-semibold" style="font-size: 0.75rem;">View Profile &rarr;</a>
                                        </div>
                                    </div>

                                    <!-- 3-Dots Ellipsis Dropdown -->
                                    <div class="dropdown flex-shrink-0 position-relative">
                                        <button class="btn btn-sm btn-light border-0 text-secondary rounded-circle p-0 d-flex align-items-center justify-content-center" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="width: 32px; height: 32px;" title="Options">
                                            <i class="fa-solid fa-ellipsis-vertical fs-6"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3" style="font-size: 0.82rem; position: absolute; right: 0; top: 100%; min-width: 175px;">
                                            <li>
                                                <a class="dropdown-item py-2 d-flex align-items-center gap-2" href="{{ route('portfolio.show', $other->username) }}" target="_blank">
                                                    <i class="fa-solid fa-user text-primary" style="width: 18px;"></i> View Portfolio
                                                </a>
                                            </li>
                                            <li>
                                                <form action="{{ route('messages.start', $other->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item py-2 d-flex align-items-center gap-2 text-primary">
                                                        <i class="fa-solid fa-comments text-primary" style="width: 18px;"></i> Direct Chat
                                                    </button>
                                                </form>
                                            </li>
                                            <li>
                                                <button class="dropdown-item py-2 d-flex align-items-center gap-2 text-secondary" onclick="navigator.clipboard.writeText('{{ route('portfolio.show', $other->username) }}'); alert('Portfolio link copied to clipboard!');">
                                                    <i class="fa-solid fa-copy" style="width: 18px;"></i> Copy Profile Link
                                                </button>
                                            </li>
                                            <li><hr class="dropdown-divider my-1"></li>
                                            <li>
                                                <form action="{{ route('connections.remove', $other->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove {{ addslashes($other->name) }} from your connections?');">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item py-2 text-danger d-flex align-items-center gap-2">
                                                        <i class="fa-solid fa-user-xmark" style="width: 18px;"></i> Remove Connection
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center py-5 text-muted">
                                <i class="fa-solid fa-users-slash fa-2x mb-2 text-muted"></i>
                                <h6 class="fw-bold text-dark">No connections yet</h6>
                                <p class="small text-muted mb-0">Discover and connect with professionals on the platform.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- ========================================== -->
                <!-- 10. INBOX / MESSAGES PANE -->
                <!-- ========================================== -->
                <div class="tab-pane fade" id="inboxPane" role="tabpanel">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4 pb-3 border-bottom">
                        <div>
                            <h3 class="fw-bold text-dark mb-0"><i class="fa-solid fa-envelope text-primary me-2"></i> Communications & Messages</h3>
                            <p class="text-secondary small mb-0">Separated channels for Registered Platform Users Direct Messaging and Public Visitor Portfolio Contact Inquiries.</p>
                        </div>
                        <a href="{{ route('messages.index') }}" class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm fw-bold">
                            <i class="fa-solid fa-comments me-1"></i> Open Direct Chat Workspace
                        </a>
                    </div>

                    <!-- Navigation Sub-Tabs -->
                    <ul class="nav nav-pills mb-4 gap-2" id="communicationSubTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active rounded-pill px-3 py-2 fw-semibold" id="direct-messages-subtab" data-bs-toggle="pill" data-bs-target="#directMessagesSubPane" type="button" role="tab">
                                <i class="fa-solid fa-comments me-1"></i> Platform User Chat
                                @if($unreadDirectMessagesCount > 0)
                                    <span class="badge bg-danger rounded-pill ms-1">{{ $unreadDirectMessagesCount }}</span>
                                @endif
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-pill px-3 py-2 fw-semibold" id="visitor-inquiries-subtab" data-bs-toggle="pill" data-bs-target="#visitorInquiriesSubPane" type="button" role="tab">
                                <i class="fa-solid fa-address-card me-1"></i> Visitor Contact Submissions
                                @php $unreadInq = $portfolio->messages->where('is_read', false)->count(); @endphp
                                @if($unreadInq > 0)
                                    <span class="badge bg-warning text-dark rounded-pill ms-1">{{ $unreadInq }}</span>
                                @endif
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="communicationSubTabContent">
                        <!-- Sub-Pane 1: Platform User Direct Messages -->
                        <div class="tab-pane fade show active" id="directMessagesSubPane" role="tabpanel">
                            <div class="card border-0 shadow-sm rounded-3">
                                <div class="card-header bg-white py-3 px-4 d-flex justify-content-between align-items-center">
                                    <h6 class="fw-bold text-dark mb-0"><i class="fa-solid fa-user-check text-primary me-2"></i> Registered Platform User Conversations</h6>
                                    <a href="{{ route('messages.index') }}" class="btn btn-outline-primary btn-sm rounded-pill">Open Workspace &rarr;</a>
                                </div>
                                <div class="card-body p-0">
                                    <div class="list-group list-group-flush">
                                        @forelse($userConversations as $conv)
                                            @php
                                                $otherUser = $conv->getOtherUser(Auth::id());
                                                $unreadCount = $conv->unreadCountFor(Auth::id());
                                                $latestMsg = $conv->latestMessage;
                                            @endphp
                                            <a href="{{ route('messages.index', ['conversation' => $conv->id]) }}" class="list-group-item list-group-item-action p-3 d-flex align-items-center justify-content-between gap-3">
                                                <div class="d-flex align-items-center gap-3 min-w-0 flex-grow-1">
                                                    @if($otherUser && $otherUser->portfolio && $otherUser->portfolio->profile_image)
                                                        <img src="{{ Storage::url($otherUser->portfolio->profile_image) }}" class="rounded-circle border flex-shrink-0" style="width: 44px; height: 44px; object-fit: cover;">
                                                    @else
                                                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold flex-shrink-0" style="width: 44px; height: 44px;">
                                                            {{ strtoupper(substr($otherUser->name ?? 'U', 0, 1)) }}
                                                        </div>
                                                    @endif
                                                    <div class="min-w-0 flex-grow-1">
                                                        <div class="d-flex align-items-center justify-content-between mb-1">
                                                            <strong class="text-dark small text-truncate">{{ $otherUser->name ?? 'User' }}</strong>
                                                            @if($conv->last_message_at)
                                                                <small class="text-muted ms-2 flex-shrink-0" style="font-size: 0.7rem;">{{ $conv->last_message_at->diffForHumans() }}</small>
                                                            @endif
                                                        </div>
                                                        <p class="text-muted mb-0 small text-truncate">
                                                            @if($latestMsg)
                                                                {{ $latestMsg->sender_id === Auth::id() ? 'You: ' : '' }}{{ $latestMsg->body }}
                                                            @else
                                                                <em>No messages exchanged yet</em>
                                                            @endif
                                                        </p>
                                                    </div>
                                                </div>
                                                @if($unreadCount > 0)
                                                    <span class="badge bg-danger rounded-pill flex-shrink-0">{{ $unreadCount }} new</span>
                                                @endif
                                            </a>
                                        @empty
                                            <div class="text-center py-5 text-muted">
                                                <i class="fa-solid fa-comments-slash fa-2x mb-2 text-secondary"></i>
                                                <h6 class="fw-bold text-dark">No direct conversations yet</h6>
                                                <p class="small text-muted mb-0">Start a chat conversation with any registered portfolio user.</p>
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sub-Pane 2: Public Visitor Portfolio Contact Inquiries -->
                        <div class="tab-pane fade" id="visitorInquiriesSubPane" role="tabpanel">
                            <div class="card border-0 shadow-sm rounded-3">
                                <div class="card-header bg-white py-3 px-4">
                                    <h6 class="fw-bold text-dark mb-0"><i class="fa-solid fa-address-book text-success me-2"></i> Public Visitor Contact Form Submissions</h6>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table align-middle mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Visitor Name</th>
                                                    <th>Email Address</th>
                                                    <th>Message Content</th>
                                                    <th>Submitted</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($portfolio->messages->sortByDesc('created_at') as $msg)
                                                    <tr>
                                                        <td class="fw-bold text-dark">{{ $msg->name }}</td>
                                                        <td>{{ $msg->email }}</td>
                                                        <td><small class="text-muted">{{ Str::limit($msg->message, 90) }}</small></td>
                                                        <td><small class="text-muted">{{ $msg->created_at->diffForHumans() }}</small></td>
                                                    </tr>
                                                @empty
                                                    <tr><td colspan="4" class="text-center py-4 text-muted">No public visitor contact form messages received yet.</td></tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                function initSummernote() {
                    if (typeof $ !== 'undefined' && $.fn.summernote) {
                        $('.js-summernote').each(function() {
                            if (!$(this).next('.note-editor').length) {
                                var h = $(this).data('height') || 140;
                                $(this).summernote({
                                    height: h,
                                    toolbar: [
                                        ['style', ['style', 'bold', 'italic', 'underline', 'clear']],
                                        ['font', ['strikethrough']],
                                        ['para', ['ul', 'ol', 'paragraph']],
                                        ['insert', ['link', 'hr']],
                                        ['view', ['codeview', 'undo', 'redo']]
                                    ]
                                });
                            }
                        });
                    }
                }

                initSummernote();

                // Save active tab on user tab switch & re-init Summernote
                document.querySelectorAll('[data-bs-toggle="tab"]').forEach(function(tabBtn) {
                    tabBtn.addEventListener('shown.bs.tab', function (e) {
                        setTimeout(initSummernote, 50);
                        var targetPane = e.target.getAttribute('data-bs-target');
                        if (targetPane) {
                            try { localStorage.setItem('myresume_active_tab', targetPane); } catch(err){}
                            if (history.replaceState) {
                                history.replaceState(null, null, targetPane);
                            }
                        }
                    });
                });

                // Auto switch tab based on session active_tab, URL hash, or localStorage
                var sessionActiveTab = "{{ session('active_tab') }}";
                
                function activateTab(paneId) {
                    if (!paneId) return false;
                    var targetPane = paneId.startsWith('#') ? paneId : '#' + paneId;
                    var targetBtn = document.querySelector('[data-bs-target="' + targetPane + '"]');
                    if (targetBtn) {
                        var tabInstance = bootstrap.Tab.getOrCreateInstance(targetBtn);
                        tabInstance.show();
                        
                        document.querySelectorAll('#careerWorkspaceTabs [data-bs-toggle="tab"]').forEach(function(b) {
                            if (b.getAttribute('data-bs-target') === targetPane) {
                                b.classList.add('active');
                            } else {
                                b.classList.remove('active');
                            }
                        });
                        return true;
                    }
                    return false;
                }

                function checkHashTab() {
                    if (sessionActiveTab && activateTab(sessionActiveTab)) {
                        try { localStorage.setItem('myresume_active_tab', sessionActiveTab); } catch(e){}
                        return;
                    }
                    var hash = window.location.hash;
                    if (hash && activateTab(hash)) {
                        try { localStorage.setItem('myresume_active_tab', hash); } catch(e){}
                        return;
                    }
                    var storedTab = localStorage.getItem('myresume_active_tab');
                    if (storedTab && activateTab(storedTab)) {
                        return;
                    }
                }
                checkHashTab();
                window.addEventListener('hashchange', checkHashTab);

                // Mobile Sidebar Drawer Navigation Sync
                document.querySelectorAll('#mobileSidebarDrawer [data-bs-toggle="tab"]').forEach(function(btn) {
                    btn.addEventListener('click', function() {
                        var targetId = this.getAttribute('data-bs-target');
                        activateTab(targetId);
                    });
                });
            });
        </script>
        
        <!-- jQuery & Select2 JS -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                if (typeof $ !== 'undefined') {
                    const dbSkillsGrouped = @json($existingSkillsGrouped ?? []);

                    const skillsDictionary = {
                        'Software Development & Engineering': [
                            'PHP', 'Laravel', 'JavaScript', 'TypeScript', 'Node.js', 'Python', 'Django',
                            'React.js', 'Vue.js', 'Angular', 'HTML5 / CSS3', 'Bootstrap 5', 'Tailwind CSS',
                            'MySQL', 'PostgreSQL', 'MongoDB', 'REST APIs', 'GraphQL', 'Docker',
                            'Git & GitHub', 'Java', 'C# / .NET', 'C++', 'Ruby on Rails', 'Swift (iOS)',
                            'Kotlin (Android)', 'Flutter', 'React Native', 'Microservices', 'Unit Testing / Pest'
                        ],
                        'Data Science, AI & Analytics': [
                            'Python Data Analysis', 'Pandas & NumPy', 'Machine Learning', 'Deep Learning',
                            'TensorFlow / PyTorch', 'SQL & Data Warehousing', 'Power BI', 'Tableau',
                            'Data Engineering', 'Big Data & Spark', 'Scikit-Learn', 'NLP (Natural Language Processing)',
                            'Computer Vision', 'Business Intelligence', 'Data Mining', 'Statistical Analysis'
                        ],
                        'Cloud, DevOps & IT Infrastructure': [
                            'Amazon Web Services (AWS)', 'Microsoft Azure', 'Google Cloud Platform (GCP)',
                            'Linux / Unix Administration', 'Kubernetes', 'CI/CD Pipelines', 'Terraform',
                            'Cybersecurity & Pen Testing', 'Network Engineering', 'Nginx / Apache',
                            'Shell Scripting (Bash)', 'Ansible', 'System Architecture'
                        ],
                        'UI/UX & Graphic Design': [
                            'Figma', 'Adobe XD', 'UI/UX Research', 'Wireframing & Prototyping',
                            'Design Systems', 'Adobe Photoshop', 'Adobe Illustrator', 'User Journey Mapping',
                            'Product Design', 'Interaction Design', 'Usability Testing', 'Responsive Web Design'
                        ],
                        'Product & Project Management': [
                            'Agile & Scrum', 'Jira & Confluence', 'Product Roadmap Creation',
                            'Stakeholder Management', 'Sprint Planning', 'Risk Management', 'Kanban',
                            'Asana / Trello', 'PRD Documentation', 'Scrum Master', 'PMP Standards'
                        ],
                        'Digital Marketing & Growth': [
                            'Search Engine Optimization (SEO)', 'Google Analytics 4', 'Social Media Marketing',
                            'Content Strategy', 'Pay-Per-Click (PPC)', 'Email Marketing', 'Copywriting',
                            'Brand Strategy', 'Conversion Rate Optimization (CRO)', 'Affiliate Marketing'
                        ],
                        'Finance, Accounting & Business': [
                            'Financial Modeling', 'Accounting & Bookkeeping', 'QuickBooks / Xero',
                            'Financial Analysis', 'Taxation & Compliance', 'Auditing', 'Budgeting & Forecasting',
                            'Business Valuation', 'Excel & Advanced Formulas', 'Corporate Finance'
                        ],
                        'Operations & Human Resources': [
                            'Talent Acquisition & ATS', 'HR Management', 'Performance Management',
                            'Operations Management', 'Supply Chain & Logistics', 'Customer Relationship Management (CRM)',
                            'Salesforce', 'Business Development', 'Vendor Management', 'Public Speaking'
                        ],
                        'Other / General Skills': [
                            'Communication Skills', 'Leadership & Team Management', 'Critical Thinking',
                            'Problem Solving', 'Time Management', 'Negotiation', 'Technical Writing'
                        ]
                    };

                    // Merge dynamic database categories & skills into skillsDictionary
                    if (dbSkillsGrouped && typeof dbSkillsGrouped === 'object') {
                        for (const [cat, skills] of Object.entries(dbSkillsGrouped)) {
                            if (cat) {
                                if (!skillsDictionary[cat]) {
                                    skillsDictionary[cat] = [];
                                }
                                if (Array.isArray(skills)) {
                                    skills.forEach(function(s) {
                                        if (s && !skillsDictionary[cat].includes(s)) {
                                            skillsDictionary[cat].push(s);
                                        }
                                    });
                                }
                            }
                        }
                    }

                    function initSelect2Skills() {
                        var $catSelect = $('#skillCategorySelect');
                        var $skillSelect = $('#skillNameSelect');

                        if ($catSelect.length && typeof $.fn.select2 !== 'undefined') {
                            // Populate any missing categories dynamically into the dropdown options
                            for (const catName of Object.keys(skillsDictionary)) {
                                if ($catSelect.find("option[value='" + catName.replace(/'/g, "\\'") + "']").length === 0) {
                                    $catSelect.append(new Option(catName, catName, false, false));
                                }
                            }

                            $catSelect.select2({
                                theme: 'bootstrap-5',
                                dropdownParent: $('#addModal_skills'),
                                placeholder: '-- Search, Select or Type Custom Category --',
                                tags: true,
                                allowClear: true
                            });

                            $skillSelect.select2({
                                theme: 'bootstrap-5',
                                dropdownParent: $('#addModal_skills'),
                                placeholder: 'Choose or type category first above...',
                                tags: true,
                                allowClear: true
                            });

                            $catSelect.on('change', function() {
                                var selectedCategory = $(this).val();
                                $skillSelect.empty();

                                if (selectedCategory) {
                                    if (!skillsDictionary[selectedCategory]) {
                                        skillsDictionary[selectedCategory] = [];
                                    }
                                    var availableSkills = skillsDictionary[selectedCategory];
                                    $.each(availableSkills, function(index, skillName) {
                                        $skillSelect.append(new Option(skillName, skillName, false, false));
                                    });
                                    $skillSelect.prop('disabled', false).trigger('change');
                                } else {
                                    $skillSelect.prop('disabled', true).trigger('change');
                                }
                            });
                        }
                    }

                    $('#addModal_skills').on('shown.bs.modal', function () {
                        initSelect2Skills();
                    });

                    $('#manageModal_skills').on('shown.bs.modal', function () {
                        if (typeof $.fn.select2 !== 'undefined') {
                            $('.edit-cat-select').select2({
                                theme: 'bootstrap-5',
                                dropdownParent: $('#manageModal_skills'),
                                tags: true
                            });
                            $('.edit-skills-select').select2({
                                theme: 'bootstrap-5',
                                dropdownParent: $('#manageModal_skills'),
                                tags: true
                            });
                        }
                    });

                    function initSelect2Education() {
                        var $degSelect = $('#eduDegreeSelect');
                        var $instSelect = $('#eduInstitutionSelect');

                        if (typeof $.fn.select2 !== 'undefined') {
                            if ($degSelect.length) {
                                $degSelect.select2({
                                    theme: 'bootstrap-5',
                                    dropdownParent: $('#addModal_education'),
                                    placeholder: '-- Search, Select or Type Degree --',
                                    tags: true,
                                    allowClear: true
                                });
                            }
                            if ($instSelect.length) {
                                $instSelect.select2({
                                    theme: 'bootstrap-5',
                                    dropdownParent: $('#addModal_education'),
                                    placeholder: '-- Search, Select or Type Institution --',
                                    tags: true,
                                    allowClear: true
                                });
                            }
                        }
                    }

                    $('#addModal_education').on('shown.bs.modal', function () {
                        initSelect2Education();
                    });

                    $('#manageModal_education').on('shown.bs.modal', function () {
                        if (typeof $.fn.select2 !== 'undefined') {
                            $('.edit-edu-degree-select').select2({
                                theme: 'bootstrap-5',
                                dropdownParent: $('#manageModal_education'),
                                tags: true
                            });
                            $('.edit-edu-institution-select').select2({
                                theme: 'bootstrap-5',
                                dropdownParent: $('#manageModal_education'),
                                tags: true
                            });
                        }
                    });
                }
            });
        </script>
    @endpush
</x-app-layout>

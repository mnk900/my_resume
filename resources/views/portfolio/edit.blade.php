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

            #sidebarMenu {
                background-color: #222d32 !important;
                width: 260px;
                min-width: 260px;
            }

            @media (min-width: 768px) {
                #sidebarMenu {
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

            /* Module Grid Cards */
            .module-card {
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }

            .module-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1) !important;
            }

            .hover-lift {
                transition: transform 0.2s, box-shadow 0.2s;
            }

            .hover-lift:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1) !important;
            }

            .note-editor.note-frame {
                border-color: #dee2e6;
            }
        </style>
    @endpush

    <!-- Mobile Dashboard Header (Visible only on mobile) -->
    <div class="bg-white border-bottom p-2 d-md-none d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center">
            <button class="btn btn-outline-secondary me-2 py-1 px-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu">
                <i class="bi bi-list fs-5"></i>
            </button>
            <span class="fw-bold">Elite Portfolio CMS</span>
        </div>
        <div class="small-badge text-success small"><i class="bi bi-circle-fill me-1" style="font-size: 0.5rem;"></i> Active</div>
    </div>

    <div class="d-flex flex-column flex-md-row" id="dashboard-wrapper">
        <!-- Sidebar Navigation (Responsive Offcanvas) -->
        <div class="offcanvas-md offcanvas-start bg-dark text-white p-3 d-flex flex-column" tabindex="-1" id="sidebarMenu" aria-labelledby="sidebarMenuLabel" style="width: 260px; min-width: 260px;">
            <div class="offcanvas-header d-md-none border-bottom mb-3">
                <h5 class="offcanvas-title text-white fw-bold" id="sidebarMenuLabel">Menu Navigation</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" data-bs-target="#sidebarMenu" aria-label="Close"></button>
            </div>
            <div class="brand-link text-center mb-4 pb-3 border-bottom text-white text-decoration-none d-none d-md-block">
                <span class="brand-text fw-bold fs-5">Elite Portfolio CMS</span>
            </div>
            <div class="user-panel d-flex align-items-center mb-4 pb-3 border-bottom">
                <div class="image me-3">
                    @if($portfolio->profile_image)
                        <img src="{{ Storage::url($portfolio->profile_image) }}" class="rounded-circle shadow-sm" style="width: 40px; height: 40px; object-fit: cover; border: 2px solid rgba(255,255,255,0.2);">
                    @else
                        <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white fw-bold" style="width: 40px; height: 40px;">
                            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                        </div>
                    @endif
                </div>
                <div class="info">
                    <a href="#" class="d-block text-white text-decoration-none fw-semibold text-truncate" style="max-width: 160px;">{{ Auth::user()->name }}</a>
                    <span class="text-success small d-block mb-1"><i class="bi bi-circle-fill me-1" style="font-size: 0.6rem;"></i> Active</span>
                    <a href="{{ route('portfolio.show', Auth::user()->username) }}" target="_blank" class="text-info small text-decoration-none d-flex align-items-center gap-1">
                        <i class="bi bi-box-arrow-up-right"></i> Live Portfolio
                    </a>
                </div>
            </div>

            <!-- Sidebar Menu Links mapping to Tab Panes -->
            <ul class="nav nav-pills flex-column mb-auto sidebar-menu" id="dashboardTabs" role="tablist">
                <li class="nav-item mb-2">
                    <button class="nav-link active text-start text-white w-100 d-flex align-items-center gap-2 border-0 bg-transparent py-2 px-3" id="dashboard-tab" data-bs-toggle="tab" data-bs-target="#dashboardPane" type="button" role="tab">
                        <i class="bi bi-speedometer2"></i> <span>Dashboard</span>
                    </button>
                </li>
                <li class="nav-item mb-2">
                    <button class="nav-link text-start text-white w-100 d-flex align-items-center gap-2 border-0 bg-transparent py-2 px-3" id="modules-tab" data-bs-toggle="tab" data-bs-target="#modulesPane" type="button" role="tab">
                        <i class="bi bi-grid-3x3-gap"></i> <span>Modules / Sections</span>
                    </button>
                </li>
                <li class="nav-item mb-2">
                    <button class="nav-link text-start text-white w-100 d-flex align-items-center gap-2 border-0 bg-transparent py-2 px-3" id="settings-tab" data-bs-toggle="tab" data-bs-target="#settingsPane" type="button" role="tab">
                        <i class="bi bi-gear"></i> <span>Settings</span>
                    </button>
                </li>
                <li class="nav-item mb-2">
                    <button class="nav-link text-start text-white w-100 d-flex align-items-center gap-2 border-0 bg-transparent py-2 px-3" id="inbox-tab" data-bs-toggle="tab" data-bs-target="#inboxPane" type="button" role="tab">
                        <i class="bi bi-envelope"></i> <span>Inbox / Messages</span>
                        @php $unread = $portfolio->messages->where('is_read', false)->count(); @endphp
                        @if($unread > 0)
                            <span class="badge bg-danger rounded-pill ms-auto">{{ $unread }}</span>
                        @endif
                    </button>
                </li>
                <li class="nav-item mb-2">
                    <button class="nav-link text-start text-white w-100 d-flex align-items-center gap-2 border-0 bg-transparent py-2 px-3" id="connections-tab" data-bs-toggle="tab" data-bs-target="#connectionsPane" type="button" role="tab">
                        <i class="bi bi-people"></i> <span>Connections</span>
                        @if($pendingReceived->count() > 0)
                            <span class="badge bg-warning text-dark rounded-pill ms-auto">{{ $pendingReceived->count() }}</span>
                        @endif
                    </button>
                </li>
            </ul>
        </div>

        <!-- Right Content Wrapper -->
        <div class="flex-grow-1 p-4 bg-light overflow-auto" id="content-pane-wrapper">
            @if (session('status'))
                <div class="alert alert-success alert-dismissible shadow-sm border-0 fade show mb-4" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    {{ str_replace('-', ' ', ucfirst(session('status'))) }} successfully.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible shadow-sm border-0 fade show mb-4" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <strong class="d-block mb-1">Please fix the following errors:</strong>
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="tab-content" id="dashboardTabsContent">
                <!-- 1. DASHBOARD TAB PANE -->
                <div class="tab-pane fade show active" id="dashboardPane" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3 class="fw-bold mb-0">Overview Dashboard</h3>
                        <a href="{{ route('portfolio.show', Auth::user()->username) }}" target="_blank" class="btn btn-outline-primary btn-sm shadow-sm d-flex align-items-center gap-1 fw-bold">
                            <i class="bi bi-eye"></i> View Live Portfolio
                        </a>
                    </div>

                    <!-- Small Infographics widgets -->
                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-5 g-3 mb-4">
                        <div class="col">
                            <div class="small-box" style="background: linear-gradient(135deg, #1e3a8a, #3b82f6);">
                                <div class="inner">
                                    <h3>{{ $portfolio->projects->count() }}</h3>
                                    <p>Projects</p>
                                </div>
                                <div class="icon"><i class="bi bi-laptop"></i></div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="small-box" style="background: linear-gradient(135deg, #065f46, #10b981);">
                                <div class="inner">
                                    <h3>{{ $portfolio->experiences->count() }}</h3>
                                    <p>Work Records</p>
                                </div>
                                <div class="icon"><i class="bi bi-briefcase"></i></div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="small-box" style="background: linear-gradient(135deg, #0f766e, #14b8a6);">
                                <div class="inner">
                                    <h3>{{ $portfolio->services->count() }}</h3>
                                    <p>Services Offered</p>
                                </div>
                                <div class="icon"><i class="bi bi-gear-fill"></i></div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="small-box" style="background: linear-gradient(135deg, #7c2d12, #f97316);">
                                <div class="inner">
                                    <h3>{{ $portfolio->certifications->count() + $portfolio->publications->count() }}</h3>
                                    <p>Certs & Pubs</p>
                                </div>
                                <div class="icon"><i class="bi bi-award"></i></div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="small-box" style="background: linear-gradient(135deg, #991b1b, #ef4444);">
                                <div class="inner">
                                    <h3>{{ $portfolio->messages->count() }}</h3>
                                    <p>Inquiries</p>
                                </div>
                                <div class="icon"><i class="bi bi-envelope"></i></div>
                            </div>
                        </div>
                    </div>

                    <!-- Dashboard Alerts & Notifications / Recent Inbox Messages -->
                    <div class="card border-0 shadow-sm rounded-3 mb-4">
                        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-bold"><i class="bi bi-bell me-2 text-warning"></i>Recent Public Inquiries & Messages</h5>
                            <button onclick="document.getElementById('inbox-tab').click();" class="btn btn-sm btn-outline-primary">Open Inbox</button>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Sender Name</th>
                                            <th>Email Address</th>
                                            <th>Message Snippet</th>
                                            <th>Status</th>
                                            <th>Date Received</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($portfolio->messages->sortByDesc('created_at')->take(5) as $msg)
                                            <tr>
                                                <td class="fw-bold">{{ $msg->name }}</td>
                                                <td>{{ $msg->email }}</td>
                                                <td><small class="text-muted">{{ Str::limit($msg->message, 80) }}</small></td>
                                                <td>
                                                    <span class="badge rounded-pill bg-{{ $msg->is_read ? 'secondary' : 'danger' }}">
                                                        {{ $msg->is_read ? 'Read' : 'New' }}
                                                    </span>
                                                </td>
                                                <td>{{ $msg->created_at->diffForHumans() }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center py-4 text-muted">No messages received yet.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. MODULES / SECTIONS TAB PANE -->
                <div class="tab-pane fade" id="modulesPane" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3 class="fw-bold mb-0">Portfolio Sections & Modules</h3>
                    </div>

                    <!-- Grid of 13 cards (12 modules + CV) -->
                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3" id="modulesGrid">
                        @php
                            $modulesConfig = [
                                'skills' => ['title' => 'Skills', 'icon' => 'bi-gear-fill', 'count' => $portfolio->skills->count(), 'desc' => 'Add technical expertise, bars & categories.'],
                                'projects' => ['title' => 'Projects', 'icon' => 'bi-laptop', 'count' => $portfolio->projects->count(), 'desc' => 'Flagship projects, descriptions, taglines.'],
                                'experience' => ['title' => 'Work Experience', 'icon' => 'bi-briefcase', 'count' => $portfolio->experiences->count(), 'desc' => 'Job history, roles, responsibilities.'],
                                'education' => ['title' => 'Education', 'icon' => 'bi-book', 'count' => $portfolio->education->count(), 'desc' => 'Academic records, degrees, institutions.'],
                                'services' => ['title' => 'Services Offered', 'icon' => 'bi-window-stack', 'count' => $portfolio->services->count(), 'desc' => 'Freelance / consultancy service listings.'],
                                'certifications' => ['title' => 'Certifications', 'icon' => 'bi-patch-check', 'count' => $portfolio->certifications->count(), 'desc' => 'Industry licenses and certifications.'],
                                'trainings' => ['title' => 'Trainings', 'icon' => 'bi-person-workspace', 'count' => $portfolio->trainings->count(), 'desc' => 'Seminars, bootcamps and workshops.'],
                                'achievements' => ['title' => 'Achievements', 'icon' => 'bi-trophy', 'count' => $portfolio->achievements->count(), 'desc' => 'Awards, honors, and soft skills.'],
                                'contributions' => ['title' => 'Contributions', 'icon' => 'bi-github', 'count' => $portfolio->contributions->count(), 'desc' => 'Open-source or social contribution links.'],
                                'testimonials' => ['title' => 'Testimonials', 'icon' => 'bi-chat-quote', 'count' => $portfolio->testimonials->count(), 'desc' => 'Recommendations from clients/managers.'],
                                'media' => ['title' => 'Media Appearances', 'icon' => 'bi-tv', 'count' => $portfolio->media->count(), 'desc' => 'TV shows, channel links, newspaper op-eds.'],
                                'publications' => ['title' => 'Publications', 'icon' => 'bi-journal-text', 'count' => $portfolio->publications->count(), 'desc' => 'Books, research papers, journal articles.'],
                                'resume' => ['title' => 'Resume / CV File', 'icon' => 'bi-file-earmark-pdf', 'count' => $portfolio->sections->where('type', 'resume')->count(), 'desc' => 'Upload direct PDF version of CV document.'],
                            ];
                        @endphp

                        @foreach($modulesConfig as $key => $config)
                            <div class="col">
                                <div class="card h-100 shadow-sm border-0 module-card hover-lift">
                                    <div class="card-body d-flex flex-column p-4">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="bg-primary-subtle text-primary p-3 rounded-3 me-3 fs-3"><i class="bi {{ $config['icon'] }}"></i></div>
                                            <div>
                                                <h5 class="card-title mb-0 fw-bold">{{ $config['title'] }}</h5>
                                                <small class="badge bg-secondary-subtle text-secondary rounded-pill">{{ $config['count'] }} records</small>
                                            </div>
                                        </div>
                                        <p class="card-text text-secondary small mb-4">{{ $config['desc'] }}</p>
                                        <div class="mt-auto d-flex gap-2">
                                            <button class="btn btn-outline-dark btn-sm flex-grow-1 js-manage-section" data-module="{{ $key }}">Manage</button>
                                            @if($key !== 'resume')
                                                <button class="btn btn-dark btn-sm js-add-section-btn" data-module="{{ $key }}"><i class="bi bi-plus-lg"></i> Add</button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Dynamic Details Panel Container (Switched via JS) -->
                    <div id="modulesDetailPanel" style="display: none;">
                        <button class="btn btn-sm btn-outline-dark mb-4" id="backToModulesBtn"><i class="bi bi-arrow-left"></i> Back to Sections Grid</button>
                        
                        <!-- 2.1 SKILLS DETAIL VIEW -->
                        <div class="module-detail-wrapper" id="moduleDetail-skills" style="display: none;">
                            <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                                <h4 class="fw-bold mb-0">Manage Skills</h4>
                                <button class="btn btn-primary btn-sm" data-bs-toggle="collapse" data-bs-target="#addSkillsCollapse"><i class="bi bi-plus-lg"></i> Add New Skill</button>
                            </div>
                            
                            <div class="collapse mb-4" id="addSkillsCollapse">
                                <form action="{{ route('modules.skills.store') }}" method="POST" class="p-3 bg-white rounded border shadow-sm">
                                    @csrf
                                    <div class="row g-2 align-items-end">
                                        <div class="col-md-3"><label class="small fw-bold">Skill Name</label><input name="name" class="form-control form-control-sm" placeholder="Skill Name" required></div>
                                        <div class="col-md-2"><label class="small fw-bold">Proficiency (%)</label><input name="percentage" type="number" class="form-control form-control-sm" placeholder="%" required></div>
                                        <div class="col-md-3"><label class="small fw-bold">Category</label><input name="category" class="form-control form-control-sm" placeholder="Category (e.g. Programming)" required></div>
                                        <div class="col-md-3">
                                            <label class="small fw-bold">Icon Class</label>
                                            <select name="icon" class="form-select form-select-sm">
                                                <option value="code">Code</option>
                                                <option value="layer-group">Layers</option>
                                                <option value="database">Database</option>
                                                <option value="tools">Tools</option>
                                                <option value="laptop-code">Laptop</option>
                                                <option value="server">Server</option>
                                                <option value="mobile-alt">Mobile</option>
                                                <option value="palette">Design</option>
                                                <option value="terminal">Terminal</option>
                                            </select>
                                        </div>
                                        <div class="col-md-1"><button class="btn btn-sm btn-dark w-100">Add</button></div>
                                    </div>
                                </form>
                            </div>

                            <div class="card shadow-sm border-0 mb-3 bg-white py-2 px-3">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="input-group input-group-sm" style="max-width: 250px;">
                                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                                        <input type="text" class="form-control js-module-search border-start-0" placeholder="Filter skills...">
                                    </div>
                                    <div class="ms-auto js-module-pagination"></div>
                                </div>
                            </div>

                            <div class="js-module-list">
                                @foreach($portfolio->skills as $skill)
                                    <div class="js-module-item bg-white border-bottom p-3">
                                        <div class="d-flex justify-content-between align-items-start gap-2">
                                            <div>
                                                <span class="fw-bold">{{ $skill->name }}</span>
                                                <span class="badge bg-secondary-subtle text-secondary ms-2">{{ $skill->category }} ({{ $skill->percentage }}%)</span>
                                            </div>
                                            <div class="d-flex gap-2">
                                                <button class="btn btn-sm btn-outline-secondary py-0" type="button" data-bs-toggle="collapse" data-bs-target="#editSkill{{ $skill->id }}">Edit</button>
                                                <form action="{{ route('modules.skills.destroy', $skill) }}" method="POST">
                                                    @csrf @method('delete')
                                                    <button class="btn btn-sm btn-link text-danger py-0 p-0 text-decoration-none">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                        <form action="{{ route('modules.skills.update', $skill) }}" method="POST" class="collapse mt-2 p-3 bg-light rounded border" id="editSkill{{ $skill->id }}">
                                            @csrf @method('PATCH')
                                            <div class="row g-2 align-items-end">
                                                <div class="col-md-3"><input name="name" class="form-control form-control-sm" value="{{ $skill->name }}" required></div>
                                                <div class="col-md-2"><input name="percentage" type="number" class="form-control form-control-sm" value="{{ $skill->percentage }}" required></div>
                                                <div class="col-md-3"><input name="category" class="form-control form-control-sm" value="{{ $skill->category }}" required></div>
                                                <div class="col-md-3">
                                                    <select name="icon" class="form-select form-select-sm">
                                                        <option value="code" {{ $skill->icon == 'code' ? 'selected' : '' }}>Code</option>
                                                        <option value="layer-group" {{ $skill->icon == 'layer-group' ? 'selected' : '' }}>Layers</option>
                                                        <option value="database" {{ $skill->icon == 'database' ? 'selected' : '' }}>Database</option>
                                                        <option value="tools" {{ $skill->icon == 'tools' ? 'selected' : '' }}>Tools</option>
                                                        <option value="laptop-code" {{ $skill->icon == 'laptop-code' ? 'selected' : '' }}>Laptop</option>
                                                        <option value="server" {{ $skill->icon == 'server' ? 'selected' : '' }}>Server</option>
                                                        <option value="mobile-alt" {{ $skill->icon == 'mobile-alt' ? 'selected' : '' }}>Mobile</option>
                                                        <option value="palette" {{ $skill->icon == 'palette' ? 'selected' : '' }}>Design</option>
                                                        <option value="terminal" {{ $skill->icon == 'terminal' ? 'selected' : '' }}>Terminal</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-1"><button class="btn btn-sm btn-primary w-100">Update</button></div>
                                            </div>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- 2.2 PROJECTS DETAIL VIEW -->
                        <div class="module-detail-wrapper" id="moduleDetail-projects" style="display: none;">
                            <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                                <h4 class="fw-bold mb-0">Manage Projects</h4>
                                <button class="btn btn-primary btn-sm" data-bs-toggle="collapse" data-bs-target="#addProjectsCollapse"><i class="bi bi-plus-lg"></i> Add Project</button>
                            </div>
                            
                            <div class="collapse mb-4" id="addProjectsCollapse">
                                <form action="{{ route('modules.projects.store') }}" method="POST" enctype="multipart/form-data" class="p-3 bg-white rounded border shadow-sm">
                                    @csrf
                                    <div class="row g-2 mb-2">
                                        <div class="col-md-6">
                                            <label class="small fw-bold">Project Title</label>
                                            <input name="title" class="form-control form-control-sm" placeholder="Project Title" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="small fw-bold">Project Link</label>
                                            <input name="link" class="form-control form-control-sm" placeholder="Project Link">
                                        </div>
                                    </div>
                                    <div class="mb-2">
                                        <label class="small fw-bold">Showcase Image</label>
                                        <input type="file" name="image" class="form-control form-control-sm" accept="image/*">
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label class="small fw-bold">Project Description</label>
                                        <textarea name="description" class="form-control js-summernote" data-height="150" placeholder="Project details..."></textarea>
                                    </div>
                                    <button class="btn btn-sm btn-dark w-100">Add Project</button>
                                </form>
                            </div>

                            <div class="card shadow-sm border-0 mb-3 bg-white py-2 px-3">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="input-group input-group-sm" style="max-width: 250px;">
                                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                                        <input type="text" class="form-control js-module-search border-start-0" placeholder="Filter projects...">
                                    </div>
                                    <div class="ms-auto js-module-pagination"></div>
                                </div>
                            </div>

                            <div class="js-module-list">
                                @foreach($portfolio->projects as $project)
                                    <div class="js-module-item bg-white border-bottom p-3">
                                        <div class="d-flex justify-content-between align-items-start gap-2">
                                            <div class="d-flex gap-3 align-items-start">
                                                @if($project->image_path)
                                                    <img src="{{ Storage::url($project->image_path) }}" class="rounded shadow-sm border" style="width: 60px; height: 60px; object-fit: cover;">
                                                @else
                                                    <div class="rounded border bg-light d-flex align-items-center justify-content-center text-muted" style="width: 60px; height: 60px; font-size: 0.75rem;">No Image</div>
                                                @endif
                                                <div>
                                                    <div class="fw-bold">{{ $project->title }}</div>
                                                    @if($project->link)
                                                        <small class="text-primary d-block mb-1"><a href="{{ $project->link }}" target="_blank" class="text-decoration-none"><i class="bi bi-link-45deg"></i> {{ $project->link }}</a></small>
                                                    @endif
                                                    <small class="text-muted d-block">{{ Str::limit(strip_tags($project->description), 80) }}</small>
                                                </div>
                                            </div>
                                            <div class="d-flex gap-2">
                                                <button class="btn btn-sm btn-outline-secondary py-0" type="button" data-bs-toggle="collapse" data-bs-target="#editProject{{ $project->id }}">Edit</button>
                                                <form action="{{ route('modules.projects.destroy', $project) }}" method="POST">
                                                    @csrf @method('delete')
                                                    <button class="btn btn-sm btn-link text-danger py-0 p-0 text-decoration-none">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                        <form action="{{ route('modules.projects.update', $project) }}" method="POST" enctype="multipart/form-data" class="collapse mt-2 p-3 bg-light rounded border" id="editProject{{ $project->id }}">
                                            @csrf @method('PATCH')
                                            <div class="row g-2 mb-2">
                                                <div class="col-md-6">
                                                    <label class="small fw-bold">Project Title</label>
                                                    <input name="title" class="form-control form-control-sm" value="{{ $project->title }}" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="small fw-bold">Project Link</label>
                                                    <input name="link" class="form-control form-control-sm" value="{{ $project->link }}">
                                                </div>
                                            </div>
                                            <div class="mb-2">
                                                @if($project->image_path)
                                                    <div class="mb-2">
                                                        <img src="{{ Storage::url($project->image_path) }}" class="rounded shadow-sm border" style="max-height: 80px; width: auto; object-fit: cover;">
                                                    </div>
                                                @endif
                                                <label class="small fw-bold">Update Showcase Image</label>
                                                <input type="file" name="image" class="form-control form-control-sm" accept="image/*">
                                            </div>
                                            <div class="col-12 mb-3">
                                                <label class="small fw-bold">Project Description</label>
                                                <textarea name="description" class="form-control form-control-sm js-summernote" data-height="140">{{ $project->description }}</textarea>
                                            </div>
                                            <button class="btn btn-sm btn-primary">Update Project</button>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- 2.3 WORK EXPERIENCE DETAIL VIEW -->
                        <div class="module-detail-wrapper" id="moduleDetail-experience" style="display: none;">
                            <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                                <h4 class="fw-bold mb-0">Manage Work Experience</h4>
                                <button class="btn btn-primary btn-sm" data-bs-toggle="collapse" data-bs-target="#addExpCollapse"><i class="bi bi-plus-lg"></i> Add Experience</button>
                            </div>
                            
                            <div class="collapse mb-4" id="addExpCollapse">
                                <form action="{{ route('modules.experiences.store') }}" method="POST" class="p-3 bg-white rounded border shadow-sm">
                                    @csrf
                                    <div class="row g-2 mb-2">
                                        <div class="col-md-6"><input name="position" class="form-control form-control-sm" placeholder="Job Title" required></div>
                                        <div class="col-md-6"><input name="company" class="form-control form-control-sm" placeholder="Company Name" required></div>
                                        <div class="col-md-6"><label class="small fw-bold">Start Date</label><input name="start_date" type="date" class="form-control form-control-sm" required></div>
                                        <div class="col-md-6"><label class="small fw-bold">End Date (Keep empty if current)</label><input name="end_date" type="date" class="form-control form-control-sm"></div>
                                    </div>
                                    <div class="col-12 mb-3"><textarea name="description" class="form-control js-summernote" data-height="180" placeholder="Job Responsibilities"></textarea></div>
                                    <button class="btn btn-sm btn-dark w-100">Add Experience</button>
                                </form>
                            </div>

                            <div class="card shadow-sm border-0 mb-3 bg-white py-2 px-3">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="input-group input-group-sm" style="max-width: 250px;">
                                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                                        <input type="text" class="form-control js-module-search border-start-0" placeholder="Filter experience...">
                                    </div>
                                    <div class="ms-auto js-module-pagination"></div>
                                </div>
                            </div>

                            <div class="js-module-list">
                                @foreach($portfolio->experiences as $exp)
                                    <div class="js-module-item bg-white border-bottom p-3">
                                        <div class="d-flex justify-content-between align-items-start gap-2">
                                            <div>
                                                <div class="fw-bold">{{ $exp->position }} at {{ $exp->company }}</div>
                                                <small class="text-muted d-block mb-2">{{ $exp->start_date->format('M Y') }} - {{ $exp->end_date ? $exp->end_date->format('M Y') : 'Present' }}</small>
                                            </div>
                                            <div class="d-flex gap-2">
                                                <button class="btn btn-sm btn-outline-secondary py-0" type="button" data-bs-toggle="collapse" data-bs-target="#editExp{{ $exp->id }}">Edit</button>
                                                <form action="{{ route('modules.experiences.destroy', $exp) }}" method="POST">
                                                    @csrf @method('delete')
                                                    <button class="btn btn-sm btn-link text-danger py-0 p-0 text-decoration-none">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                        <form action="{{ route('modules.experiences.update', $exp) }}" method="POST" class="collapse mt-2 p-3 bg-light rounded border" id="editExp{{ $exp->id }}">
                                            @csrf @method('PATCH')
                                            <div class="row g-2 mb-2">
                                                <div class="col-md-6"><input name="position" class="form-control form-control-sm" value="{{ $exp->position }}" required></div>
                                                <div class="col-md-6"><input name="company" class="form-control form-control-sm" value="{{ $exp->company }}" required></div>
                                                <div class="col-md-6"><input name="start_date" type="date" class="form-control form-control-sm" value="{{ $exp->start_date?->format('Y-m-d') }}" required></div>
                                                <div class="col-md-6"><input name="end_date" type="date" class="form-control form-control-sm" value="{{ $exp->end_date?->format('Y-m-d') }}"></div>
                                            </div>
                                            <div class="col-12 mb-2"><textarea name="description" class="form-control form-control-sm js-summernote" data-height="150">{{ $exp->description }}</textarea></div>
                                            <button class="btn btn-sm btn-primary">Update Experience</button>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- 2.4 EDUCATION DETAIL VIEW -->
                        <div class="module-detail-wrapper" id="moduleDetail-education" style="display: none;">
                            <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                                <h4 class="fw-bold mb-0">Manage Education</h4>
                                <button class="btn btn-primary btn-sm" data-bs-toggle="collapse" data-bs-target="#addEduCollapse"><i class="bi bi-plus-lg"></i> Add Education</button>
                            </div>
                            
                            <div class="collapse mb-4" id="addEduCollapse">
                                <form action="{{ route('modules.education.store') }}" method="POST" class="p-3 bg-white rounded border shadow-sm">
                                    @csrf
                                    <div class="row g-3">
                                        <div class="col-md-6"><input name="institution" class="form-control form-control-sm" placeholder="University/School" required></div>
                                        <div class="col-md-6"><input name="degree" class="form-control form-control-sm" placeholder="Degree" required></div>
                                        <div class="col-md-6"><label class="small fw-bold">Start Date</label><input name="start_date" type="date" class="form-control form-control-sm" required></div>
                                        <div class="col-md-6"><label class="small fw-bold">End Date</label><input name="end_date" type="date" class="form-control form-control-sm" required></div>
                                        <div class="col-12 text-end"><button class="btn btn-sm btn-dark">Add Education</button></div>
                                    </div>
                                </form>
                            </div>

                            <div class="card shadow-sm border-0 mb-3 bg-white py-2 px-3">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="input-group input-group-sm" style="max-width: 250px;">
                                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                                        <input type="text" class="form-control js-module-search border-start-0" placeholder="Filter education...">
                                    </div>
                                    <div class="ms-auto js-module-pagination"></div>
                                </div>
                            </div>

                            <div class="js-module-list">
                                @foreach($portfolio->education as $edu)
                                    <div class="js-module-item bg-white border-bottom p-3">
                                        <div class="d-flex justify-content-between align-items-start gap-2">
                                            <div>
                                                <div class="fw-bold">{{ $edu->degree }}</div>
                                                <small class="text-muted d-block mb-1">{{ $edu->institution }} | {{ $edu->start_date?->format('Y') }} - {{ $edu->end_date?->format('Y') }}</small>
                                            </div>
                                            <div class="d-flex gap-2">
                                                <button class="btn btn-sm btn-outline-secondary py-0" type="button" data-bs-toggle="collapse" data-bs-target="#editEdu{{ $edu->id }}">Edit</button>
                                                <form action="{{ route('modules.education.destroy', $edu) }}" method="POST">
                                                    @csrf @method('delete')
                                                    <button class="btn btn-sm btn-link text-danger py-0 p-0 text-decoration-none">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                        <form action="{{ route('modules.education.update', $edu) }}" method="POST" class="collapse mt-2 p-3 bg-light rounded border" id="editEdu{{ $edu->id }}">
                                            @csrf @method('PATCH')
                                            <div class="row g-2">
                                                <div class="col-md-6"><input name="institution" class="form-control form-control-sm" value="{{ $edu->institution }}" required></div>
                                                <div class="col-md-6"><input name="degree" class="form-control form-control-sm" value="{{ $edu->degree }}" required></div>
                                                <div class="col-md-6"><input name="start_date" type="date" class="form-control form-control-sm" value="{{ $edu->start_date?->format('Y-m-d') }}" required></div>
                                                <div class="col-md-6"><input name="end_date" type="date" class="form-control form-control-sm" value="{{ $edu->end_date?->format('Y-m-d') }}" required></div>
                                                <div class="col-12 text-end"><button class="btn btn-sm btn-primary">Update Education</button></div>
                                            </div>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- 2.5 SERVICES DETAIL VIEW -->
                        <div class="module-detail-wrapper" id="moduleDetail-services" style="display: none;">
                            <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                                <h4 class="fw-bold mb-0">Manage Services</h4>
                                <button class="btn btn-primary btn-sm" data-bs-toggle="collapse" data-bs-target="#addServicesCollapse"><i class="bi bi-plus-lg"></i> Add Service</button>
                            </div>
                            
                            <div class="collapse mb-4" id="addServicesCollapse">
                                <form action="{{ route('modules.services.store') }}" method="POST" class="p-3 bg-white rounded border shadow-sm">
                                    @csrf
                                    <input name="title" class="form-control mb-2 form-control-sm" placeholder="Service Name" required>
                                    <textarea name="description" class="form-control mb-2 js-summernote" data-height="150" placeholder="What you offer..."></textarea>
                                    <button class="btn btn-sm btn-dark w-100">Add Service</button>
                                </form>
                            </div>

                            <div class="card shadow-sm border-0 mb-3 bg-white py-2 px-3">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="input-group input-group-sm" style="max-width: 250px;">
                                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                                        <input type="text" class="form-control js-module-search border-start-0" placeholder="Filter services...">
                                    </div>
                                    <div class="ms-auto js-module-pagination"></div>
                                </div>
                            </div>

                            <div class="js-module-list">
                                @foreach($portfolio->services as $serv)
                                    <div class="js-module-item bg-white border-bottom p-3">
                                        <div class="d-flex justify-content-between align-items-start gap-2">
                                            <div>
                                                <div class="fw-bold">{{ $serv->title }}</div>
                                                <small class="text-muted d-block mb-1">{{ Str::limit(strip_tags($serv->description), 80) }}</small>
                                            </div>
                                            <div class="d-flex gap-2">
                                                <button class="btn btn-sm btn-outline-secondary py-0" type="button" data-bs-toggle="collapse" data-bs-target="#editService{{ $serv->id }}">Edit</button>
                                                <form action="{{ route('modules.services.destroy', $serv) }}" method="POST">
                                                    @csrf @method('delete')
                                                    <button class="btn btn-sm btn-link text-danger py-0 p-0 text-decoration-none">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                        <form action="{{ route('modules.services.update', $serv) }}" method="POST" class="collapse mt-2 p-3 bg-light rounded border" id="editService{{ $serv->id }}">
                                            @csrf @method('PATCH')
                                            <input name="title" class="form-control form-control-sm mb-2" value="{{ $serv->title }}" required>
                                            <textarea name="description" class="form-control form-control-sm mb-2 js-summernote" data-height="130">{{ $serv->description }}</textarea>
                                            <button class="btn btn-sm btn-primary">Update Service</button>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- 2.6 CERTIFICATIONS DETAIL VIEW -->
                        <div class="module-detail-wrapper" id="moduleDetail-certifications" style="display: none;">
                            <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                                <h4 class="fw-bold mb-0">Manage Certifications</h4>
                                <button class="btn btn-primary btn-sm" data-bs-toggle="collapse" data-bs-target="#addCertsCollapse"><i class="bi bi-plus-lg"></i> Add Certification</button>
                            </div>
                            
                            <div class="collapse mb-4" id="addCertsCollapse">
                                <form action="{{ route('modules.certifications.store') }}" method="POST" class="p-3 bg-white rounded border shadow-sm">
                                    @csrf
                                    <input name="name" class="form-control mb-2 form-control-sm" placeholder="Certification Name" required>
                                    <input name="issuer" class="form-control mb-2 form-control-sm" placeholder="Issuing Body" required>
                                    <button class="btn btn-sm btn-dark w-100">Add Certification</button>
                                </form>
                            </div>

                            <div class="card shadow-sm border-0 mb-3 bg-white py-2 px-3">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="input-group input-group-sm" style="max-width: 250px;">
                                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                                        <input type="text" class="form-control js-module-search border-start-0" placeholder="Filter certifications...">
                                    </div>
                                    <div class="ms-auto js-module-pagination"></div>
                                </div>
                            </div>

                            <div class="js-module-list">
                                @foreach($portfolio->certifications as $cert)
                                    <div class="js-module-item bg-white border-bottom p-3">
                                        <div class="d-flex justify-content-between align-items-start gap-2">
                                            <div>
                                                <div class="fw-bold">{{ $cert->name }}</div>
                                                <small class="text-muted d-block mb-1">Issuer: {{ $cert->issuer }}</small>
                                            </div>
                                            <div class="d-flex gap-2">
                                                <button class="btn btn-sm btn-outline-secondary py-0" type="button" data-bs-toggle="collapse" data-bs-target="#editCert{{ $cert->id }}">Edit</button>
                                                <form action="{{ route('modules.certifications.destroy', $cert) }}" method="POST">
                                                    @csrf @method('delete')
                                                    <button class="btn btn-sm btn-link text-danger py-0 p-0 text-decoration-none">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                        <form action="{{ route('modules.certifications.update', $cert) }}" method="POST" class="collapse mt-2 p-3 bg-light rounded border" id="editCert{{ $cert->id }}">
                                            @csrf @method('PATCH')
                                            <input name="name" class="form-control form-control-sm mb-2" value="{{ $cert->name }}" required>
                                            <input name="issuer" class="form-control form-control-sm mb-2" value="{{ $cert->issuer }}" required>
                                            <button class="btn btn-sm btn-primary">Update Certification</button>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- 2.7 TRAININGS DETAIL VIEW -->
                        <div class="module-detail-wrapper" id="moduleDetail-trainings" style="display: none;">
                            <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                                <h4 class="fw-bold mb-0">Manage Trainings</h4>
                                <button class="btn btn-primary btn-sm" data-bs-toggle="collapse" data-bs-target="#addTrainCollapse"><i class="bi bi-plus-lg"></i> Add Training</button>
                            </div>
                            
                            <div class="collapse mb-4" id="addTrainCollapse">
                                <form action="{{ route('modules.trainings.store') }}" method="POST" class="p-3 bg-white rounded border shadow-sm">
                                    @csrf
                                    <input name="title" class="form-control mb-2 form-control-sm" placeholder="Training Title" required>
                                    <input name="institution" class="form-control mb-2 form-control-sm" placeholder="Institution" required>
                                    <button class="btn btn-sm btn-dark w-100">Add Training</button>
                                </form>
                            </div>

                            <div class="card shadow-sm border-0 mb-3 bg-white py-2 px-3">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="input-group input-group-sm" style="max-width: 250px;">
                                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                                        <input type="text" class="form-control js-module-search border-start-0" placeholder="Filter trainings...">
                                    </div>
                                    <div class="ms-auto js-module-pagination"></div>
                                </div>
                            </div>

                            <div class="js-module-list">
                                @foreach($portfolio->trainings as $train)
                                    <div class="js-module-item bg-white border-bottom p-3">
                                        <div class="d-flex justify-content-between align-items-start gap-2">
                                            <div>
                                                <div class="fw-bold">{{ $train->title }}</div>
                                                <small class="text-muted d-block mb-1">Institution: {{ $train->institution }}</small>
                                            </div>
                                            <div class="d-flex gap-2">
                                                <button class="btn btn-sm btn-outline-secondary py-0" type="button" data-bs-toggle="collapse" data-bs-target="#editTrain{{ $train->id }}">Edit</button>
                                                <form action="{{ route('modules.trainings.destroy', $train) }}" method="POST">
                                                    @csrf @method('delete')
                                                    <button class="btn btn-sm btn-link text-danger py-0 p-0 text-decoration-none">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                        <form action="{{ route('modules.trainings.update', $train) }}" method="POST" class="collapse mt-2 p-3 bg-light rounded border" id="editTrain{{ $train->id }}">
                                            @csrf @method('PATCH')
                                            <input name="title" class="form-control form-control-sm mb-2" value="{{ $train->title }}" required>
                                            <input name="institution" class="form-control form-control-sm mb-2" value="{{ $train->institution }}" required>
                                            <button class="btn btn-sm btn-primary">Update Training</button>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- 2.8 ACHIEVEMENTS DETAIL VIEW -->
                        <div class="module-detail-wrapper" id="moduleDetail-achievements" style="display: none;">
                            <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                                <h4 class="fw-bold mb-0">Manage Achievements</h4>
                                <button class="btn btn-primary btn-sm" data-bs-toggle="collapse" data-bs-target="#addAchCollapse"><i class="bi bi-plus-lg"></i> Add Achievement</button>
                            </div>
                            
                            <div class="collapse mb-4" id="addAchCollapse">
                                <form action="{{ route('modules.achievements.store') }}" method="POST" class="p-3 bg-white rounded border shadow-sm">
                                    @csrf
                                    <input name="title" class="form-control mb-2 form-control-sm" placeholder="Award / Achievement / Skill title" required>
                                    <button class="btn btn-sm btn-dark w-100">Add Achievement</button>
                                </form>
                            </div>

                            <div class="card shadow-sm border-0 mb-3 bg-white py-2 px-3">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="input-group input-group-sm" style="max-width: 250px;">
                                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                                        <input type="text" class="form-control js-module-search border-start-0" placeholder="Filter achievements...">
                                    </div>
                                    <div class="ms-auto js-module-pagination"></div>
                                </div>
                            </div>

                            <div class="js-module-list">
                                @foreach($portfolio->achievements as $ach)
                                    <div class="js-module-item bg-white border-bottom p-3">
                                        <div class="d-flex justify-content-between align-items-start gap-2">
                                            <div class="fw-bold">{{ $ach->title }}</div>
                                            <div class="d-flex gap-2">
                                                <button class="btn btn-sm btn-outline-secondary py-0" type="button" data-bs-toggle="collapse" data-bs-target="#editAch{{ $ach->id }}">Edit</button>
                                                <form action="{{ route('modules.achievements.destroy', $ach) }}" method="POST">
                                                    @csrf @method('delete')
                                                    <button class="btn btn-sm btn-link text-danger py-0 p-0 text-decoration-none">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                        <form action="{{ route('modules.achievements.update', $ach) }}" method="POST" class="collapse mt-2 p-3 bg-light rounded border" id="editAch{{ $ach->id }}">
                                            @csrf @method('PATCH')
                                            <input name="title" class="form-control form-control-sm mb-2" value="{{ $ach->title }}" required>
                                            <button class="btn btn-sm btn-primary">Update Achievement</button>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- 2.9 CONTRIBUTIONS DETAIL VIEW -->
                        <div class="module-detail-wrapper" id="moduleDetail-contributions" style="display: none;">
                            <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                                <h4 class="fw-bold mb-0">Manage Contributions</h4>
                                <button class="btn btn-primary btn-sm" data-bs-toggle="collapse" data-bs-target="#addContribCollapse"><i class="bi bi-plus-lg"></i> Add Contribution</button>
                            </div>
                            
                            <div class="collapse mb-4" id="addContribCollapse">
                                <form action="{{ route('modules.contributions.store') }}" method="POST" class="p-3 bg-white rounded border shadow-sm">
                                    @csrf
                                    <input name="title" class="form-control mb-2 form-control-sm" placeholder="Contribution Title" required>
                                    <textarea name="description" class="form-control mb-2 js-summernote" data-height="150" rows="2" placeholder="Describe your contribution..."></textarea>
                                    <button class="btn btn-sm btn-dark w-100">Add Contribution</button>
                                </form>
                            </div>

                            <div class="card shadow-sm border-0 mb-3 bg-white py-2 px-3">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="input-group input-group-sm" style="max-width: 250px;">
                                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                                        <input type="text" class="form-control js-module-search border-start-0" placeholder="Filter contributions...">
                                    </div>
                                    <div class="ms-auto js-module-pagination"></div>
                                </div>
                            </div>

                            <div class="js-module-list">
                                @foreach($portfolio->contributions as $contrib)
                                    <div class="js-module-item bg-white border-bottom p-3">
                                        <div class="d-flex justify-content-between align-items-start gap-2">
                                            <div>
                                                <div class="fw-bold">{{ $contrib->title }}</div>
                                                <small class="text-muted d-block mb-1">{{ Str::limit(strip_tags($contrib->description), 85) }}</small>
                                            </div>
                                            <div class="d-flex gap-2">
                                                <button class="btn btn-sm btn-outline-secondary py-0" type="button" data-bs-toggle="collapse" data-bs-target="#editContrib{{ $contrib->id }}">Edit</button>
                                                <form action="{{ route('modules.contributions.destroy', $contrib) }}" method="POST">
                                                    @csrf @method('delete')
                                                    <button class="btn btn-sm btn-link text-danger py-0 p-0 text-decoration-none">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                        <form action="{{ route('modules.contributions.update', $contrib) }}" method="POST" class="collapse mt-2 p-3 bg-light rounded border" id="editContrib{{ $contrib->id }}">
                                            @csrf @method('PATCH')
                                            <input name="title" class="form-control form-control-sm mb-2" value="{{ $contrib->title }}" required>
                                            <textarea name="description" class="form-control form-control-sm mb-2 js-summernote" data-height="130">{{ $contrib->description }}</textarea>
                                            <button class="btn btn-sm btn-primary">Update Contribution</button>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- 2.10 TESTIMONIALS DETAIL VIEW -->
                        <div class="module-detail-wrapper" id="moduleDetail-testimonials" style="display: none;">
                            <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                                <h4 class="fw-bold mb-0">Manage Testimonials</h4>
                                <button class="btn btn-primary btn-sm" data-bs-toggle="collapse" data-bs-target="#addTestiCollapse"><i class="bi bi-plus-lg"></i> Add Testimonial</button>
                            </div>
                            
                            <div class="collapse mb-4" id="addTestiCollapse">
                                <form action="{{ route('modules.testimonials.store') }}" method="POST" class="p-3 bg-white rounded border shadow-sm">
                                    @csrf
                                    <input name="client_name" class="form-control mb-2 form-control-sm" placeholder="Author's Name" required>
                                    <textarea name="content" class="form-control mb-2 js-summernote" data-height="170" rows="3" placeholder="What did they say about you?" required></textarea>
                                    <button class="btn btn-sm btn-dark w-100">Add Testimonial</button>
                                </form>
                            </div>

                            <div class="card shadow-sm border-0 mb-3 bg-white py-2 px-3">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="input-group input-group-sm" style="max-width: 250px;">
                                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                                        <input type="text" class="form-control js-module-search border-start-0" placeholder="Filter testimonials...">
                                    </div>
                                    <div class="ms-auto js-module-pagination"></div>
                                </div>
                            </div>

                            <div class="js-module-list">
                                @foreach($portfolio->testimonials as $testi)
                                    <div class="js-module-item bg-white border-bottom p-3">
                                        <div class="d-flex justify-content-between align-items-start gap-2">
                                            <div>
                                                <div class="fw-bold">{{ $testi->client_name }}</div>
                                                <small class="text-muted d-block mb-1">"{{ Str::limit(strip_tags($testi->content), 85) }}"</small>
                                            </div>
                                            <div class="d-flex gap-2">
                                                <button class="btn btn-sm btn-outline-secondary py-0" type="button" data-bs-toggle="collapse" data-bs-target="#editTesti{{ $testi->id }}">Edit</button>
                                                <form action="{{ route('modules.testimonials.destroy', $testi) }}" method="POST">
                                                    @csrf @method('delete')
                                                    <button class="btn btn-sm btn-link text-danger py-0 p-0 text-decoration-none">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                        <form action="{{ route('modules.testimonials.update', $testi) }}" method="POST" class="collapse mt-2 p-3 bg-light rounded border" id="editTesti{{ $testi->id }}">
                                            @csrf @method('PATCH')
                                            <input name="client_name" class="form-control form-control-sm mb-2" value="{{ $testi->client_name }}" required>
                                            <textarea name="content" class="form-control form-control-sm mb-2 js-summernote" data-height="130" required>{{ $testi->content }}</textarea>
                                            <button class="btn btn-sm btn-primary">Update Testimonial</button>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- 2.11 MEDIA DETAIL VIEW -->
                        <div class="module-detail-wrapper" id="moduleDetail-media" style="display: none;">
                            <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                                <h4 class="fw-bold mb-0">Manage Media Appearances</h4>
                                <button class="btn btn-primary btn-sm" data-bs-toggle="collapse" data-bs-target="#addMediaCollapse"><i class="bi bi-plus-lg"></i> Add Appearance</button>
                            </div>
                            
                            <div class="collapse mb-4" id="addMediaCollapse">
                                <div class="row g-3">
                                    <!-- Add TV show -->
                                    <div class="col-md-6 border-end">
                                        <form action="{{ route('modules.media.store') }}" method="POST" class="p-3 bg-white rounded border shadow-sm">
                                            @csrf
                                            <h6 class="fw-bold mb-2">TV & Talk Show Appearance</h6>
                                            <input type="hidden" name="type" value="tv">
                                            <input name="title" class="form-control mb-2 form-control-sm" placeholder="Topic Title" required>
                                            <input name="channel_platform" class="form-control mb-2 form-control-sm" placeholder="CNN, YouTube, etc." required>
                                            <label class="small fw-bold mb-1">Appearance Date</label>
                                            <input name="date" type="date" class="form-control mb-2 form-control-sm" required>
                                            <input name="link" type="url" class="form-control mb-2 form-control-sm" placeholder="Watch Link" required>
                                            <button class="btn btn-sm btn-dark w-100">Add TV Show</button>
                                        </form>
                                    </div>
                                    <!-- Add Op-ed -->
                                    <div class="col-md-6">
                                        <form action="{{ route('modules.media.store') }}" method="POST" class="p-3 bg-white rounded border shadow-sm">
                                            @csrf
                                            <h6 class="fw-bold mb-2">Newspaper Op-ed</h6>
                                            <input type="hidden" name="type" value="oped">
                                            <input name="title" class="form-control mb-2 form-control-sm" placeholder="Op-ed Title" required>
                                            <input name="newspaper_name" class="form-control mb-2 form-control-sm" placeholder="Dawn, The News, etc." required>
                                            <label class="small fw-bold mb-1">Publication Date</label>
                                            <input name="date" type="date" class="form-control mb-2 form-control-sm" required>
                                            <input name="link" type="url" class="form-control mb-2 form-control-sm" placeholder="Online Link" required>
                                            <button class="btn btn-sm btn-dark w-100">Add Op-ed</button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div class="card shadow-sm border-0 mb-3 bg-white py-2 px-3">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="input-group input-group-sm" style="max-width: 250px;">
                                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                                        <input type="text" class="form-control js-module-search border-start-0" placeholder="Filter media appearances...">
                                    </div>
                                    <div class="ms-auto js-module-pagination"></div>
                                </div>
                            </div>

                            <div class="js-module-list">
                                @foreach($portfolio->media as $tv)
                                    <div class="js-module-item bg-white border-bottom p-3">
                                        <div class="d-flex justify-content-between align-items-start gap-2">
                                            <div>
                                                <span class="badge bg-secondary mb-1">{{ strtoupper($tv->type) }}</span>
                                                <div class="fw-bold">{{ $tv->title }}</div>
                                                <small class="text-muted d-block mb-1">{{ $tv->channel_platform ?? $tv->newspaper_name }} | {{ \Carbon\Carbon::parse($tv->date)->format('M d, Y') }}</small>
                                            </div>
                                            <div class="d-flex gap-2">
                                                <button class="btn btn-sm btn-outline-secondary py-0" type="button" data-bs-toggle="collapse" data-bs-target="#editTv{{ $tv->id }}">Edit</button>
                                                <form action="{{ route('modules.media.destroy', $tv) }}" method="POST">
                                                    @csrf @method('delete')
                                                    <button class="btn btn-sm btn-link text-danger py-0 p-0 text-decoration-none">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                        <form action="{{ route('modules.media.update', $tv) }}" method="POST" class="collapse mt-2 p-3 bg-light rounded border" id="editTv{{ $tv->id }}">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="type" value="{{ $tv->type }}">
                                            <div class="row g-2">
                                                <div class="col-md-6"><input name="title" class="form-control form-control-sm mb-2" value="{{ $tv->title }}" required></div>
                                                <div class="col-md-6">
                                                    @if($tv->type === 'tv')
                                                        <input name="channel_platform" class="form-control form-control-sm mb-2" value="{{ $tv->channel_platform }}" required>
                                                    @else
                                                        <input name="newspaper_name" class="form-control form-control-sm mb-2" value="{{ $tv->newspaper_name }}" required>
                                                    @endif
                                                </div>
                                                <div class="col-md-6"><input name="date" type="date" class="form-control form-control-sm mb-2" value="{{ \Carbon\Carbon::parse($tv->date)->format('Y-m-d') }}" required></div>
                                                <div class="col-md-6"><input name="link" type="url" class="form-control form-control-sm mb-2" value="{{ $tv->link }}" required></div>
                                                <button class="btn btn-sm btn-primary">Update Media Entry</button>
                                            </div>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- 2.12 PUBLICATIONS DETAIL VIEW -->
                        <div class="module-detail-wrapper" id="moduleDetail-publications" style="display: none;">
                            <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                                <h4 class="fw-bold mb-0">Manage Publications</h4>
                                <button class="btn btn-primary btn-sm" data-bs-toggle="collapse" data-bs-target="#addPubsCollapse"><i class="bi bi-plus-lg"></i> Add Publication</button>
                            </div>
                            
                            <div class="collapse mb-4" id="addPubsCollapse">
                                <form action="{{ route('modules.publications.store') }}" method="POST" enctype="multipart/form-data" class="p-3 bg-white rounded border shadow-sm">
                                    @csrf
                                    <div class="row g-2 mb-2">
                                        <div class="col-md-4"><input name="type" class="form-control form-control-sm" placeholder="Type (Book, Journal, Report)" required></div>
                                        <div class="col-md-8"><input name="title" class="form-control form-control-sm" placeholder="Publication Title" required></div>
                                        <div class="col-md-6"><input name="authors" class="form-control form-control-sm" placeholder="Authors" required></div>
                                        <div class="col-md-3"><input name="year" class="form-control form-control-sm" placeholder="Year" required></div>
                                        <div class="col-md-3"><input name="publisher" class="form-control form-control-sm" placeholder="Publisher" required></div>
                                        <div class="col-md-6"><input name="link" type="url" class="form-control form-control-sm" placeholder="Online Link (Optional)"></div>
                                        <div class="col-md-6"><input name="report" type="file" class="form-control form-control-sm"></div>
                                    </div>
                                    <button class="btn btn-sm btn-dark w-100">Add Publication</button>
                                </form>
                            </div>

                            <div class="card shadow-sm border-0 mb-3 bg-white py-2 px-3">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="input-group input-group-sm" style="max-width: 250px;">
                                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                                        <input type="text" class="form-control js-module-search border-start-0" placeholder="Filter publications...">
                                    </div>
                                    <div class="ms-auto js-module-pagination"></div>
                                </div>
                            </div>

                            <div class="js-module-list">
                                @foreach($portfolio->publications as $pub)
                                    <div class="js-module-item bg-white border-bottom p-3">
                                        <div class="d-flex justify-content-between align-items-start gap-2">
                                            <div>
                                                <div class="fw-bold">{{ $pub->title }}</div>
                                                <small class="text-muted d-block mb-1">{{ $pub->authors }} ({{ $pub->year }}). <em>{{ $pub->publisher }}</em>. ({{ $pub->type }})</small>
                                            </div>
                                            <div class="d-flex gap-2">
                                                <button class="btn btn-sm btn-outline-secondary py-0" type="button" data-bs-toggle="collapse" data-bs-target="#editPub{{ $pub->id }}">Edit</button>
                                                <form action="{{ route('modules.publications.destroy', $pub) }}" method="POST">
                                                    @csrf @method('delete')
                                                    <button class="btn btn-sm btn-link text-danger py-0 p-0 text-decoration-none">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                        <form action="{{ route('modules.publications.update', $pub) }}" method="POST" enctype="multipart/form-data" class="collapse mt-2 p-3 bg-light rounded border" id="editPub{{ $pub->id }}">
                                            @csrf @method('PATCH')
                                            <div class="row g-2">
                                                <div class="col-md-4"><input name="type" class="form-control form-control-sm mb-2" value="{{ $pub->type }}" required></div>
                                                <div class="col-md-8"><input name="title" class="form-control form-control-sm mb-2" value="{{ $pub->title }}" required></div>
                                                <div class="col-md-6"><input name="authors" class="form-control form-control-sm mb-2" value="{{ $pub->authors }}" required></div>
                                                <div class="col-md-3"><input name="year" class="form-control form-control-sm mb-2" value="{{ $pub->year }}" required></div>
                                                <div class="col-md-3"><input name="publisher" class="form-control form-control-sm mb-2" value="{{ $pub->publisher }}" required></div>
                                                <div class="col-md-6"><input name="link" type="url" class="form-control form-control-sm mb-2" value="{{ $pub->link }}" placeholder="Online Link"></div>
                                                <div class="col-md-6">
                                                    <label class="small fw-bold">Replace File</label>
                                                    <input name="report" type="file" class="form-control form-control-sm">
                                                </div>
                                                <button class="btn btn-sm btn-primary">Update Publication</button>
                                            </div>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- 2.13 RESUME DETAIL VIEW -->
                        <div class="module-detail-wrapper" id="moduleDetail-resume" style="display: none;">
                            <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                                <h4 class="fw-bold mb-0">Manage Resume Document</h4>
                            </div>
                            <div class="card shadow-sm border-0 bg-white p-4">
                                <form action="{{ route('portfolio.sections.store') }}" method="POST" enctype="multipart/form-data" class="mb-3">
                                    @csrf
                                    <input type="hidden" name="type" value="resume">
                                    <input type="hidden" name="title" value="Professional Resume">
                                    <label class="form-label small fw-bold">Upload PDF format of your Resume</label>
                                    <input type="file" name="file" class="form-control mb-3" required>
                                    <button class="btn btn-sm btn-dark w-100">Upload PDF Resume File</button>
                                </form>
                                @php $resume = $portfolio->sections->where('type', 'resume')->first(); @endphp
                                @if($resume)
                                    <div class="alert alert-info py-3 small d-flex justify-content-between align-items-center mb-0 mt-2">
                                        <span>Current Resume Document: <strong>{{ basename($resume->file_path) }}</strong></span>
                                        <form action="{{ route('portfolio.sections.destroy', $resume) }}" method="POST">
                                            @csrf @method('delete')
                                            <button class="btn btn-sm text-danger py-0 fw-bold border-0 bg-transparent">Remove File</button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3. SETTINGS TAB PANE -->
                <div class="tab-pane fade" id="settingsPane" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3 class="fw-bold mb-0">Portfolio Settings</h3>
                    </div>

                    <!-- Single form wrapping all three cards to prevent validation failures -->
                    <form method="post" action="{{ route('portfolio.update') }}" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- CARD 1: Basic Info & Portfolio Visibility -->
                        <div class="card shadow-sm border-0 rounded-3 mb-4 bg-white">
                            <div class="card-header bg-white py-3">
                                <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-person-circle text-primary me-2"></i>Profile Information & Basic Details</h5>
                            </div>
                            <div class="card-body p-4">
                                <div class="mb-4 text-center">
                                    @if($portfolio->profile_image)
                                        <img src="{{ Storage::url($portfolio->profile_image) }}" class="rounded-circle shadow-sm border border-secondary-subtle mb-3" style="width: 110px; height: 110px; object-fit: cover;">
                                    @endif
                                    <div style="max-width: 250px; margin: 0 auto;">
                                        <input type="file" name="profile_image" class="form-control form-control-sm">
                                        <small class="text-muted d-block mt-1">Accepts PNG, JPG, JPEG (Max 2MB)</small>
                                    </div>
                                </div>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold">Full Name Display</label>
                                        <input name="title" type="text" class="form-control" value="{{ $portfolio->title }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold">Profession Title / Hero Subtitle</label>
                                        <input name="position" type="text" class="form-control" value="{{ $portfolio->position }}" placeholder="e.g. Senior Software Architect">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold">Organization</label>
                                        <input name="organization" type="text" class="form-control" value="{{ $portfolio->organization }}" placeholder="e.g. Google DeepMind">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold">Contact Number</label>
                                        <input name="contact_number" type="text" class="form-control" value="{{ $portfolio->contact_number }}" placeholder="e.g. +92 345 1234567">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label small fw-bold">City</label>
                                        <input name="city" type="text" class="form-control" value="{{ $portfolio->city }}" placeholder="e.g. London">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label small fw-bold">Country</label>
                                        <input name="country" type="text" class="form-control" value="{{ $portfolio->country }}" placeholder="e.g. United Kingdom">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label small fw-bold">LinkedIn Profile URL</label>
                                        <input name="linkedin_url" type="url" class="form-control" value="{{ $portfolio->linkedin_url }}" placeholder="https://linkedin.com/in/username">
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <label class="form-label small fw-bold">Brief Tagline / Pitch Hook</label>
                                    <textarea name="description" class="form-control js-summernote" data-height="140" rows="2" placeholder="Brief tagline shown on hero section...">{{ $portfolio->description }}</textarea>
                                </div>

                                <div class="mt-4">
                                    <label class="form-label small fw-bold">Detailed Bio / About Journey</label>
                                    <textarea name="detailed_bio" class="form-control js-summernote" data-height="220" rows="5" placeholder="Detailed professional history bio...">{{ $portfolio->detailed_bio }}</textarea>
                                </div>

                                <hr class="my-4">

                                <h6 class="fw-bold mb-3 text-primary"><i class="bi bi-link-45deg me-1"></i>Portfolio Link Status & Visibility</h6>
                                <div class="row g-3 mb-4">
                                    <div class="col-12">
                                        <div class="mb-2 fw-bold small">Public Portfolio Link</div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="is_active" id="is_active_yes" value="active" {{ $portfolio->is_active ? 'checked' : '' }}>
                                            <label class="form-check-label fw-bold text-success" for="is_active_yes">Active (Public URL accessible)</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="is_active" id="is_active_no" value="inactive" {{ $portfolio->is_active ? '' : 'checked' }}>
                                            <label class="form-check-label fw-bold text-danger" for="is_active_no">Deactivated (Private / hidden)</label>
                                        </div>
                                        <small class="text-muted d-block mt-1 mb-3">If deactivated, visiting your public URL <code>/{{ Auth::user()->username }}</code> will return a 404 Page Not Found error.</small>
                                        
                                        <div class="mb-2 fw-bold small">Profile Privacy Setting</div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="is_public" id="is_public_no" value="private" {{ !$portfolio->is_public ? 'checked' : '' }}>
                                            <label class="form-check-label fw-bold text-warning" for="is_public_no"><i class="bi bi-lock-fill"></i> Private (Only added connections can view)</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="is_public" id="is_public_yes" value="public" {{ $portfolio->is_public ? 'checked' : '' }}>
                                            <label class="form-check-label fw-bold text-success" for="is_public_yes"><i class="bi bi-globe"></i> Public (Everyone can view)</label>
                                        </div>
                                        <small class="text-muted d-block mt-1">By default, your profile is private and can only be seen by users who you accept as connections.</small>
                                    </div>
                                </div>

                                <hr class="my-4">

                                <h6 class="fw-bold mb-3">Portfolio Visibility Controls (Contact Details)</h6>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <div class="mb-2 fw-bold small">Show Email Address</div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="show_email" id="show_email_yes" value="show" {{ $portfolio->show_email ? 'checked' : '' }}>
                                            <label class="form-check-label" for="show_email_yes">Show</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="show_email" id="show_email_no" value="hide" {{ $portfolio->show_email ? '' : 'checked' }}>
                                            <label class="form-check-label" for="show_email_no">Hide</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-2 fw-bold small">Show Phone Number</div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="show_phone" id="show_phone_yes" value="show" {{ $portfolio->show_phone ? 'checked' : '' }}>
                                            <label class="form-check-label" for="show_phone_yes">Show</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="show_phone" id="show_phone_no" value="hide" {{ $portfolio->show_phone ? '' : 'checked' }}>
                                            <label class="form-check-label" for="show_phone_no">Hide</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-2 fw-bold small">Show LinkedIn URL</div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="show_linkedin" id="show_linkedin_yes" value="show" {{ $portfolio->show_linkedin ? 'checked' : '' }}>
                                            <label class="form-check-label" for="show_linkedin_yes">Show</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="show_linkedin" id="show_linkedin_no" value="hide" {{ $portfolio->show_linkedin ? '' : 'checked' }}>
                                            <label class="form-check-label" for="show_linkedin_no">Hide</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- CARD 2: Design Themes -->
                        <div class="card shadow-sm border-0 rounded-3 mb-4 bg-white">
                            <div class="card-header bg-white py-3">
                                <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-palette text-success me-2"></i>Active Design Theme</h5>
                            </div>
                            <div class="card-body p-4">
                                <label class="form-label small fw-bold">Active Design Theme Layout</label>
                                <select name="theme" class="form-select border-primary shadow-sm mb-3">
                                    @foreach($themes as $t)
                                        <option value="{{ $t->slug }}" {{ $portfolio->theme == $t->slug ? 'selected' : '' }}>{{ $t->name }}</option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Changes the visual style of your public resume. Choose from Classic, Premium, or Elegant.</small>
                            </div>
                        </div>

                        <!-- CARD 3: Section Visibility Controls -->
                        <div class="card shadow-sm border-0 rounded-3 mb-4 bg-white">
                            <div class="card-header bg-white py-3">
                                <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-eye text-warning me-2"></i>Section Visibility Controls</h5>
                            </div>
                            <div class="card-body p-4">
                                <div class="row g-3">
                                    @php
                                        $sectionsMap = [
                                            'show_skills' => 'Skills',
                                            'show_projects' => 'Projects',
                                            'show_experience' => 'Experience',
                                            'show_education' => 'Education',
                                            'show_services' => 'Services',
                                            'show_certifications' => 'Certifications',
                                            'show_trainings' => 'Trainings',
                                            'show_achievements' => 'Achievements',
                                            'show_contributions' => 'Contributions',
                                            'show_testimonials' => 'Testimonials',
                                            'show_media' => 'Media Appearances',
                                            'show_publications' => 'Publications'
                                        ];
                                    @endphp
                                    @foreach($sectionsMap as $field => $label)
                                        <div class="col-md-6 col-lg-4">
                                            <div class="mb-2 fw-bold small text-dark">{{ $label }}</div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="{{ $field }}" id="sett_{{ $field }}_yes" value="show" {{ $portfolio->$field ? 'checked' : '' }}>
                                                <label class="form-check-label" for="sett_{{ $field }}_yes">Show</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="{{ $field }}" id="sett_{{ $field }}_no" value="hide" {{ $portfolio->$field ? '' : 'checked' }}>
                                                <label class="form-check-label" for="sett_{{ $field }}_no">Hide</label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Central Save Bar -->
                        <div class="card border-0 shadow-sm mb-4 bg-white">
                            <div class="card-body d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary px-5 shadow-sm fw-bold">Save All Settings</button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- 4. INBOX / MESSAGES TAB PANE -->
                <div class="tab-pane fade" id="inboxPane" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3 class="fw-bold mb-0">Public Messages & Inquiries</h3>
                    </div>

                    <div class="js-module-list">
                        @forelse($portfolio->messages->sortByDesc('created_at') as $msg)
                            <div class="card mb-3 border-0 shadow-sm js-module-item bg-white">
                                <div class="card-body p-4">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div>
                                            <h6 class="fw-bold mb-1">{{ $msg->name }}</h6>
                                            <small class="text-muted d-block">{{ $msg->email }} | {{ $msg->created_at->diffForHumans() }}</small>
                                        </div>
                                        <div class="d-flex gap-2">
                                            @if(!$msg->is_read)
                                                <form action="{{ route('messages.read', $msg) }}" method="POST">
                                                    @csrf
                                                    <button class="btn btn-sm btn-outline-primary">Mark Read</button>
                                                </form>
                                            @endif
                                            <form action="{{ route('messages.destroy', $msg) }}" method="POST">
                                                @csrf @method('delete')
                                                <button class="btn btn-sm btn-outline-danger">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                    <p class="mb-3 p-3 bg-light rounded text-secondary italic">"{{ $msg->message }}"</p>
                                    
                                    @if($msg->reply)
                                        <div class="alert alert-secondary py-2 px-3 small mb-0">
                                            <strong>My Response:</strong> {!! $msg->reply !!}
                                        </div>
                                    @else
                                        <button class="btn btn-sm btn-dark" data-bs-toggle="collapse" data-bs-target="#replyBox{{ $msg->id }}"><i class="bi bi-reply-fill me-1"></i>Reply via Email</button>
                                        <form action="{{ route('messages.reply', $msg) }}" method="POST" class="collapse mt-3" id="replyBox{{ $msg->id }}">
                                            @csrf
                                            <div class="mb-3">
                                                <textarea name="reply" class="form-control js-summernote" data-height="120" placeholder="Type email response..."></textarea>
                                            </div>
                                            <button class="btn btn-primary btn-sm fw-bold">Send Reply Email</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5 text-muted bg-white rounded shadow-sm">
                                <i class="bi bi-inbox display-1 mb-3 text-secondary-subtle"></i>
                                <p class="mb-0">Your Inbox is completely empty.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Connections Tab Pane -->
                <div class="tab-pane fade" id="connectionsPane" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3 class="fw-bold mb-0">Manage Connections</h3>
                    </div>

                    <div class="row">
                        <!-- Left Column: Search & Add Connections -->
                        <div class="col-lg-6 mb-4">
                            <div class="card border-0 shadow-sm rounded-3 h-100">
                                <div class="card-header bg-white py-3">
                                    <h5 class="mb-0 fw-bold text-primary"><i class="bi bi-person-plus-fill me-2"></i>Find Professionals</h5>
                                </div>
                                <div class="card-body bg-white">
                                    <form action="{{ route('portfolio.edit') }}" method="GET" class="mb-4">
                                        <div class="input-group">
                                            <input type="text" name="search" class="form-control" placeholder="Search by name, username or email..." value="{{ request('search') }}">
                                            <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> Search</button>
                                        </div>
                                    </form>

                                    @if(request('search'))
                                        <h6 class="fw-bold mb-3">Search Results</h6>
                                        <ul class="list-group list-group-flush">
                                            @forelse($searchResults as $u)
                                                <li class="list-group-item d-flex align-items-center justify-content-between px-0">
                                                    <div class="d-flex align-items-center">
                                                        <div class="me-3">
                                                            @if($u->portfolio && $u->portfolio->profile_image)
                                                                <img src="{{ Storage::url($u->portfolio->profile_image) }}" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                                                            @else
                                                                <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white fw-bold" style="width: 40px; height: 40px;">
                                                                    {{ strtoupper(substr($u->name, 0, 2)) }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div>
                                                            <div class="fw-bold">{{ $u->name }}</div>
                                                            <small class="text-muted">{{ '@' . $u->username }}</small>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        @php
                                                            $conn = Auth::user()->connectionWith($u);
                                                        @endphp
                                                        @if($conn)
                                                            @if($conn->status === 'accepted')
                                                                <span class="badge bg-success"><i class="bi bi-check-lg"></i> Connected</span>
                                                            @elseif($conn->sender_id === Auth::id())
                                                                <form action="{{ route('connections.cancel', $conn->id) }}" method="POST" class="d-inline">
                                                                    @csrf
                                                                    <button type="submit" class="btn btn-outline-danger btn-sm">Cancel Request</button>
                                                                </form>
                                                            @else
                                                                <form action="{{ route('connections.accept', $conn->id) }}" method="POST" class="d-inline">
                                                                    @csrf
                                                                    <button type="submit" class="btn btn-success btn-sm me-1">Accept</button>
                                                                </form>
                                                                <form action="{{ route('connections.reject', $conn->id) }}" method="POST" class="d-inline">
                                                                    @csrf
                                                                    <button type="submit" class="btn btn-outline-secondary btn-sm">Ignore</button>
                                                                </form>
                                                            @endif
                                                        @else
                                                            <form action="{{ route('connections.request', $u->id) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-person-plus-fill"></i> Connect</button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </li>
                                            @empty
                                                <li class="list-group-item text-center py-3 text-muted">No professionals found.</li>
                                            @endforelse
                                        </ul>
                                    @else
                                        <p class="text-muted small">Type a name, username, or email to find and connect with other users.</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Right Column: Requests & Active Connections -->
                        <div class="col-lg-6 mb-4">
                            <!-- Pending Received Requests -->
                            <div class="card border-0 shadow-sm rounded-3 mb-4">
                                <div class="card-header bg-white py-3">
                                    <h5 class="mb-0 fw-bold text-warning"><i class="bi bi-bell-fill me-2"></i>Connection Requests</h5>
                                </div>
                                <div class="card-body p-0 bg-white">
                                    <ul class="list-group list-group-flush mb-0">
                                        @forelse($pendingReceived as $conn)
                                            <li class="list-group-item d-flex align-items-center justify-content-between p-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="me-3">
                                                        @if($conn->sender->portfolio && $conn->sender->portfolio->profile_image)
                                                            <img src="{{ Storage::url($conn->sender->portfolio->profile_image) }}" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                                                        @else
                                                            <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white fw-bold" style="width: 40px; height: 40px;">
                                                                {{ strtoupper(substr($conn->sender->name, 0, 2)) }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <div class="fw-bold">{{ $conn->sender->name }}</div>
                                                        <small class="text-muted">{{ '@' . $conn->sender->username }}</small>
                                                    </div>
                                                </div>
                                                <div>
                                                    <form action="{{ route('connections.accept', $conn->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-success btn-sm me-1">Accept</button>
                                                    </form>
                                                    <form action="{{ route('connections.reject', $conn->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-outline-secondary btn-sm">Ignore</button>
                                                    </form>
                                                </div>
                                            </li>
                                        @empty
                                            <li class="list-group-item text-center py-4 text-muted">No pending connection requests.</li>
                                        @endforelse
                                    </ul>
                                </div>
                            </div>

                            <!-- Active Connections -->
                            <div class="card border-0 shadow-sm rounded-3">
                                <div class="card-header bg-white py-3">
                                    <h5 class="mb-0 fw-bold text-success"><i class="bi bi-people-fill me-2"></i>My Connections ({{ $connectionsCount }})</h5>
                                </div>
                                <div class="card-body p-0 bg-white">
                                    <ul class="list-group list-group-flush mb-0">
                                        @forelse($acceptedConnections as $otherUser)
                                            <li class="list-group-item d-flex align-items-center justify-content-between p-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="me-3">
                                                        @if($otherUser->portfolio && $otherUser->portfolio->profile_image)
                                                            <img src="{{ Storage::url($otherUser->portfolio->profile_image) }}" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                                                        @else
                                                            <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white fw-bold" style="width: 40px; height: 40px;">
                                                                {{ strtoupper(substr($otherUser->name, 0, 2)) }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <div class="fw-bold">{{ $otherUser->name }}</div>
                                                        <small class="text-muted"><a href="{{ route('portfolio.show', $otherUser->username) }}" target="_blank" class="text-decoration-none">{{ '@' . $otherUser->username }}</a></small>
                                                    </div>
                                                </div>
                                                <div>
                                                    <form action="{{ route('connections.remove', $otherUser->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to remove this connection?');">
                                                        @csrf
                                                        <button type="submit" class="btn btn-outline-danger btn-sm"><i class="bi bi-trash"></i> Remove</button>
                                                    </form>
                                                </div>
                                            </li>
                                        @empty
                                            <li class="list-group-item text-center py-4 text-muted">You haven't added any connections yet.</li>
                                        @endforelse
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Client-side search and pagination engine
            function initModuleSearchAndPagination(moduleSelector) {
                const container = document.querySelector(moduleSelector);
                if (!container) return;

                const searchInput = container.querySelector('.js-module-search');
                const items = Array.from(container.querySelectorAll('.js-module-item'));
                const paginationContainer = container.querySelector('.js-module-pagination');
                const itemsPerPage = 5;
                let currentPage = 1;
                let filteredItems = [...items];

                function renderPage() {
                    const totalPages = Math.ceil(filteredItems.length / itemsPerPage);
                    
                    // Hide all items
                    items.forEach(item => item.style.display = 'none');

                    // Show items for current page
                    const start = (currentPage - 1) * itemsPerPage;
                    const end = start + itemsPerPage;
                    
                    filteredItems.slice(start, end).forEach(item => {
                        item.style.display = '';
                    });

                    // Render pagination
                    if (paginationContainer) {
                        paginationContainer.innerHTML = '';
                        if (totalPages > 1) {
                            const nav = document.createElement('nav');
                            const ul = document.createElement('ul');
                            ul.className = 'pagination pagination-sm mb-0 justify-content-end';

                            // Previous Page
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

                            // Page items
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

                            // Next Page
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
                    // Remove existing event listener if any to avoid stacking
                    const newSearchInput = searchInput.cloneNode(true);
                    searchInput.parentNode.replaceChild(newSearchInput, searchInput);
                    
                    newSearchInput.addEventListener('input', function() {
                        const query = this.value.toLowerCase().trim();
                        filteredItems = items.filter(item => {
                            const searchText = item.innerText.toLowerCase();
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

                // Sidebar navigation view switcher
                const modulesGrid = document.getElementById('modulesGrid');
                const modulesDetailPanel = document.getElementById('modulesDetailPanel');
                const backToModulesBtn = document.getElementById('backToModulesBtn');

                document.querySelectorAll('.js-manage-section').forEach(btn => {
                    btn.addEventListener('click', function(e) {
                        e.preventDefault();
                        const targetModule = this.getAttribute('data-module');
                        showModuleDetail(targetModule);
                    });
                });

                document.querySelectorAll('.js-add-section-btn').forEach(btn => {
                    btn.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        const targetModule = this.getAttribute('data-module');
                        showModuleDetail(targetModule, true);
                    });
                });

                backToModulesBtn.addEventListener('click', function() {
                    modulesDetailPanel.style.display = 'none';
                    modulesGrid.style.display = '';
                    document.querySelectorAll('.module-detail-wrapper').forEach(wrapper => {
                        wrapper.style.display = 'none';
                    });
                });

                function showModuleDetail(moduleName, openAddForm = false) {
                    modulesGrid.style.display = 'none';
                    modulesDetailPanel.style.display = '';
                    
                    document.querySelectorAll('.module-detail-wrapper').forEach(wrapper => {
                        wrapper.style.display = 'none';
                    });

                    const activeWrapper = document.getElementById('moduleDetail-' + moduleName);
                    if (activeWrapper) {
                        activeWrapper.style.display = '';
                        initModuleSearchAndPagination('#moduleDetail-' + moduleName);
                        
                        if (openAddForm) {
                            const addCollapse = activeWrapper.querySelector('.collapse');
                            if (addCollapse) {
                                const bsCollapse = new bootstrap.Collapse(addCollapse, { show: true });
                                bsCollapse.show();
                            }
                        }
                    }
                }

                // Auto-close responsive offcanvas sidebar on menu click (mobile view)
                const sidebarMenuEl = document.getElementById('sidebarMenu');
                if (sidebarMenuEl) {
                    sidebarMenuEl.querySelectorAll('.nav-link').forEach(link => {
                        link.addEventListener('click', () => {
                            const bsOffcanvas = bootstrap.Offcanvas.getInstance(sidebarMenuEl);
                            if (bsOffcanvas) {
                                bsOffcanvas.hide();
                            }
                        });
                    });
                }
                // Auto-switch to connections tab if we are searching
                const urlParams = new URLSearchParams(window.location.search);
                if (urlParams.has('search')) {
                    const connTab = document.getElementById('connections-tab');
                    if (connTab) {
                        connTab.click();
                    }
                }
            });
        </script>
    @endpush
</x-app-layout>

@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row g-4">
        <!-- Main Job Details Column -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <span class="badge bg-primary-subtle text-primary border border-primary px-3 py-1 mb-2 fs-6">{{ strtoupper($opportunity->type) }}</span>
                            <h1 class="h3 fw-bold text-dark mb-1">{{ $opportunity->title }}</h1>
                            <p class="text-secondary fw-medium mb-0">
                                @if($opportunity->company)
                                    <a href="{{ route('companies.show', $opportunity->company->slug) }}" class="text-decoration-none text-primary fw-bold">{{ $opportunity->company->name }}</a>
                                @else
                                    <span class="fw-bold">Platform Opportunity</span>
                                @endif
                                &bull; <i class="fa-solid fa-location-dot text-danger"></i> {{ ucfirst($opportunity->location_type) }} ({{ $opportunity->city ?? 'Global' }})
                            </p>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            @auth
                            <button type="button" class="btn btn-outline-success btn-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#shareOpportunityModal" title="Share this job on your professional feed">
                                <i class="fa-solid fa-share-nodes me-1"></i> Share to Feed
                            </button>
                            @if(Auth::id() === $opportunity->posted_by_user_id || ($opportunity->company && $opportunity->company->user_id === Auth::id()) || Auth::user()->isAdmin())
                                <a href="{{ route('opportunities.edit', $opportunity->id) }}" class="btn btn-outline-primary btn-sm rounded-pill"><i class="fa-solid fa-pen-to-square me-1"></i> Edit Job</a>
                                <form action="{{ route('opportunities.destroy', $opportunity->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this job posting?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill"><i class="fa-solid fa-trash-can me-1"></i> Delete</button>
                                </form>
                            @endif
                            <form action="{{ route('opportunities.save', $opportunity->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-outline-secondary btn-sm rounded-circle p-2" title="{{ $isSaved ? 'Unsave Job' : 'Save Job' }}">
                                    <i class="fa-{{ $isSaved ? 'solid' : 'regular' }} fa-bookmark fa-lg text-warning"></i>
                                </button>
                            </form>
                            @endauth
                        </div>
                    </div>

                    @php
                        $symbol = ($opportunity->salary_currency === 'PKR' || $opportunity->salary_currency === 'Rs') ? 'PKR ' : '$';
                        if ($opportunity->salary_min && $opportunity->salary_max) {
                            $salaryText = $symbol . number_format($opportunity->salary_min) . ' - ' . $symbol . number_format($opportunity->salary_max) . ' / ' . $opportunity->salary_period;
                        } elseif ($opportunity->salary_min) {
                            $salaryText = $symbol . number_format($opportunity->salary_min) . ' / ' . $opportunity->salary_period;
                        } else {
                            $salaryText = 'Negotiable';
                        }
                        $deadlineBadge = $opportunity->deadline_badge;
                    @endphp
                    <!-- Meta Tags Row -->
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 my-4 p-3 bg-light rounded-3 border">
                        <div><span class="text-muted d-block small">Employment Type</span> <strong class="text-dark">{{ ucfirst($opportunity->employment_type) }}</strong></div>
                        <div><span class="text-muted d-block small">Min Experience</span> <strong class="text-dark">{{ $opportunity->min_experience }} Years</strong></div>
                        <div><span class="text-muted d-block small">Salary Range</span> <strong class="text-dark">{{ $salaryText }}</strong></div>
                        <div><span class="text-muted d-block small">Posted Date</span> <strong class="text-dark">{{ $opportunity->published_at ? $opportunity->published_at->format('M d, Y') : 'Recently' }}</strong></div>
                        <div>
                            <span class="text-muted d-block small">Deadline Status</span>
                            <span class="badge {{ $deadlineBadge['class'] }} px-2.5 py-1.5 fs-6 mt-1">
                                <i class="{{ $deadlineBadge['icon'] }} me-1"></i> {{ $deadlineBadge['short_label'] }}
                            </span>
                        </div>
                    </div>

                    <!-- Job Description -->
                    <h5 class="fw-bold text-dark mb-3"><i class="fa-solid fa-file-text text-primary me-2"></i> Role Overview</h5>
                    <div class="text-secondary lh-lg mb-4">{!! $opportunity->description !!}</div>

                    @if($opportunity->responsibilities)
                    <h5 class="fw-bold text-dark mb-3"><i class="fa-solid fa-list-check text-primary me-2"></i> Key Responsibilities</h5>
                    <div class="text-secondary lh-lg mb-4">{!! $opportunity->responsibilities !!}</div>
                    @endif

                    @if($opportunity->education_required)
                    <h5 class="fw-bold text-dark mb-3"><i class="fa-solid fa-graduation-cap text-primary me-2"></i> Required Background & Education</h5>
                    <div class="text-secondary lh-lg mb-4">{!! $opportunity->education_required !!}</div>
                    @endif

                    @if($opportunity->skills->isNotEmpty())
                    <h5 class="fw-bold text-dark mb-3"><i class="fa-solid fa-code text-primary me-2"></i> Required Skills</h5>
                    <div class="d-flex flex-wrap gap-2 mb-4">
                        @foreach($opportunity->skills as $sk)
                            <span class="badge bg-primary-subtle text-primary border border-primary px-3 py-2 fs-6">{{ $sk->skill_name }}</span>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar Column: Match Score, AI Mock Interview, Countdown & Application Actions -->
        <div class="col-lg-4">
            <!-- Application Deadline & Days Countdown Card -->
            <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white p-4">
                <div class="d-flex align-items-center mb-2">
                    <div class="rounded-circle bg-primary-subtle text-primary p-3 me-3">
                        <i class="fa-solid fa-clock-rotate-left fa-xl"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold text-dark mb-0">Application Deadline</h6>
                        <span class="text-muted small">
                            @if($opportunity->application_deadline)
                                {{ $opportunity->application_deadline->format('F d, Y') }}
                            @else
                                Open / Rolling Hiring
                            @endif
                        </span>
                    </div>
                </div>

                @if($opportunity->application_deadline)
                    @if($deadlineBadge['status'] === 'expired')
                        <div class="alert alert-danger border-0 mb-0 rounded-3 text-center py-2 px-3 mt-3">
                            <i class="fa-solid fa-triangle-exclamation me-1"></i> <strong>Application Deadline Passed</strong>
                            <div class="small mt-0.5">This position closed on {{ $opportunity->application_deadline->format('M d, Y') }}.</div>
                        </div>
                    @else
                        <div class="p-3 bg-light rounded-3 text-center mt-3 border">
                            <span class="text-muted small d-block mb-1">Time Remaining to Apply</span>
                            <div class="d-flex justify-content-center align-items-baseline gap-2">
                                <span class="display-5 fw-bold text-primary mb-0">{{ max(0, $opportunity->days_remaining) }}</span>
                                <span class="fw-bold text-dark fs-5">{{ Str::plural('Day', max(0, $opportunity->days_remaining)) }} Left</span>
                            </div>
                            <div class="badge {{ $deadlineBadge['class'] }} mt-2 px-3 py-1.5 fs-6">
                                <i class="{{ $deadlineBadge['icon'] }} me-1"></i> {{ $deadlineBadge['label'] }}
                            </div>
                        </div>
                    @endif
                @else
                    <div class="alert alert-info border-0 mb-0 rounded-3 text-center py-2 px-3 mt-3 small">
                        <i class="fa-solid fa-circle-check me-1"></i> <strong>Active Hiring</strong>
                        <div>No expiration date set by employer.</div>
                    </div>
                @endif
            </div>

            <!-- Transparent Match Score Breakdown Card -->
            @if(isset($matchResult) && $matchResult)
            <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold text-dark mb-0"><i class="fa-solid fa-chart-pie text-primary me-2"></i> Portfolio Match Score</h5>
                        <span class="badge bg-success rounded-pill px-3 py-2 fs-5 fw-bold">{{ $matchResult['overall_score'] }}%</span>
                    </div>

                    <p class="text-muted small mb-3">Matching evaluation based on your portfolio skills, experience, education, and career preferences.</p>

                    <!-- Score Breakdown Progress Bars -->
                    <div class="mb-3">
                        <div class="d-flex justify-content-between small mb-1"><span class="fw-semibold">Skills Match</span><span>{{ $matchResult['breakdown']['skills'] }}%</span></div>
                        <div class="progress mb-2" style="height: 6px;"><div class="progress-bar bg-primary" style="width: {{ $matchResult['breakdown']['skills'] }}%"></div></div>

                        <div class="d-flex justify-content-between small mb-1"><span class="fw-semibold">Experience Match</span><span>{{ $matchResult['breakdown']['experience'] }}%</span></div>
                        <div class="progress mb-2" style="height: 6px;"><div class="progress-bar bg-info" style="width: {{ $matchResult['breakdown']['experience'] }}%"></div></div>

                        <div class="d-flex justify-content-between small mb-1"><span class="fw-semibold">Location Match</span><span>{{ $matchResult['breakdown']['location'] }}%</span></div>
                        <div class="progress mb-2" style="height: 6px;"><div class="progress-bar bg-success" style="width: {{ $matchResult['breakdown']['location'] }}%"></div></div>
                    </div>

                    @if(!empty($matchResult['explanations']))
                    <div class="p-3 bg-light rounded-3 small">
                        <strong class="d-block mb-1 text-dark">Why you were recommended:</strong>
                        <ul class="mb-0 ps-3 text-secondary">
                            @foreach($matchResult['explanations'] as $exp)
                                <li>{{ $exp }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- AI Mock Interview Card -->
            <div class="card border-0 shadow-sm rounded-4 mb-4 bg-gradient bg-dark text-white p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="rounded-circle bg-warning text-dark p-3 me-3"><i class="fa-solid fa-robot fa-xl"></i></div>
                    <div>
                        <h5 class="fw-bold mb-0 text-white">AI Mock Interview</h5>
                        <span class="text-warning small">Practice for this specific job</span>
                    </div>
                </div>
                <p class="text-light small mb-3">Take a personalized mock interview tailored to this job description and your portfolio background. Get instant diagnostic feedback & readiness score.</p>
                @auth
                <form action="{{ route('mock-interviews.start') }}" method="POST">
                    @csrf
                    <input type="hidden" name="opportunity_id" value="{{ $opportunity->id }}">
                    <button type="submit" class="btn btn-warning w-100 fw-bold rounded-pill"><i class="fa-solid fa-play me-1"></i> Take AI Mock Interview</button>
                </form>
                @else
                <button type="button" class="btn btn-warning w-100 fw-bold rounded-pill" data-bs-toggle="modal" data-bs-target="#guestApplyModal">Sign In to Take Interview</button>
                @endauth
            </div>

            <!-- Apply Card -->
            <div class="card border-0 shadow-sm rounded-4 p-4 text-center">
                @auth
                    @if($opportunity->company && $opportunity->company->user_id === Auth::id())
                        <div class="alert alert-info border-0 shadow-sm mb-0 rounded-3 text-center">
                            <h6 class="fw-bold text-dark mb-1"><i class="fa-solid fa-building-circle-check text-primary me-2"></i> Posted by Your Company</h6>
                            <p class="text-secondary small mb-3">You manage this position as an employer representative.</p>
                            <div class="d-flex gap-2 justify-content-center">
                                <a href="{{ route('opportunities.edit', $opportunity->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                    <i class="fa-solid fa-pen-to-square me-1"></i> Edit Job
                                </a>
                                <a href="{{ route('companies.dashboard', $opportunity->company_id) }}" class="btn btn-sm btn-primary rounded-pill px-3 fw-bold shadow-sm">
                                    <i class="fa-solid fa-gauge me-1"></i> Manage Applicants
                                </a>
                            </div>
                        </div>
                    @elseif(isset($userApplication) && $userApplication)
                        <div class="alert alert-success mb-0 rounded-3">
                            <i class="fa-solid fa-circle-check me-1"></i> You applied on {{ $userApplication->created_at ? \Carbon\Carbon::parse($userApplication->created_at)->format('M d, Y') : 'Recently' }}
                            <div class="fw-bold mt-1">Status: {{ strtoupper(str_replace('_', ' ', $userApplication->status)) }}</div>
                        </div>
                    @elseif($deadlineBadge['status'] === 'expired')
                        <div class="alert alert-secondary border-0 mb-0 rounded-3 text-center">
                            <i class="fa-solid fa-lock text-muted me-1"></i> <strong>Applications Closed</strong>
                            <div class="small text-muted mt-1">The application deadline for this job posting has passed.</div>
                        </div>
                    @else
                        <button type="button" class="btn btn-primary btn-lg w-100 fw-bold rounded-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#applyModal">
                            <i class="fa-solid fa-paper-plane me-1"></i> Apply Now with Portfolio
                        </button>
                    @endif
                @else
                    @if($deadlineBadge['status'] === 'expired')
                        <div class="alert alert-secondary border-0 mb-0 rounded-3 text-center">
                            <i class="fa-solid fa-lock text-muted me-1"></i> <strong>Applications Closed</strong>
                            <div class="small text-muted mt-1">The application deadline for this job posting has passed.</div>
                        </div>
                    @else
                        <button type="button" class="btn btn-primary btn-lg w-100 fw-bold rounded-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#guestApplyModal">
                            <i class="fa-solid fa-paper-plane me-1"></i> Apply Now with Portfolio
                        </button>
                    @endif
                @endauth
            </div>
        </div>
    </div>
</div>

<!-- Apply Modal (Logged In Users) -->
@auth
<div class="modal fade" id="applyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom py-3">
                <h5 class="modal-title fw-bold text-dark"><i class="fa-solid fa-paper-plane text-primary me-2"></i> Apply for {{ $opportunity->title }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('applications.store', $opportunity->id) }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <p class="text-secondary small">Your verified portfolio & CV will be automatically attached to this application.</p>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Cover Note / Note to Recruiter</label>
                        <textarea name="cover_letter" class="form-control" rows="4" placeholder="Briefly introduce yourself and why you're a great fit..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top py-3">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4 fw-bold shadow-sm">Submit Application</button>
                </div>
            </form>
        </div>
    </div>
</div>
@else
<!-- Guest Application Prompt Modal -->
<div class="modal fade" id="guestApplyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 text-center p-4">
            <div class="modal-header border-0 pb-0 justify-content-end">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-0">
                <div class="rounded-circle bg-primary-subtle text-primary d-inline-flex align-items-center justify-content-center mb-3" style="width: 64px; height: 64px;">
                    <i class="fa-solid fa-briefcase fa-2xl"></i>
                </div>
                <h4 class="fw-bold text-dark mb-2">Apply for {{ $opportunity->title }}</h4>
                <p class="text-secondary small mb-4">
                    Job postings are publicly visible on MyResume.cloud. To submit your application and match your skills with this role, please sign in or create a free portfolio account.
                </p>

                <div class="d-grid gap-2 col-11 mx-auto mb-3">
                    <a href="{{ route('register') }}" class="btn btn-primary btn-lg rounded-pill fw-bold shadow-sm">
                        <i class="fa-solid fa-user-plus me-2"></i> Create Portfolio Account
                    </a>
                    <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-lg rounded-pill fw-semibold">
                        <i class="fa-solid fa-right-to-bracket me-2"></i> Sign In to Existing Account
                    </a>
                </div>
                <small class="text-muted" style="font-size: 0.78rem;">Takes less than 1 minute to setup your professional portfolio.</small>
            </div>
        </div>
    </div>
</div>
@endauth

<!-- Share Opportunity to Social Feed Modal -->
@auth
<div class="modal fade" id="shareOpportunityModal" tabindex="-1" aria-labelledby="shareOpportunityModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom py-3">
                <h5 class="modal-title fw-bold text-dark" id="shareOpportunityModalLabel">
                    <i class="fa-solid fa-share-nodes text-success me-2"></i> Share Job on Professional Feed
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('posts.share-opportunity', $opportunity->id) }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    @if(Auth::user()->companies->isNotEmpty())
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Share As</label>
                            <select name="company_id" class="form-select">
                                <option value="">👤 Yourself ({{ Auth::user()->name }})</option>
                                @foreach(Auth::user()->companies as $myComp)
                                    <option value="{{ $myComp->id }}">🏢 {{ $myComp->name }} (Company)</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Add Your Commentary / Thoughts</label>
                        <textarea name="content" class="form-control" rows="3" placeholder="Share why this position is great or tag relevant skills..."></textarea>
                    </div>

                    <!-- Job Preview Card -->
                    <div class="card border border-primary-subtle rounded-3 p-3 bg-light">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <span class="badge bg-primary rounded-pill mb-1">JOB OPPORTUNITY</span>
                                <h6 class="fw-bold text-dark mb-0">{{ $opportunity->title }}</h6>
                                <small class="text-muted">{{ $opportunity->company->name ?? 'Platform Opportunity' }} &bull; {{ ucfirst($opportunity->location_type) }}</small>
                            </div>
                        </div>
                        <p class="text-secondary small mb-0">{{ Str::limit(strip_tags($opportunity->description), 120) }}</p>
                    </div>
                </div>
                <div class="modal-footer border-top py-3">
                    <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success px-4 fw-bold rounded-pill shadow-sm">
                        <i class="fa-solid fa-paper-plane me-1"></i> Post to Feed
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endauth
@endsection

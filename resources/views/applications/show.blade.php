@extends('layouts.app')

@section('content')
<div class="container py-4">
    <!-- Breadcrumb & Back Link -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('opportunities.applications', $application->opportunity_id) }}" class="text-decoration-none text-muted fw-medium">
            <i class="fa-solid fa-arrow-left me-1"></i> Back to Applicants List
        </a>
        <span class="badge {{ $application->is_public_applicant ? 'bg-success-subtle text-success border border-success' : 'bg-primary-subtle text-primary border border-primary' }} px-3 py-1">
            <i class="fa-solid {{ $application->is_public_applicant ? 'fa-globe' : 'fa-user-check' }} me-1"></i>
            {{ $application->is_public_applicant ? 'Public Guest Applicant' : 'Verified Platform User' }}
        </span>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
            <i class="fa-solid fa-circle-exclamation me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        <!-- Candidate Profile Column -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-circle {{ $application->is_public_applicant ? 'bg-success' : 'bg-primary' }} text-white d-flex align-items-center justify-content-center fw-bold fs-2" style="width: 72px; height: 72px;">
                                {{ strtoupper(substr($application->candidate_name, 0, 1)) }}
                            </div>
                            <div>
                                <h2 class="h4 fw-bold text-dark mb-1">{{ $application->candidate_name }}</h2>
                                <p class="text-secondary mb-1 fw-medium">
                                    @if($application->is_currently_employed)
                                        <i class="fa-solid fa-briefcase text-primary me-1"></i> {{ $application->current_designation }} at {{ $application->current_organization_name }}
                                    @else
                                        <i class="fa-solid fa-user-tie text-secondary me-1"></i> {{ $application->user->portfolio->position ?? 'Candidate Applicant' }}
                                    @endif
                                </p>
                                <div class="d-flex flex-wrap gap-2">
                                    <span class="badge bg-light text-dark border"><i class="fa-solid fa-envelope me-1 text-primary"></i> {{ $application->candidate_email }}</span>
                                    @if($application->candidate_phone)
                                        <span class="badge bg-light text-dark border"><i class="fa-solid fa-phone me-1 text-success"></i> {{ $application->candidate_phone }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="d-flex flex-wrap gap-2">
                            <button type="button" class="btn btn-outline-primary rounded-pill px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#emailCandidateModal">
                                <i class="fa-solid fa-paper-plane me-1"></i> Send Email
                            </button>

                            <a href="{{ route('applications.download-resume', $application->id) }}" class="btn btn-primary rounded-pill px-3 shadow-sm">
                                <i class="fa-solid fa-file-arrow-down me-1"></i> Download CV
                            </a>

                            @if($application->user && $application->user->portfolio)
                                <a href="{{ route('portfolio.show', $application->user->username) }}" target="_blank" class="btn btn-outline-secondary rounded-pill px-3">
                                    <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> Portfolio
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Detailed Candidate Application Info Block -->
                    <div class="p-3 bg-light rounded-3 border mb-4">
                        <h6 class="fw-bold text-dark mb-3"><i class="fa-solid fa-id-card text-primary me-2"></i> Application Profile Information</h6>
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <span class="text-muted d-block small">Full Name</span>
                                <strong class="text-dark">{{ $application->candidate_name }}</strong>
                            </div>
                            <div class="col-md-6">
                                <span class="text-muted d-block small">Email Address</span>
                                <strong class="text-dark">{{ $application->candidate_email }}</strong>
                            </div>
                            <div class="col-md-6">
                                <span class="text-muted d-block small">Contact Number</span>
                                <strong class="text-dark">{{ $application->candidate_phone ?? 'N/A' }}</strong>
                            </div>
                            <div class="col-md-6">
                                <span class="text-muted d-block small">Currently Employed</span>
                                @if($application->is_currently_employed)
                                    <span class="badge bg-success-subtle text-success border border-success">Yes</span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary border">No</span>
                                @endif
                            </div>

                            @if($application->is_currently_employed)
                                <div class="col-md-6">
                                    <span class="text-muted d-block small">Current Designation</span>
                                    <strong class="text-dark">{{ $application->current_designation ?? 'N/A' }}</strong>
                                </div>
                                <div class="col-md-6">
                                    <span class="text-muted d-block small">Current Organization Name</span>
                                    <strong class="text-dark">{{ $application->current_organization_name ?? 'N/A' }}</strong>
                                </div>
                                <div class="col-12">
                                    <span class="text-muted d-block small">Current Organization Address</span>
                                    <strong class="text-dark">{{ $application->current_organization_address ?? 'N/A' }}</strong>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Cover Letter Section -->
                    @if($application->cover_letter || $application->cover_letter_path)
                    <div class="p-4 bg-white border rounded-3 mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="fw-bold text-dark mb-0"><i class="fa-solid fa-comment-dots text-primary me-2"></i> Cover Letter / Candidate Statement</h6>
                            @if($application->cover_letter_path)
                                <a href="{{ route('applications.download-cover-letter', $application->id) }}" class="btn btn-sm btn-outline-secondary rounded-pill">
                                    <i class="fa-solid fa-download me-1"></i> Download Cover Letter File
                                </a>
                            @endif
                        </div>
                        @if($application->cover_letter)
                            <p class="text-secondary mb-0 mt-2 lh-lg">{!! nl2br(e($application->cover_letter)) !!}</p>
                        @endif
                    </div>
                    @endif

                    <!-- Verified Portfolio Skills Overview (for platform users) -->
                    @if($application->user && $application->user->portfolio && $application->user->portfolio->skills->isNotEmpty())
                    <h5 class="fw-bold text-dark mb-3"><i class="fa-solid fa-code text-primary me-2"></i> Verified Portfolio Skills</h5>
                    <div class="d-flex flex-wrap gap-2 mb-4">
                        @foreach($application->user->portfolio->skills as $sk)
                            <span class="badge bg-primary-subtle text-primary border border-primary px-3 py-2 fs-6">{{ $sk->name }} ({{ $sk->percentage }}%)</span>
                        @endforeach
                    </div>
                    @endif

                    <!-- Verified Experience History (for platform users) -->
                    @if($application->user && $application->user->portfolio && $application->user->portfolio->experiences->isNotEmpty())
                    <h5 class="fw-bold text-dark mb-3"><i class="fa-solid fa-briefcase text-primary me-2"></i> Portfolio Work History</h5>
                    <div class="d-flex flex-column gap-3 mb-4">
                        @foreach($application->user->portfolio->experiences as $exp)
                        <div class="p-3 border rounded-3 bg-white">
                            <h6 class="fw-bold mb-1">{{ $exp->position }} at {{ $exp->company }}</h6>
                            <span class="text-muted small d-block mb-2">{{ $exp->start_date }} - {{ $exp->end_date ?? 'Present' }}</span>
                            <p class="text-secondary small mb-0">{{ $exp->description }}</p>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>

            <!-- Recruiter Internal Notes Card -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="fw-bold text-dark mb-0"><i class="fa-solid fa-notes-medical me-2 text-primary"></i> Internal Recruiter Notes</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('applications.note', $application->id) }}" method="POST" class="mb-4">
                        @csrf
                        <div class="mb-2">
                            <textarea name="note" class="form-control" rows="3" required placeholder="Add confidential recruiter notes about this candidate..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-sm btn-primary rounded-pill px-3">Add Note</button>
                    </form>

                    <div class="d-flex flex-column gap-3">
                        @forelse($notes as $n)
                        <div class="p-3 bg-light rounded-3">
                            <div class="d-flex justify-content-between small text-muted mb-1">
                                <strong>{{ $n->author->name }}</strong>
                                <span>{{ $n->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-dark small mb-0">{{ $n->note }}</p>
                        </div>
                        @empty
                        <span class="text-muted small">No recruiter notes added yet.</span>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- ATS Status & Action Column -->
        <div class="col-lg-4">
            <!-- Status Card -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-dark mb-3"><i class="fa-solid fa-sliders text-primary me-2"></i> Application Status</h5>
                    
                    <form action="{{ route('applications.status', $application->id) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Current Hiring Stage</label>
                            <select name="status" class="form-select fw-bold text-dark">
                                <option value="applied" {{ $application->status == 'applied' ? 'selected' : '' }}>Applied</option>
                                <option value="under_review" {{ $application->status == 'under_review' ? 'selected' : '' }}>Under Review</option>
                                <option value="shortlisted" {{ $application->status == 'shortlisted' ? 'selected' : '' }}>Shortlisted</option>
                                <option value="interview" {{ $application->status == 'interview' ? 'selected' : '' }}>Interview Scheduled</option>
                                <option value="selected" {{ $application->status == 'selected' ? 'selected' : '' }}>Selected / Hired</option>
                                <option value="rejected" {{ $application->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Status Notes / Feedback</label>
                            <textarea name="status_notes" class="form-control" rows="3" placeholder="Optional status update comments...">{{ $application->status_notes }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 fw-bold rounded-pill shadow-sm mb-2">Update Stage</button>
                    </form>

                    <!-- Direct Send Email Quick Button -->
                    <button type="button" class="btn btn-outline-success w-100 fw-bold rounded-pill mb-2" data-bs-toggle="modal" data-bs-target="#emailCandidateModal">
                        <i class="fa-solid fa-envelope me-1"></i> Send Direct Email to Candidate
                    </button>

                    @if($application->user_id)
                    <form action="{{ route('applications.shortlist', $application->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-{{ $isShortlisted ? 'warning' : 'outline-warning' }} w-100 fw-bold rounded-pill">
                            <i class="fa-solid fa-star me-1"></i> {{ $isShortlisted ? 'Remove from Shortlist' : 'Shortlist Candidate' }}
                        </button>
                    </form>
                    @endif
                </div>
            </div>

            <!-- Download CV / Resume Card -->
            <div class="card border-0 shadow-sm rounded-4 mb-4 text-center p-4">
                <i class="fa-solid fa-file-pdf fa-3xl text-danger mb-3"></i>
                <h6 class="fw-bold text-dark mb-1">Candidate Resume / CV</h6>
                <p class="text-secondary small mb-3">View or download the candidate's submitted curriculum vitae file.</p>
                <a href="{{ route('applications.download-resume', $application->id) }}" class="btn btn-primary w-100 fw-bold rounded-pill shadow-sm">
                    <i class="fa-solid fa-download me-1"></i> Download CV / Resume
                </a>
            </div>

            <!-- Automated Match Score Explanation Card (for platform users) -->
            @if($matchResult)
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold text-dark mb-0">Automated Match Score</h6>
                        @php
                            $scoreVal = $matchResult['overall_score'];
                            $badgeClass = $scoreVal >= 75 ? 'bg-success text-white' : ($scoreVal >= 50 ? 'bg-warning text-dark' : 'bg-danger text-white');
                        @endphp
                        <span class="badge {{ $badgeClass }} rounded-pill px-3 py-2 fs-5 fw-bold">{{ $scoreVal }}%</span>
                    </div>

                    <div class="small">
                        <div class="d-flex justify-content-between mb-1"><span>Role Fit:</span> <strong>{{ $matchResult['breakdown']['role'] ?? 0 }}%</strong></div>
                        <div class="d-flex justify-content-between mb-1"><span>Skills:</span> <strong>{{ $matchResult['breakdown']['skills'] }}%</strong></div>
                        <div class="d-flex justify-content-between mb-1"><span>Relevant Exp:</span> <strong>{{ $matchResult['breakdown']['experience'] }}%</strong></div>
                        <div class="d-flex justify-content-between mb-1"><span>Location:</span> <strong>{{ $matchResult['breakdown']['location'] }}%</strong></div>
                        <div class="d-flex justify-content-between mb-1"><span>Education:</span> <strong>{{ $matchResult['breakdown']['education'] }}%</strong></div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal: Send Direct Email to Candidate -->
<div class="modal fade" id="emailCandidateModal" tabindex="-1" aria-labelledby="emailCandidateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom py-3">
                <h5 class="modal-title fw-bold text-dark" id="emailCandidateModalLabel">
                    <i class="fa-solid fa-paper-plane text-primary me-2"></i> Send Email to {{ $application->candidate_name }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('applications.send-email', $application->id) }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="alert alert-info border-0 rounded-3 small mb-3">
                        <i class="fa-solid fa-info-circle me-1"></i> Email will be sent directly to <strong>{{ $application->candidate_email }}</strong> from the platform on your behalf.
                    </div>

                    <!-- Quick Template Selector -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Quick Message Templates</label>
                        <div class="d-flex flex-wrap gap-2">
                            <button type="button" class="btn btn-sm btn-outline-success rounded-pill" onclick="applyTemplate('selected')">
                                <i class="fa-solid fa-check me-1"></i> Selection / Offer Template
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-danger rounded-pill" onclick="applyTemplate('rejected')">
                                <i class="fa-solid fa-xmark me-1"></i> Rejection Template
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-info rounded-pill" onclick="applyTemplate('interview')">
                                <i class="fa-solid fa-calendar-check me-1"></i> Interview Invitation Template
                            </button>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email Subject <span class="text-danger">*</span></label>
                        <input type="text" name="subject" id="emailSubject" class="form-control" required value="Application Update: {{ $application->opportunity->title }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email Body Message <span class="text-danger">*</span></label>
                        <textarea name="body" id="emailBody" class="form-control" rows="7" required placeholder="Write your email content here..."></textarea>
                    </div>

                    <input type="hidden" name="email_type" id="emailType" value="custom">
                </div>
                <div class="modal-footer border-top py-3">
                    <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">
                        <i class="fa-solid fa-paper-plane me-1"></i> Send Email Now
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function applyTemplate(type) {
    const candidateName = "{{ addslashes($application->candidate_name) }}";
    const jobTitle = "{{ addslashes($application->opportunity->title) }}";
    const companyName = "{{ addslashes($application->opportunity->company->name ?? 'our organization') }}";

    document.getElementById('emailType').value = type;

    if (type === 'selected') {
        document.getElementById('emailSubject').value = "Congratulations! Selection Update for " + jobTitle;
        document.getElementById('emailBody').value = "We are pleased to inform you that based on your experience and qualifications, you have been SELECTED for the position of " + jobTitle + " at " + companyName + "!\n\nOur team will follow up shortly with further details regarding your offer and next steps.\n\nCongratulations and welcome aboard!";
    } else if (type === 'rejected') {
        document.getElementById('emailSubject').value = "Application Status Update: " + jobTitle;
        document.getElementById('emailBody').value = "Thank you for taking the time to apply for the position of " + jobTitle + " at " + companyName + ".\n\nAfter careful review of all applications, we regret to inform you that we have decided to move forward with other candidates whose experience more closely aligns with our current requirements.\n\nWe appreciate your interest in our organization and wish you all the best in your career endeavors.";
    } else if (type === 'interview') {
        document.getElementById('emailSubject').value = "Interview Invitation: " + jobTitle;
        document.getElementById('emailBody').value = "Thank you for applying for the position of " + jobTitle + " at " + companyName + ".\n\nWe were impressed by your background and would like to invite you for an interview session to discuss your experience further.\n\nPlease let us know your availability over the upcoming days.";
    }
}
</script>
@endsection

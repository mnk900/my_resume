@extends('layouts.app')

@section('content')
<div class="container py-4">
    <!-- Breadcrumb & Back Link -->
    <div class="mb-3">
        <a href="{{ route('opportunities.applications', $application->opportunity_id) }}" class="text-decoration-none text-muted"><i class="fa-solid fa-arrow-left me-1"></i> Back to Applicants List</a>
    </div>

    <div class="row g-4">
        <!-- Candidate Profile Column -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold fs-2" style="width: 72px; height: 72px;">
                                {{ strtoupper(substr($application->user->name, 0, 1)) }}
                            </div>
                            <div>
                                <h2 class="h4 fw-bold text-dark mb-1">{{ $application->user->name }}</h2>
                                <p class="text-secondary mb-1 fw-medium">{{ $application->user->portfolio->position ?? 'Candidate Portfolio' }} &bull; {{ $application->user->portfolio->city ?? 'Location N/A' }}</p>
                                <span class="badge bg-light text-dark border"><i class="fa-solid fa-envelope me-1 text-primary"></i> {{ $application->user->email }}</span>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            @if($application->user->portfolio)
                                <a href="{{ route('portfolio.show', $application->user->username) }}" target="_blank" class="btn btn-outline-primary rounded-pill px-3"><i class="fa-solid fa-arrow-up-right-from-square me-1"></i> View Portfolio</a>
                                <a href="{{ route('cv.download.pdf', $application->user->username) }}" class="btn btn-light border rounded-pill px-3"><i class="fa-solid fa-file-pdf text-danger me-1"></i> Export CV</a>
                            @endif
                        </div>
                    </div>

                    <!-- Cover Letter -->
                    @if($application->cover_letter)
                    <div class="p-3 bg-light rounded-3 mb-4">
                        <h6 class="fw-bold text-dark mb-2"><i class="fa-solid fa-comment-dots text-primary me-2"></i> Candidate Note / Cover Letter</h6>
                        <p class="text-secondary mb-0">{!! nl2br(e($application->cover_letter)) !!}</p>
                    </div>
                    @endif

                    <!-- Skills Overview -->
                    @if($application->user->portfolio && $application->user->portfolio->skills->isNotEmpty())
                    <h5 class="fw-bold text-dark mb-3">Portfolio Skills</h5>
                    <div class="d-flex flex-wrap gap-2 mb-4">
                        @foreach($application->user->portfolio->skills as $sk)
                            <span class="badge bg-primary-subtle text-primary border border-primary px-3 py-2 fs-6">{{ $sk->name }} ({{ $sk->percentage }}%)</span>
                        @endforeach
                    </div>
                    @endif

                    <!-- Experience Overview -->
                    @if($application->user->portfolio && $application->user->portfolio->experiences->isNotEmpty())
                    <h5 class="fw-bold text-dark mb-3">Career History</h5>
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
                            <label class="form-label fw-semibold">Current Status</label>
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

                        <button type="submit" class="btn btn-primary w-100 fw-bold rounded-pill shadow-sm mb-2">Update Candidate Status</button>
                    </form>

                    <form action="{{ route('applications.shortlist', $application->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-{{ $isShortlisted ? 'warning' : 'outline-warning' }} w-100 fw-bold rounded-pill">
                            <i class="fa-solid fa-star me-1"></i> {{ $isShortlisted ? 'Remove from Shortlist' : 'Shortlist Candidate' }}
                        </button>
                    </form>
                </div>
            </div>

            <!-- Match Score Explanation Card -->
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
@endsection

@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 fw-bold text-dark mb-1"><i class="fa-solid fa-robot me-2 text-warning"></i> AI Mock Interviews Engine</h1>
            <p class="text-secondary mb-0">Practice role-specific interview questions tailored to jobs and your portfolio.</p>
        </div>
        <button type="button" class="btn btn-warning fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#startCustomInterviewModal">
            <i class="fa-solid fa-play me-1"></i> Start New Custom Mock Practice
        </button>
    </div>

    <!-- History Card -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-bottom py-3">
            <h5 class="fw-bold text-dark mb-0"><i class="fa-solid fa-clock-rotate-left me-2 text-primary"></i> Your Interview Practice History</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Job Title / Role</th>
                            <th>Date</th>
                            <th>Overall Score</th>
                            <th>Technical Score</th>
                            <th>Readiness Rating</th>
                            <th class="text-end">Report</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($interviews as $session)
                        <tr>
                            <td>
                                <div class="fw-bold text-dark">{{ $session->job_title }}</div>
                                <span class="text-muted small">{{ $session->opportunity->company->name ?? 'Custom Role Practice' }}</span>
                            </td>
                            <td><span class="text-secondary small">{{ $session->created_at->format('M d, Y') }}</span></td>
                            <td>
                                <span class="badge bg-primary rounded-pill px-3 py-1 fw-bold fs-6">{{ $session->overall_score ?? 'In Progress' }}{{ $session->overall_score ? '%' : '' }}</span>
                            </td>
                            <td>
                                <span class="badge bg-info text-dark rounded-pill px-3 py-1 fw-bold">{{ $session->technical_score ?? 'N/A' }}{{ $session->technical_score ? '%' : '' }}</span>
                            </td>
                            <td>
                                @if($session->readiness_rating === 'High')
                                    <span class="badge bg-success px-3 py-1">HIGH READINESS</span>
                                @elseif($session->readiness_rating === 'Moderate')
                                    <span class="badge bg-warning text-dark px-3 py-1">MODERATE</span>
                                @else
                                    <span class="badge bg-secondary px-3 py-1">{{ strtoupper($session->status) }}</span>
                                @endif
                            </td>
                            <td class="text-end">
                                @if($session->status === 'completed')
                                    <a href="{{ route('mock-interviews.report', $session->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">View Report</a>
                                @else
                                    <a href="{{ route('mock-interviews.take', $session->id) }}" class="btn btn-sm btn-warning rounded-pill px-3">Resume Test</a>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-robot fa-3x text-muted mb-3 d-block"></i>
                                You haven't taken any AI mock interview practice sessions yet.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Custom Mock Practice -->
<div class="modal fade" id="startCustomInterviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom py-3">
                <h5 class="modal-title fw-bold text-dark"><i class="fa-solid fa-robot text-warning me-2"></i> Start Custom Mock Interview Practice</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('mock-interviews.start') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Target Job Title / Position</label>
                        <input type="text" name="job_title" class="form-control" required placeholder="e.g. Full Stack Developer, Product Manager">
                    </div>
                    <p class="text-muted small mb-0">The AI engine will cross-reference your portfolio experience and skills to generate 5 targeted interview questions.</p>
                </div>
                <div class="modal-footer border-top py-3">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning px-4 fw-bold shadow-sm">Generate Interview</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

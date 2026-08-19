@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <span class="badge bg-success px-3 py-1 mb-2">Evaluation Report Generated</span>
            <h1 class="h2 fw-bold text-dark mb-1">Mock Interview Report: {{ $session->job_title }}</h1>
            <p class="text-secondary mb-0">Detailed AI evaluation of your responses, technical depth, and role readiness.</p>
        </div>
        <a href="{{ route('mock-interviews.index') }}" class="btn btn-outline-secondary">All Mock Sessions</a>
    </div>

    <!-- Summary Score Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-4 text-center bg-primary text-white">
                <span class="d-block text-white opacity-75 small uppercase font-bold">Overall Score</span>
                <h2 class="display-5 fw-bold mb-0 text-white">{{ $session->overall_score }}%</h2>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-4 text-center bg-info text-white">
                <span class="d-block text-white opacity-75 small">Technical Depth</span>
                <h2 class="display-5 fw-bold mb-0 text-white">{{ $session->technical_score }}%</h2>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-4 text-center bg-warning text-dark">
                <span class="d-block text-dark opacity-75 small">Communication</span>
                <h2 class="display-5 fw-bold mb-0 text-dark">{{ $session->communication_score }}%</h2>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-4 text-center bg-success text-white">
                <span class="d-block text-white opacity-75 small">Role Readiness</span>
                <h2 class="display-6 fw-bold mb-0 text-white">{{ strtoupper($session->readiness_rating ?? 'MODERATE') }}</h2>
            </div>
        </div>
    </div>

    <!-- Summary & Recommendations -->
    <div class="row g-4 mb-4">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 p-4">
                <h5 class="fw-bold text-dark mb-3"><i class="fa-solid fa-thumbs-up text-success me-2"></i> Key Strengths</h5>
                <ul class="mb-0 text-secondary lh-lg">
                    @if(isset($session->detailed_report['strengths']))
                        @foreach($session->detailed_report['strengths'] as $st)
                            <li>{{ $st }}</li>
                        @endforeach
                    @else
                        <li>Clear articulation of domain concepts.</li>
                    @endif
                </ul>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 p-4">
                <h5 class="fw-bold text-dark mb-3"><i class="fa-solid fa-lightbulb text-warning me-2"></i> Preparation & Improvement Areas</h5>
                <ul class="mb-0 text-secondary lh-lg">
                    @if(isset($session->detailed_report['weaknesses']))
                        @foreach($session->detailed_report['weaknesses'] as $w)
                            <li>{{ $w }}</li>
                        @endforeach
                    @else
                        <li>Structure answers using STAR (Situation, Task, Action, Result).</li>
                    @endif
                </ul>
            </div>
        </div>
    </div>

    <!-- Question by Question Evaluation Breakdown -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-bottom py-3">
            <h4 class="fw-bold text-dark mb-0 fs-5"><i class="fa-solid fa-list-check me-2 text-primary"></i> Question-by-Question Evaluation Breakdown</h4>
        </div>
        <div class="card-body p-4">
            <div class="d-flex flex-column gap-4">
                @foreach($session->questions as $q)
                <div class="p-4 border rounded-3 bg-light">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="badge bg-primary rounded-pill px-3 py-1">Question {{ $q->question_number }} ({{ ucfirst($q->question_category) }})</span>
                        <span class="badge bg-success rounded-pill px-3 py-1 fw-bold fs-6">Score: {{ $q->score }}%</span>
                    </div>
                    <h6 class="fw-bold text-dark mb-3">{{ $q->question_text }}</h6>

                    <div class="mb-3 p-3 bg-white border rounded-3">
                        <strong class="d-block small text-muted mb-1">Your Answer:</strong>
                        <p class="text-secondary mb-0 small">{!! nl2br(e($q->user_answer)) !!}</p>
                    </div>

                    <div class="p-3 bg-primary-subtle text-primary border border-primary rounded-3 mb-2">
                        <strong class="d-block small mb-1"><i class="fa-solid fa-robot me-1"></i> AI Feedback & Diagnosis:</strong>
                        <span class="small">{{ $q->feedback }}</span>
                    </div>

                    @if($q->sample_improved_answer)
                    <div class="p-3 bg-warning-subtle text-dark border border-warning rounded-3">
                        <strong class="d-block small mb-1"><i class="fa-solid fa-star me-1"></i> Recommended Model Answer:</strong>
                        <span class="small text-secondary">{!! nl2br(e($q->sample_improved_answer)) !!}</span>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection

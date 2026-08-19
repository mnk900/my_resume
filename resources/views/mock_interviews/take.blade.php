@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-dark text-white py-3 d-flex justify-content-between align-items-center">
                    <h2 class="h5 fw-bold mb-0 text-white"><i class="fa-solid fa-robot text-warning me-2"></i> AI Mock Interview: {{ $session->job_title }}</h2>
                    <span class="badge bg-warning text-dark px-3 py-1">5 Questions Session</span>
                </div>
                <div class="card-body p-4">
                    <p class="text-secondary mb-4">Answer each question thoroughly. Provide real-world examples from your background. The AI engine will evaluate technical clarity, STAR method alignment, and communication depth.</p>

                    <form action="{{ route('mock-interviews.submit', $session->id) }}" method="POST">
                        @csrf

                        @foreach($session->questions as $index => $q)
                        <div class="card border-0 shadow-sm bg-light rounded-3 p-4 mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge bg-primary rounded-pill px-3 py-1">Question {{ $q->question_number }} of 5</span>
                                <span class="badge bg-secondary-subtle text-dark border">{{ strtoupper(str_replace('_', ' ', $q->question_category)) }}</span>
                            </div>
                            <h5 class="fw-bold text-dark mb-3">{{ $q->question_text }}</h5>

                            <div class="mb-2">
                                <label class="form-label fw-semibold text-secondary small">Your Answer:</label>
                                <textarea name="answers[{{ $q->id }}]" class="form-control bg-white" rows="4" required placeholder="Type your response here... (Aim for at least 50-100 words)"></textarea>
                            </div>
                        </div>
                        @endforeach

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-warning btn-lg fw-bold rounded-pill px-5 shadow-sm"><i class="fa-solid fa-paper-plane me-2"></i> Submit Answers & Generate Evaluation Report</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

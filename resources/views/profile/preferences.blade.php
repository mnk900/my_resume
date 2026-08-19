@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h2 class="h4 fw-bold text-dark mb-0"><i class="fa-solid fa-sliders me-2 text-primary"></i> Career & Job Preferences</h2>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('preferences.update') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Current Availability Status</label>
                            <select name="availability" class="form-select fw-bold text-dark">
                                <option value="open_to_work" {{ $preference->availability === 'open_to_work' ? 'selected' : '' }}>Open to Work (Actively Job Hunting)</option>
                                <option value="open_to_opportunities" {{ $preference->availability === 'open_to_opportunities' ? 'selected' : '' }}>Open to Opportunities (Casually Exploring)</option>
                                <option value="freelance" {{ $preference->availability === 'freelance' ? 'selected' : '' }}>Available for Freelance / Contract</option>
                                <option value="internship" {{ $preference->availability === 'internship' ? 'selected' : '' }}>Available for Internship</option>
                                <option value="not_looking" {{ $preference->availability === 'not_looking' ? 'selected' : '' }}>Not Looking</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Preferred Job Titles (Comma separated)</label>
                            <input type="text" name="preferred_titles" class="form-control" value="{{ is_array($preference->preferred_titles) ? implode(', ', $preference->preferred_titles) : '' }}" placeholder="e.g. Senior Software Engineer, Technical Lead">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Preferred Industries (Comma separated)</label>
                            <input type="text" name="preferred_industries" class="form-control" value="{{ is_array($preference->preferred_industries) ? implode(', ', $preference->preferred_industries) : '' }}" placeholder="e.g. Information Technology, FinTech, E-Commerce">
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Remote Work Preference</label>
                                <select name="remote_preference" class="form-select">
                                    <option value="any" {{ $preference->remote_preference === 'any' ? 'selected' : '' }}>Any (Remote, Hybrid, or On-Site)</option>
                                    <option value="remote_only" {{ $preference->remote_preference === 'remote_only' ? 'selected' : '' }}>Remote Only</option>
                                    <option value="hybrid" {{ $preference->remote_preference === 'hybrid' ? 'selected' : '' }}>Hybrid</option>
                                    <option value="onsite" {{ $preference->remote_preference === 'onsite' ? 'selected' : '' }}>On-Site</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Expected Minimum Salary ($)</label>
                                <input type="number" name="salary_expectation_min" class="form-control" value="{{ $preference->salary_expectation_min }}" placeholder="e.g. 60000">
                            </div>
                        </div>

                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" name="willing_to_relocate" id="relocate" value="1" {{ $preference->willing_to_relocate ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="relocate">
                                Willing to Relocate for the right opportunity
                            </label>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary px-4 fw-bold shadow-sm">Save Career Preferences</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

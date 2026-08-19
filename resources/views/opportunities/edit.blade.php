@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                    <h2 class="h4 fw-bold text-dark mb-0"><i class="fa-solid fa-pen-to-square me-2 text-primary"></i> Edit Opportunity / Job</h2>
                    <form action="{{ route('opportunities.destroy', $opportunity->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete this job posting?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill"><i class="fa-solid fa-trash-can me-1"></i> Delete Job</button>
                    </form>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('opportunities.update', $opportunity->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        @if($companies->isNotEmpty())
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Posting Company / Organization</label>
                            <select name="company_id" class="form-select">
                                <option value="">Platform Opportunity (Individual / Direct)</option>
                                @foreach($companies as $comp)
                                    <option value="{{ $comp->id }}" {{ old('company_id', $opportunity->company_id) == $comp->id ? 'selected' : '' }}>{{ $comp->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        <div class="row g-3 mb-3">
                            <div class="col-md-8">
                                <label class="form-label fw-semibold">Opportunity Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $opportunity->title) }}" required placeholder="e.g. Senior Laravel Engineer">
                                @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Type <span class="text-danger">*</span></label>
                                <select name="type" class="form-select" required>
                                    <option value="job" {{ old('type', $opportunity->type) === 'job' ? 'selected' : '' }}>Job</option>
                                    <option value="internship" {{ old('type', $opportunity->type) === 'internship' ? 'selected' : '' }}>Internship</option>
                                    <option value="freelance" {{ old('type', $opportunity->type) === 'freelance' ? 'selected' : '' }}>Freelance</option>
                                    <option value="training" {{ old('type', $opportunity->type) === 'training' ? 'selected' : '' }}>Training / Workshop</option>
                                    <option value="scholarship" {{ old('type', $opportunity->type) === 'scholarship' ? 'selected' : '' }}>Scholarship</option>
                                    <option value="event" {{ old('type', $opportunity->type) === 'event' ? 'selected' : '' }}>Event</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Description <span class="text-danger">*</span></label>
                            <textarea name="description" class="form-control js-summernote @error('description') is-invalid @enderror" rows="5" required placeholder="Detailed job overview and requirements...">{{ old('description', $opportunity->description) }}</textarea>
                            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Responsibilities</label>
                            <textarea name="responsibilities" class="form-control js-summernote" rows="3" placeholder="Key day-to-day responsibilities...">{{ old('responsibilities', $opportunity->responsibilities) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Required Background & Education</label>
                            <textarea name="education_required" class="form-control js-summernote" rows="3" placeholder="Educational qualification or domain background required (e.g. BS Computer Science or equivalent experience)...">{{ old('education_required', $opportunity->education_required) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Required Skills (Comma separated)</label>
                            <textarea name="skills" class="form-control js-summernote-skills" rows="2" placeholder="PHP, Laravel, MySQL, Bootstrap, REST APIs">{{ old('skills', $existingSkills) }}</textarea>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Min Experience (Years)</label>
                                <input type="number" name="min_experience" class="form-control" value="{{ old('min_experience', $opportunity->min_experience) }}" min="0" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Location Type</label>
                                <select name="location_type" class="form-select">
                                    <option value="onsite" {{ old('location_type', $opportunity->location_type) === 'onsite' ? 'selected' : '' }}>On-Site</option>
                                    <option value="remote" {{ old('location_type', $opportunity->location_type) === 'remote' ? 'selected' : '' }}>Remote</option>
                                    <option value="hybrid" {{ old('location_type', $opportunity->location_type) === 'hybrid' ? 'selected' : '' }}>Hybrid</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Employment Type</label>
                                <select name="employment_type" class="form-select">
                                    <option value="full-time" {{ old('employment_type', $opportunity->employment_type) === 'full-time' ? 'selected' : '' }}>Full-Time</option>
                                    <option value="part-time" {{ old('employment_type', $opportunity->employment_type) === 'part-time' ? 'selected' : '' }}>Part-Time</option>
                                    <option value="contract" {{ old('employment_type', $opportunity->employment_type) === 'contract' ? 'selected' : '' }}>Contract</option>
                                    <option value="freelance" {{ old('employment_type', $opportunity->employment_type) === 'freelance' ? 'selected' : '' }}>Freelance</option>
                                    <option value="internship" {{ old('employment_type', $opportunity->employment_type) === 'internship' ? 'selected' : '' }}>Internship</option>
                                </select>
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">City</label>
                                <input type="text" name="city" class="form-control" value="{{ old('city', $opportunity->city) }}" placeholder="e.g. Gilgit">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Vacancies Count</label>
                                <input type="number" name="vacancies_count" class="form-control" value="{{ old('vacancies_count', $opportunity->vacancies_count) }}" min="1" required>
                            </div>
                        </div>

                        <!-- Salary & Compensation Row -->
                        <div class="row g-3 mb-3">
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Currency</label>
                                <select name="salary_currency" class="form-select">
                                    <option value="PKR" {{ old('salary_currency', $opportunity->salary_currency ?? 'PKR') === 'PKR' ? 'selected' : '' }}>PKR (Rs.)</option>
                                    <option value="USD" {{ old('salary_currency', $opportunity->salary_currency ?? 'PKR') === 'USD' ? 'selected' : '' }}>USD ($)</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Min Salary</label>
                                <input type="number" name="salary_min" class="form-control" value="{{ old('salary_min', $opportunity->salary_min) }}" placeholder="e.g. 100000">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Max Salary (Optional)</label>
                                <input type="number" name="salary_max" class="form-control" value="{{ old('salary_max', $opportunity->salary_max) }}" placeholder="e.g. 150000">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Salary Period</label>
                                <select name="salary_period" class="form-select">
                                    <option value="monthly" {{ old('salary_period', $opportunity->salary_period ?? 'monthly') === 'monthly' ? 'selected' : '' }}>Monthly</option>
                                    <option value="yearly" {{ old('salary_period', $opportunity->salary_period ?? 'monthly') === 'yearly' ? 'selected' : '' }}>Yearly</option>
                                    <option value="hourly" {{ old('salary_period', $opportunity->salary_period ?? 'monthly') === 'hourly' ? 'selected' : '' }}>Hourly</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" name="is_internal_application" id="internalApp" value="1" {{ old('is_internal_application', $opportunity->is_internal_application) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="internalApp">
                                Enable Direct Applications on MyResume.cloud Platform
                            </label>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('opportunities.show', $opportunity->slug) }}" class="btn btn-light">Cancel</a>
                            <button type="submit" class="btn btn-primary px-4 fw-bold shadow-sm">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css" rel="stylesheet">
<style>
    .note-editor.note-frame {
        border: 1px solid #dee2e6;
        border-radius: 0.5rem;
        overflow: hidden;
    }
    .note-editor .note-toolbar {
        background-color: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
    }
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof $ !== 'undefined' && $.fn.summernote) {
            $('.js-summernote').summernote({
                height: 180,
                toolbar: [
                    ['style', ['style', 'bold', 'italic', 'underline', 'clear']],
                    ['font', ['strikethrough']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link', 'hr']],
                    ['view', ['codeview', 'undo', 'redo']]
                ]
            });

            $('.js-summernote-skills').summernote({
                height: 90,
                toolbar: [
                    ['style', ['bold', 'italic', 'clear']],
                    ['para', ['ul', 'paragraph']],
                    ['view', ['codeview']]
                ]
            });
        }
    });
</script>
@endpush
@endsection

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
                    @if ($errors->any())
                        <div class="alert alert-danger border-0 shadow-sm rounded-3 mb-4 p-3">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fa-solid fa-circle-exclamation fa-lg me-2 text-danger"></i>
                                <h6 class="fw-bold mb-0 text-danger">Please fix the following validation errors before updating:</h6>
                            </div>
                            <ul class="mb-0 ps-3 small text-danger">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('opportunities.update', $opportunity->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        @if($companies->isNotEmpty())
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Posting Company / Organization</label>
                            <select name="company_id" class="form-select @error('company_id') is-invalid @enderror">
                                <option value="">Platform Opportunity (Individual / Direct)</option>
                                @foreach($companies as $comp)
                                    <option value="{{ $comp->id }}" {{ old('company_id', $opportunity->company_id) == $comp->id ? 'selected' : '' }}>{{ $comp->name }}</option>
                                @endforeach
                            </select>
                            @error('company_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
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
                                <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                                    <option value="job" {{ old('type', $opportunity->type) === 'job' ? 'selected' : '' }}>Job</option>
                                    <option value="internship" {{ old('type', $opportunity->type) === 'internship' ? 'selected' : '' }}>Internship</option>
                                    <option value="freelance" {{ old('type', $opportunity->type) === 'freelance' ? 'selected' : '' }}>Freelance</option>
                                    <option value="training" {{ old('type', $opportunity->type) === 'training' ? 'selected' : '' }}>Training / Workshop</option>
                                    <option value="scholarship" {{ old('type', $opportunity->type) === 'scholarship' ? 'selected' : '' }}>Scholarship</option>
                                    <option value="event" {{ old('type', $opportunity->type) === 'event' ? 'selected' : '' }}>Event</option>
                                </select>
                                @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Description <span class="text-danger">*</span></label>
                            <textarea name="description" class="form-control js-summernote @error('description') is-invalid @enderror" rows="5" required placeholder="Detailed job overview and requirements...">{{ old('description', $opportunity->description) }}</textarea>
                            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Responsibilities</label>
                            <textarea name="responsibilities" class="form-control js-summernote @error('responsibilities') is-invalid @enderror" rows="3" placeholder="Key day-to-day responsibilities...">{{ old('responsibilities', $opportunity->responsibilities) }}</textarea>
                            @error('responsibilities')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Required Background & Education</label>
                            <textarea name="education_required" class="form-control js-summernote @error('education_required') is-invalid @enderror" rows="3" placeholder="Educational qualification or domain background required (e.g. BS Computer Science or equivalent experience)...">{{ old('education_required', $opportunity->education_required) }}</textarea>
                            @error('education_required')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Required Skills (Comma separated)</label>
                            <textarea name="skills" class="form-control js-summernote-skills @error('skills') is-invalid @enderror" rows="2" placeholder="PHP, Laravel, MySQL, Bootstrap, REST APIs">{{ old('skills', $existingSkills) }}</textarea>
                            @error('skills')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Min Experience (Years) <span class="text-danger">*</span></label>
                                <input type="number" name="min_experience" class="form-control @error('min_experience') is-invalid @enderror" value="{{ old('min_experience', $opportunity->min_experience) }}" min="0" required>
                                @error('min_experience')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Location Type <span class="text-danger">*</span></label>
                                <select name="location_type" class="form-select @error('location_type') is-invalid @enderror" required>
                                    <option value="onsite" {{ old('location_type', $opportunity->location_type) === 'onsite' ? 'selected' : '' }}>On-Site</option>
                                    <option value="remote" {{ old('location_type', $opportunity->location_type) === 'remote' ? 'selected' : '' }}>Remote</option>
                                    <option value="hybrid" {{ old('location_type', $opportunity->location_type) === 'hybrid' ? 'selected' : '' }}>Hybrid</option>
                                </select>
                                @error('location_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Employment Type <span class="text-danger">*</span></label>
                                <select name="employment_type" class="form-select @error('employment_type') is-invalid @enderror" required>
                                    <option value="full-time" {{ old('employment_type', $opportunity->employment_type) === 'full-time' ? 'selected' : '' }}>Full-Time</option>
                                    <option value="part-time" {{ old('employment_type', $opportunity->employment_type) === 'part-time' ? 'selected' : '' }}>Part-Time</option>
                                    <option value="contract" {{ old('employment_type', $opportunity->employment_type) === 'contract' ? 'selected' : '' }}>Contract</option>
                                    <option value="freelance" {{ old('employment_type', $opportunity->employment_type) === 'freelance' ? 'selected' : '' }}>Freelance</option>
                                    <option value="internship" {{ old('employment_type', $opportunity->employment_type) === 'internship' ? 'selected' : '' }}>Internship</option>
                                </select>
                                @error('employment_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">City</label>
                                <input type="text" name="city" class="form-control @error('city') is-invalid @enderror" value="{{ old('city', $opportunity->city) }}" placeholder="e.g. Gilgit">
                                @error('city')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Vacancies Count <span class="text-danger">*</span></label>
                                <input type="number" name="vacancies_count" class="form-control @error('vacancies_count') is-invalid @enderror" value="{{ old('vacancies_count', $opportunity->vacancies_count) }}" min="1" required>
                                @error('vacancies_count')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Application Deadline / Expiry Date</label>
                                <input type="date" name="application_deadline" class="form-control @error('application_deadline') is-invalid @enderror" value="{{ old('application_deadline', $opportunity->application_deadline ? $opportunity->application_deadline->format('Y-m-d') : '') }}">
                                @error('application_deadline')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <!-- Compensation Structure Selection -->
                        <div class="card border-0 bg-light p-3 mb-3 rounded-3 border">
                            <label class="form-label fw-bold text-dark mb-2"><i class="fa-solid fa-coins text-primary me-2"></i> Compensation Structure</label>
                            <div class="row g-3 mb-3">
                                <div class="col-md-12">
                                    <div class="btn-group w-100" role="group" aria-label="Compensation Type">
                                        <input type="radio" class="btn-check" name="compensation_type" id="comp_salary" value="salary" {{ old('compensation_type', $opportunity->compensation_type ?? 'salary') === 'salary' ? 'checked' : '' }} onchange="toggleCompFields()">
                                        <label class="btn btn-outline-primary py-2 fw-semibold" for="comp_salary">
                                            <i class="fa-solid fa-money-bill-wave me-1"></i> Fixed Salary
                                        </label>

                                        <input type="radio" class="btn-check" name="compensation_type" id="comp_revshare" value="revenue_share" {{ old('compensation_type', $opportunity->compensation_type ?? 'salary') === 'revenue_share' ? 'checked' : '' }} onchange="toggleCompFields()">
                                        <label class="btn btn-outline-primary py-2 fw-semibold" for="comp_revshare">
                                            <i class="fa-solid fa-chart-line me-1"></i> Revenue Share (%)
                                        </label>

                                        <input type="radio" class="btn-check" name="compensation_type" id="comp_hybrid" value="hybrid" {{ old('compensation_type', $opportunity->compensation_type ?? 'salary') === 'hybrid' ? 'checked' : '' }} onchange="toggleCompFields()">
                                        <label class="btn btn-outline-primary py-2 fw-semibold" for="comp_hybrid">
                                            <i class="fa-solid fa-layer-group me-1"></i> Hybrid (Salary + Revenue Share)
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Salary Inputs -->
                            <div id="salary_fields_box" class="row g-3">
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

                            <!-- Revenue Share Percentage Inputs -->
                            <div id="revshare_fields_box" class="row g-3 mt-1 d-none">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Min Revenue Share (%)</label>
                                    <div class="input-group">
                                        <input type="number" step="0.01" name="revenue_share_min" class="form-control" value="{{ old('revenue_share_min', $opportunity->revenue_share_min) }}" placeholder="e.g. 5.00">
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Max Revenue Share (%)</label>
                                    <div class="input-group">
                                        <input type="number" step="0.01" name="revenue_share_max" class="form-control" value="{{ old('revenue_share_max', $opportunity->revenue_share_max) }}" placeholder="e.g. 15.00">
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
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
    function toggleCompFields() {
        const selected = document.querySelector('input[name="compensation_type"]:checked')?.value || 'salary';
        const salaryBox = document.getElementById('salary_fields_box');
        const revBox = document.getElementById('revshare_fields_box');

        if (selected === 'salary') {
            salaryBox.classList.remove('d-none');
            revBox.classList.add('d-none');
        } else if (selected === 'revenue_share') {
            salaryBox.classList.add('d-none');
            revBox.classList.remove('d-none');
        } else if (selected === 'hybrid') {
            salaryBox.classList.remove('d-none');
            revBox.classList.remove('d-none');
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        toggleCompFields();
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

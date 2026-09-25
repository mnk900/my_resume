@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-9 col-xl-8">

            <!-- Breadcrumb Navigation -->
            <div class="mb-3">
                <a href="{{ route('opportunities.show', $opportunity->slug) }}" class="text-decoration-none text-muted fw-medium">
                    <i class="fa-solid fa-arrow-left me-1"></i> Back to Position Overview
                </a>
            </div>

            <!-- Job Opportunity Summary Card -->
            <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                <div class="card-body p-4 bg-white">
                    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-3">
                        <div>
                            <span class="badge bg-primary-subtle text-primary border border-primary px-3 py-1 mb-2 fs-6 fw-semibold">
                                {{ strtoupper($opportunity->type) }}
                            </span>
                            <h1 class="h3 fw-bold text-dark mb-1">{{ $opportunity->title }}</h1>
                            <p class="text-secondary fw-medium mb-0">
                                @if($opportunity->company)
                                    <span class="fw-bold text-primary">{{ $opportunity->company->name }}</span> &bull;
                                @else
                                    <span class="fw-bold">Platform Posting</span> &bull;
                                @endif
                                <i class="fa-solid fa-location-dot text-danger"></i> {{ ucfirst($opportunity->location_type) }} ({{ $opportunity->city ?? 'Global' }})
                            </p>
                        </div>
                        @php
                            $deadlineBadge = $opportunity->deadline_badge;
                        @endphp
                        <div>
                            <span class="badge {{ $deadlineBadge['class'] }} px-3 py-2 fs-6">
                                <i class="{{ $deadlineBadge['icon'] }} me-1"></i> {{ $deadlineBadge['short_label'] }}
                            </span>
                        </div>
                    </div>

                    <!-- Compact Specs Grid -->
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 p-3 bg-light rounded-3 border">
                        <div><span class="text-muted d-block small">Employment Type</span> <strong class="text-dark">{{ ucfirst($opportunity->employment_type) }}</strong></div>
                        <div><span class="text-muted d-block small">Min Experience</span> <strong class="text-dark">{{ $opportunity->min_experience }} Years</strong></div>
                        <div><span class="text-muted d-block small">Compensation</span> <strong class="text-dark">{{ $opportunity->compensation_text }}</strong></div>
                        <div><span class="text-muted d-block small">Vacancies</span> <strong class="text-dark">{{ $opportunity->vacancies_count }} Position(s)</strong></div>
                    </div>
                </div>

                <!-- Social Share Bar for Job Owner & Visitors -->
                <div class="card-footer bg-light border-top px-4 py-3 d-flex flex-wrap align-items-center justify-content-between gap-3">
                    <div class="small text-secondary fw-bold">
                        <i class="fa-solid fa-share-nodes text-primary me-1"></i> Share Application Form on Social Media:
                    </div>
                    @php
                        $shareUrl = rawurlencode(route('opportunities.apply_public', $opportunity->slug));
                        $shareTitle = rawurlencode("Hiring: " . $opportunity->title . ($opportunity->company ? " at " . $opportunity->company->name : "") . " - Apply Now!");
                    @endphp
                    <div class="d-flex align-items-center gap-2">
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ $shareUrl }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-circle" title="Share on LinkedIn" style="width: 36px; height: 36px; padding: 6px 0;">
                            <i class="fa-brands fa-linkedin-in"></i>
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ $shareUrl }}&text={{ $shareTitle }}" target="_blank" class="btn btn-sm btn-outline-dark rounded-circle" title="Share on X / Twitter" style="width: 36px; height: 36px; padding: 6px 0;">
                            <i class="fa-brands fa-x-twitter"></i>
                        </a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-circle" title="Share on Facebook" style="width: 36px; height: 36px; padding: 6px 0;">
                            <i class="fa-brands fa-facebook-f"></i>
                        </a>
                        <a href="https://api.whatsapp.com/send?text={{ $shareTitle }}%20{{ $shareUrl }}" target="_blank" class="btn btn-sm btn-outline-success rounded-circle" title="Share on WhatsApp" style="width: 36px; height: 36px; padding: 6px 0;">
                            <i class="fa-brands fa-whatsapp"></i>
                        </a>
                        <button type="button" onclick="copyApplyLink('{{ route('opportunities.apply_public', $opportunity->slug) }}')" class="btn btn-sm btn-outline-secondary rounded-pill px-3" title="Copy Link">
                            <i class="fa-solid fa-link me-1"></i> Copy Link
                        </button>
                    </div>
                </div>
            </div>

            <!-- Application Form Card -->
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-bottom p-4">
                    <h2 class="h4 fw-bold text-dark mb-1">Candidate Job Application Form</h2>
                    <p class="text-secondary small mb-0">Please complete the form below. Fields marked with <span class="text-danger">*</span> are mandatory.</p>
                </div>

                <div class="card-body p-4 p-md-5">

                    @if(session('success'))
                        <div class="alert alert-success border-0 shadow-sm rounded-4 p-4 mb-4">
                            <div class="d-flex align-items-start gap-3">
                                <div class="rounded-circle bg-success text-white p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                    <i class="fa-solid fa-circle-check fa-xl"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold text-success mb-1">Application Submitted Successfully!</h5>
                                    <p class="text-dark mb-2">{{ session('success') }}</p>
                                    <small class="text-muted">A confirmation email has also been dispatched to your email address.</small>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger border-0 shadow-sm rounded-3 mb-4">
                            <h6 class="fw-bold mb-2"><i class="fa-solid fa-triangle-exclamation me-1"></i> Please correct the following errors:</h6>
                            <ul class="mb-0 ps-3">
                                @foreach($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if($opportunity->isExpired())
                        <div class="alert alert-warning border-0 shadow-sm rounded-4 p-4 text-center">
                            <i class="fa-solid fa-lock fa-2xl text-warning mb-3 d-block"></i>
                            <h5 class="fw-bold text-dark mb-1">Applications are Closed</h5>
                            <p class="text-secondary mb-0">The application deadline for this position has passed. Thank you for your interest.</p>
                        </div>
                    @else
                        <form action="{{ route('opportunities.apply_public.store', $opportunity->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <!-- Section 1: Mandatory Personal & Employment Info -->
                            <div class="mb-4">
                                <h5 class="fw-bold text-dark mb-3 border-bottom pb-2">
                                    <span class="badge bg-primary rounded-circle me-2">1</span> Personal & Employment Details
                                </h5>

                                <div class="row g-3">
                                    <!-- Full Name -->
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold text-dark">Full Name <span class="text-danger">*</span></label>
                                        <input type="text" name="applicant_name" class="form-control form-control-lg @error('applicant_name') is-invalid @enderror" value="{{ old('applicant_name', Auth::check() ? Auth::user()->name : '') }}" required placeholder="e.g. John Doe">
                                        @error('applicant_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Email Address -->
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold text-dark">Email Address <span class="text-danger">*</span></label>
                                        <input type="email" name="applicant_email" class="form-control form-control-lg @error('applicant_email') is-invalid @enderror" value="{{ old('applicant_email', Auth::check() ? Auth::user()->email : '') }}" required placeholder="name@example.com">
                                        @error('applicant_email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Contact Number -->
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold text-dark">Contact Number <span class="text-danger">*</span></label>
                                        <input type="text" name="applicant_phone" class="form-control form-control-lg @error('applicant_phone') is-invalid @enderror" value="{{ old('applicant_phone') }}" required placeholder="+92 300 1234567">
                                        @error('applicant_phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Currently Employed? -->
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold text-dark d-block">Are you currently employed? <span class="text-danger">*</span></label>
                                        <div class="btn-group w-100" role="group">
                                            <input type="radio" class="btn-check" name="is_currently_employed" id="employed_no" value="0" {{ old('is_currently_employed', '0') == '0' ? 'checked' : '' }} onchange="toggleEmploymentFields(false)">
                                            <label class="btn btn-outline-secondary py-2.5" for="employed_no"><i class="fa-solid fa-xmark me-1"></i> No</label>

                                            <input type="radio" class="btn-check" name="is_currently_employed" id="employed_yes" value="1" {{ old('is_currently_employed') == '1' ? 'checked' : '' }} onchange="toggleEmploymentFields(true)">
                                            <label class="btn btn-outline-primary py-2.5" for="employed_yes"><i class="fa-solid fa-check me-1"></i> Yes</label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Conditional Employment Fields -->
                                <div id="employmentFieldsBlock" class="p-3 bg-light border rounded-3 mt-3 {{ old('is_currently_employed') == '1' ? '' : 'd-none' }}">
                                    <h6 class="fw-bold text-primary mb-3"><i class="fa-solid fa-building me-1"></i> Current Organization Information</h6>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold text-dark">Your Current Designation <span class="text-danger">*</span></label>
                                            <input type="text" name="current_designation" id="current_designation" class="form-control @error('current_designation') is-invalid @enderror" value="{{ old('current_designation') }}" placeholder="e.g. Senior Software Engineer">
                                            @error('current_designation')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold text-dark">Organization Name <span class="text-danger">*</span></label>
                                            <input type="text" name="current_organization_name" id="current_organization_name" class="form-control @error('current_organization_name') is-invalid @enderror" value="{{ old('current_organization_name') }}" placeholder="e.g. Acme Corporation">
                                            @error('current_organization_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-12">
                                            <label class="form-label fw-semibold text-dark">Organization Address <span class="text-danger">*</span></label>
                                            <input type="text" name="current_organization_address" id="current_organization_address" class="form-control @error('current_organization_address') is-invalid @enderror" value="{{ old('current_organization_address') }}" placeholder="e.g. Tech Park, Floor 4, Islamabad, Pakistan">
                                            @error('current_organization_address')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Section 2: Optional Cover Letter Upload -->
                            <div class="mb-4">
                                <h5 class="fw-bold text-dark mb-3 border-bottom pb-2">
                                    <span class="badge bg-secondary rounded-circle me-2">2</span> Cover Letter <small class="text-muted fw-normal fs-6">(Optional)</small>
                                </h5>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold text-dark">Upload Cover Letter File</label>
                                        <input type="file" name="cover_letter_file" class="form-control @error('cover_letter_file') is-invalid @enderror" accept=".pdf,.doc,.docx,.txt">
                                        <div class="form-text">Accepted formats: PDF, DOC, DOCX, TXT (Max 5MB)</div>
                                        @error('cover_letter_file')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold text-dark">OR Write Cover Note</label>
                                        <textarea name="cover_letter" class="form-control" rows="3" placeholder="Brief statement about your interest in this role...">{{ old('cover_letter') }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Section 3: Mandatory CV / Resume Upload -->
                            <div class="mb-4">
                                <h5 class="fw-bold text-dark mb-3 border-bottom pb-2">
                                    <span class="badge bg-primary rounded-circle me-2">3</span> Upload CV / Resume <span class="text-danger">*</span>
                                </h5>

                                <div class="p-4 border border-2 border-dashed rounded-4 text-center bg-light">
                                    <i class="fa-solid fa-cloud-arrow-up fa-3xl text-primary mb-3"></i>
                                    <h6 class="fw-bold text-dark mb-1">Upload Your Latest CV / Resume</h6>
                                    <p class="text-muted small mb-3">Upload your curriculum vitae in PDF, DOC, or DOCX format (Max 10MB).</p>

                                    <div class="col-md-8 col-lg-6 mx-auto">
                                        <input type="file" name="resume_file" class="form-control form-control-lg @error('resume_file') is-invalid @enderror" accept=".pdf,.doc,.docx" required>
                                        @error('resume_file')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Submission Button -->
                            <div class="pt-3">
                                <button type="submit" class="btn btn-primary btn-lg w-100 rounded-pill fw-bold shadow-sm py-3">
                                    <i class="fa-solid fa-paper-plane me-2"></i> Submit Application Now
                                </button>
                                <p class="text-center text-muted small mt-2">
                                    By submitting, you confirm that the information provided is accurate and complete.
                                </p>
                            </div>
                        </form>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>

<script>
function toggleEmploymentFields(isEmployed) {
    const block = document.getElementById('employmentFieldsBlock');
    const designation = document.getElementById('current_designation');
    const orgName = document.getElementById('current_organization_name');
    const orgAddr = document.getElementById('current_organization_address');

    if (isEmployed) {
        block.classList.remove('d-none');
        designation.setAttribute('required', 'required');
        orgName.setAttribute('required', 'required');
        orgAddr.setAttribute('required', 'required');
    } else {
        block.classList.add('d-none');
        designation.removeAttribute('required');
        orgName.removeAttribute('required');
        orgAddr.removeAttribute('required');
    }
}

function copyApplyLink(url) {
    navigator.clipboard.writeText(url).then(function() {
        alert('Application form link copied to clipboard!\n' + url);
    }, function(err) {
        prompt('Copy application link:', url);
    });
}
</script>
@endsection

@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h2 class="h4 fw-bold text-dark mb-0"><i class="fa-solid fa-plus-circle me-2 text-primary"></i> Post New Opportunity / Job</h2>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('opportunities.store') }}" method="POST">
                        @csrf

                        @if($companies->isNotEmpty())
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Posting Company / Organization</label>
                            <select name="company_id" class="form-select">
                                <option value="">Platform Opportunity (Individual / Direct)</option>
                                @foreach($companies as $comp)
                                    <option value="{{ $comp->id }}" {{ $companyId == $comp->id ? 'selected' : '' }}>{{ $comp->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        <div class="row g-3 mb-3">
                            <div class="col-md-8">
                                <label class="form-label fw-semibold">Opportunity Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required placeholder="e.g. Senior Laravel Engineer">
                                @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Type <span class="text-danger">*</span></label>
                                <select name="type" class="form-select" required>
                                    <option value="job">Job</option>
                                    <option value="internship">Internship</option>
                                    <option value="freelance">Freelance</option>
                                    <option value="training">Training / Workshop</option>
                                    <option value="scholarship">Scholarship</option>
                                    <option value="event">Event</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Description <span class="text-danger">*</span></label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="5" required placeholder="Detailed job overview and requirements...">{{ old('description') }}</textarea>
                            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Responsibilities</label>
                            <textarea name="responsibilities" class="form-control" rows="3" placeholder="Key day-to-day responsibilities...">{{ old('responsibilities') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Required Skills (Comma separated)</label>
                            <input type="text" name="skills" class="form-control" value="{{ old('skills') }}" placeholder="PHP, Laravel, MySQL, Bootstrap, REST APIs">
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Min Experience (Years)</label>
                                <input type="number" name="min_experience" class="form-control" value="{{ old('min_experience', 0) }}" min="0" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Location Type</label>
                                <select name="location_type" class="form-select">
                                    <option value="onsite">On-Site</option>
                                    <option value="remote">Remote</option>
                                    <option value="hybrid">Hybrid</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Employment Type</label>
                                <select name="employment_type" class="form-select">
                                    <option value="full-time">Full-Time</option>
                                    <option value="part-time">Part-Time</option>
                                    <option value="contract">Contract</option>
                                    <option value="freelance">Freelance</option>
                                    <option value="internship">Internship</option>
                                </select>
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">City</label>
                                <input type="text" name="city" class="form-control" value="{{ old('city') }}" placeholder="e.g. Gilgit">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Min Salary ($)</label>
                                <input type="number" name="salary_min" class="form-control" value="{{ old('salary_min') }}" placeholder="50000">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Vacancies Count</label>
                                <input type="number" name="vacancies_count" class="form-control" value="{{ old('vacancies_count', 1) }}" min="1" required>
                            </div>
                        </div>

                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" name="is_internal_application" id="internalApp" value="1" checked>
                            <label class="form-check-label fw-semibold" for="internalApp">
                                Enable Direct Applications on MyResume.cloud Platform
                            </label>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('opportunities.index') }}" class="btn btn-light">Cancel</a>
                            <button type="submit" class="btn btn-primary px-4 fw-bold shadow-sm">Publish Opportunity</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use App\Models\Opportunity;
use App\Models\CandidateShortlist;
use App\Models\CandidateNote;
use App\Services\JobMatchingService;
use App\Services\NotificationService;
use App\Mail\PublicApplicationReceivedMail;
use App\Mail\NewPublicApplicationNotificationMail;
use App\Mail\ApplicationStatusEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class JobApplicationController extends Controller
{
    protected JobMatchingService $matchingService;
    protected NotificationService $notificationService;

    public function __construct(JobMatchingService $matchingService, NotificationService $notificationService)
    {
        $this->matchingService = $matchingService;
        $this->notificationService = $notificationService;
    }

    /**
     * Store registered candidate job application.
     */
    public function store(Request $request, Opportunity $opportunity)
    {
        if (!$opportunity->is_internal_application) {
            return back()->with('error', 'This position accepts external applications only.');
        }

        if ($opportunity->isExpired()) {
            return back()->with('error', 'This opportunity has expired and is no longer accepting applications.');
        }

        if ($opportunity->company && $opportunity->company->user_id === Auth::id()) {
            return back()->with('error', 'Company owners cannot apply to jobs posted by their own organization.');
        }

        $existing = JobApplication::where('opportunity_id', $opportunity->id)
            ->where('user_id', Auth::id())
            ->first();

        if ($existing) {
            return back()->with('info', 'You have already applied for this position.');
        }

        $request->validate([
            'cover_letter' => 'nullable|string|max:3000',
        ]);

        $match = $this->matchingService->calculateMatch(Auth::user(), $opportunity);

        $application = JobApplication::create([
            'opportunity_id' => $opportunity->id,
            'user_id' => Auth::id(),
            'cover_letter' => $request->input('cover_letter'),
            'status' => 'applied',
            'match_score' => $match['overall_score'],
            'applied_at' => now(),
        ]);

        // Notify company representatives or poster
        if ($opportunity->company) {
            foreach ($opportunity->company->users as $rep) {
                $this->notificationService->notify(
                    $rep,
                    "New Application Received",
                    "A candidate (" . Auth::user()->name . ") applied for " . $opportunity->title . " (Match: " . $match['overall_score'] . "%).",
                    "application",
                    route('applications.show', $application->id),
                    Auth::user()
                );
            }
        } elseif ($opportunity->postedBy) {
            $this->notificationService->notify(
                $opportunity->postedBy,
                "New Application Received",
                "A candidate (" . Auth::user()->name . ") applied for " . $opportunity->title . ".",
                "application",
                route('applications.show', $application->id),
                Auth::user()
            );
        }

        return back()->with('success', 'Application submitted successfully!');
    }

    /**
     * Show Public Candidate Application Form
     */
    public function showPublicForm(string $slug)
    {
        $opportunity = Opportunity::where('slug', $slug)->with(['company', 'skills', 'postedBy'])->firstOrFail();

        \App\Services\SeoService::set([
            'title' => 'Apply for ' . $opportunity->title . ' | MyResume.cloud',
            'description' => 'Job application registration form for ' . $opportunity->title . ' at ' . ($opportunity->company->name ?? 'Platform Posting') . '.',
            'canonical' => route('opportunities.apply_public', $opportunity->slug),
        ]);

        return view('opportunities.apply_public', compact('opportunity'));
    }

    /**
     * Store Public / Non-Registered Candidate Application
     */
    public function storePublic(Request $request, Opportunity $opportunity)
    {
        if ($opportunity->isExpired()) {
            return back()->with('error', 'This position has expired and is no longer accepting applications.')->withInput();
        }

        $validated = $request->validate([
            'applicant_name' => 'required|string|max:255',
            'applicant_email' => 'required|email|max:255',
            'applicant_phone' => 'required|string|max:50',
            'is_currently_employed' => 'required|in:0,1',
            'current_designation' => 'required_if:is_currently_employed,1|nullable|string|max:255',
            'current_organization_name' => 'required_if:is_currently_employed,1|nullable|string|max:255',
            'current_organization_address' => 'required_if:is_currently_employed,1|nullable|string|max:255',
            'cover_letter_file' => 'nullable|file|mimes:pdf,doc,docx,txt|max:5120',
            'cover_letter' => 'nullable|string|max:3000',
            'resume_file' => 'required|file|mimes:pdf,doc,docx|max:10240',
        ], [
            'applicant_name.required' => 'Full Name is required.',
            'applicant_email.required' => 'Email Address is required.',
            'applicant_phone.required' => 'Contact Number is required.',
            'is_currently_employed.required' => 'Please select whether you are currently employed.',
            'current_designation.required_if' => 'Current Designation is required when currently employed.',
            'current_organization_name.required_if' => 'Organization Name is required when currently employed.',
            'current_organization_address.required_if' => 'Organization Address is required when currently employed.',
            'resume_file.required' => 'CV / Resume file upload is mandatory.',
            'resume_file.mimes' => 'Resume must be a valid PDF, DOC, or DOCX document.',
        ]);

        // Check for duplicate application by email
        $existing = JobApplication::where('opportunity_id', $opportunity->id)
            ->where(function($q) use ($validated) {
                $q->where('applicant_email', $validated['applicant_email']);
                if (Auth::check()) {
                    $q->orWhere('user_id', Auth::id());
                }
            })
            ->first();

        if ($existing) {
            return back()->with('error', 'An application with email (' . $validated['applicant_email'] . ') has already been submitted for this position.')->withInput();
        }

        // Handle Resume Upload
        $resumePath = null;
        if ($request->hasFile('resume_file')) {
            $file = $request->file('resume_file');
            $fileName = 'public_cv_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $resumePath = $file->storeAs('resumes', $fileName, 'public');
        }

        // Handle Cover Letter File Upload
        $coverLetterPath = null;
        if ($request->hasFile('cover_letter_file')) {
            $clFile = $request->file('cover_letter_file');
            $clFileName = 'public_cl_' . uniqid() . '.' . $clFile->getClientOriginalExtension();
            $coverLetterPath = $clFile->storeAs('cover_letters', $clFileName, 'public');
        }

        $application = JobApplication::create([
            'opportunity_id' => $opportunity->id,
            'user_id' => Auth::check() ? Auth::id() : null,
            'is_public_applicant' => true,
            'applicant_name' => $validated['applicant_name'],
            'applicant_email' => $validated['applicant_email'],
            'applicant_phone' => $validated['applicant_phone'],
            'is_currently_employed' => (bool) $validated['is_currently_employed'],
            'current_designation' => $validated['is_currently_employed'] ? ($validated['current_designation'] ?? null) : null,
            'current_organization_name' => $validated['is_currently_employed'] ? ($validated['current_organization_name'] ?? null) : null,
            'current_organization_address' => $validated['is_currently_employed'] ? ($validated['current_organization_address'] ?? null) : null,
            'cover_letter' => $validated['cover_letter'] ?? null,
            'cover_letter_path' => $coverLetterPath,
            'resume_version_path' => $resumePath,
            'status' => 'applied',
            'applied_at' => now(),
        ]);

        // Send Confirmation Email to Applicant
        try {
            Mail::to($application->applicant_email)
                ->send(new PublicApplicationReceivedMail($application));
        } catch (\Exception $e) {
            Log::error("Failed to send public application confirmation email: " . $e->getMessage());
        }

        // Send Notification Email & System Notification to Job Owner
        $jobOwner = $opportunity->postedBy;
        if ($jobOwner) {
            try {
                Mail::to($jobOwner->email)
                    ->send(new NewPublicApplicationNotificationMail($application, $jobOwner->name));
            } catch (\Exception $e) {
                Log::error("Failed to send owner notification email: " . $e->getMessage());
            }

            $this->notificationService->notify(
                $jobOwner,
                "New Public Job Application",
                $application->applicant_name . " applied for " . $opportunity->title . ".",
                "application",
                route('applications.show', $application->id)
            );
        }

        $successMsg = "Dear " . $application->applicant_name . ", we have received your application for the position of " . $opportunity->title . ". We will review your profile and get back to you soon. Thank you!";

        return back()->with('success', $successMsg);
    }

    /**
     * Download Candidate Resume / CV File
     */
    public function downloadResume(JobApplication $application)
    {
        $this->authorizeCompanyAccess($application->opportunity);

        if ($application->resume_version_path && Storage::disk('public')->exists($application->resume_version_path)) {
            return Storage::disk('public')->download($application->resume_version_path, $application->candidate_name . '_CV.' . pathinfo($application->resume_version_path, PATHINFO_EXTENSION));
        }

        if ($application->user && $application->user->username) {
            return redirect()->route('cv.download.pdf', $application->user->username);
        }

        return back()->with('error', 'Resume file is not available for download.');
    }

    /**
     * Download Candidate Cover Letter File
     */
    public function downloadCoverLetter(JobApplication $application)
    {
        $this->authorizeCompanyAccess($application->opportunity);

        if ($application->cover_letter_path && Storage::disk('public')->exists($application->cover_letter_path)) {
            return Storage::disk('public')->download($application->cover_letter_path, $application->candidate_name . '_CoverLetter.' . pathinfo($application->cover_letter_path, PATHINFO_EXTENSION));
        }

        return back()->with('error', 'Cover letter file is not available for download.');
    }

    /**
     * Send direct email to applicant from platform (for Selection / Rejection / Interview).
     */
    public function sendEmailToCandidate(Request $request, JobApplication $application)
    {
        $this->authorizeCompanyAccess($application->opportunity);

        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'body' => 'required|string|max:5000',
            'email_type' => 'nullable|string|in:custom,selected,rejected,interview',
        ]);

        $candidateEmail = $application->candidate_email;

        if (empty($candidateEmail)) {
            return back()->with('error', 'Candidate email address is missing.');
        }

        try {
            Mail::to($candidateEmail)->send(new ApplicationStatusEmail(
                $application,
                $validated['subject'],
                $validated['body'],
                Auth::user()->name
            ));

            // Log recruiter note about the sent email
            if ($application->opportunity->company_id) {
                CandidateNote::create([
                    'company_id' => $application->opportunity->company_id,
                    'job_application_id' => $application->id,
                    'user_id' => $application->user_id,
                    'author_id' => Auth::id(),
                    'note' => 'Sent Email [' . ucfirst($validated['email_type'] ?? 'custom') . ']: ' . $validated['subject'],
                ]);
            }

            return back()->with('success', 'Email successfully sent to candidate (' . $candidateEmail . ').');
        } catch (\Exception $e) {
            Log::error('Failed to send candidate email: ' . $e->getMessage());
            return back()->with('error', 'Could not send email: ' . $e->getMessage());
        }
    }

    /**
     * Candidate "My Applications" dashboard.
     */
    public function indexCandidate()
    {
        $applications = JobApplication::where('user_id', Auth::id())
            ->with(['opportunity.company'])
            ->latest()
            ->paginate(10);

        \App\Services\SeoService::set([
            'title' => 'My Applications | MyResume.cloud',
            'robots' => 'noindex, nofollow'
        ]);

        return view('applications.candidate_index', compact('applications'));
    }

    /**
     * Company ATS Applicants list for an opportunity.
     */
    public function indexCompany(Opportunity $opportunity)
    {
        $this->authorizeCompanyAccess($opportunity);

        $applications = JobApplication::where('opportunity_id', $opportunity->id)
            ->with(['user.portfolio', 'user.professionalPreference'])
            ->latest()
            ->paginate(15);

        \App\Services\SeoService::set([
            'title' => 'ATS Applicants | ' . $opportunity->title . ' | MyResume.cloud',
            'robots' => 'noindex, nofollow'
        ]);

        return view('applications.company_index', compact('opportunity', 'applications'));
    }

    /**
     * View single application details (for company / recruiter / owner).
     */
    public function show(JobApplication $application)
    {
        $application->load(['opportunity.company', 'user.portfolio', 'user.professionalPreference']);
        $this->authorizeCompanyAccess($application->opportunity);

        $matchResult = null;
        if ($application->user) {
            $matchResult = $this->matchingService->calculateMatch($application->user, $application->opportunity);
        }

        // Fetch company candidate notes & shortlist status
        $notes = collect();
        $isShortlisted = false;
        if ($application->opportunity->company_id) {
            $notes = CandidateNote::where('company_id', $application->opportunity->company_id)
                ->where(function($q) use ($application) {
                    $q->where('job_application_id', $application->id);
                    if ($application->user_id) {
                        $q->orWhere('user_id', $application->user_id);
                    }
                })
                ->with('author')
                ->latest()
                ->get();

            if ($application->user_id) {
                $isShortlisted = CandidateShortlist::where('company_id', $application->opportunity->company_id)
                    ->where('user_id', $application->user_id)
                    ->exists();
            }
        }

        \App\Services\SeoService::set([
            'title' => 'Review Application | ' . $application->candidate_name . ' | MyResume.cloud',
            'robots' => 'noindex, nofollow'
        ]);

        return view('applications.show', compact('application', 'matchResult', 'notes', 'isShortlisted'));
    }

    /**
     * Update job application status.
     */
    public function updateStatus(Request $request, JobApplication $application)
    {
        $this->authorizeCompanyAccess($application->opportunity);

        $validated = $request->validate([
            'status' => 'required|string|in:applied,under_review,shortlisted,interview,selected,rejected,withdrawn',
            'status_notes' => 'nullable|string|max:1000',
        ]);

        $application->update([
            'status' => $validated['status'],
            'status_notes' => $validated['status_notes'] ?? $application->status_notes,
        ]);

        // Notify candidate if registered user
        if ($application->user) {
            $this->notificationService->notify(
                $application->user,
                "Application Status Update",
                "Your application status for " . $application->opportunity->title . " was updated to: " . strtoupper(str_replace('_', ' ', $validated['status'])),
                "application_status",
                route('applications.candidate.index'),
                Auth::user()
            );
        }

        return back()->with('success', 'Application status updated to ' . ucfirst($validated['status']));
    }

    /**
     * Add recruiter note to candidate.
     */
    public function storeNote(Request $request, JobApplication $application)
    {
        $this->authorizeCompanyAccess($application->opportunity);

        $request->validate([
            'note' => 'required|string|max:2000',
        ]);

        if ($application->opportunity->company_id) {
            CandidateNote::create([
                'company_id' => $application->opportunity->company_id,
                'job_application_id' => $application->id,
                'user_id' => $application->user_id,
                'author_id' => Auth::id(),
                'note' => $request->input('note'),
            ]);
        }

        return back()->with('success', 'Candidate note added.');
    }

    /**
     * Toggle candidate shortlist status.
     */
    public function toggleShortlist(JobApplication $application)
    {
        $this->authorizeCompanyAccess($application->opportunity);

        if (!$application->user_id) {
            return back()->with('info', 'Shortlisting applies to registered platform users.');
        }

        $existing = CandidateShortlist::where('company_id', $application->opportunity->company_id)
            ->where('user_id', $application->user_id)
            ->first();

        if ($existing) {
            $existing->delete();
            return back()->with('success', 'Candidate removed from shortlist.');
        }

        CandidateShortlist::create([
            'company_id' => $application->opportunity->company_id,
            'user_id' => $application->user_id,
            'opportunity_id' => $application->opportunity_id,
        ]);

        return back()->with('success', 'Candidate shortlisted successfully.');
    }

    private function authorizeCompanyAccess(Opportunity $opportunity)
    {
        if (Auth::user()->isAdmin()) return;

        // Poster of the job is always authorized
        if ($opportunity->posted_by_user_id === Auth::id()) {
            return;
        }

        if ($opportunity->company) {
            $isMember = $opportunity->company->members()->where('user_id', Auth::id())->exists();
            if ($isMember || $opportunity->company->user_id === Auth::id()) {
                return;
            }
        }

        abort(403, 'Unauthorized opportunity applicant access.');
    }
}

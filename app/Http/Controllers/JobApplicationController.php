<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use App\Models\Opportunity;
use App\Models\CandidateShortlist;
use App\Models\CandidateNote;
use App\Services\JobMatchingService;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
     * Store candidate job application.
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

        // Notify company representatives
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
        }

        return back()->with('success', 'Application submitted successfully!');
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
     * View single application details (for company / recruiter).
     */
    public function show(JobApplication $application)
    {
        $application->load(['opportunity.company', 'user.portfolio', 'user.professionalPreference']);
        $this->authorizeCompanyAccess($application->opportunity);

        $matchResult = $this->matchingService->calculateMatch($application->user, $application->opportunity);

        // Fetch company candidate notes & shortlist status
        $notes = CandidateNote::where('company_id', $application->opportunity->company_id)
            ->where('user_id', $application->user_id)
            ->with('author')
            ->latest()
            ->get();

        $isShortlisted = CandidateShortlist::where('company_id', $application->opportunity->company_id)
            ->where('user_id', $application->user_id)
            ->exists();

        \App\Services\SeoService::set([
            'title' => 'Review Application | ' . ($application->user->name ?? 'Candidate') . ' | MyResume.cloud',
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

        // Notify candidate
        $this->notificationService->notify(
            $application->user,
            "Application Status Update",
            "Your application status for " . $application->opportunity->title . " was updated to: " . strtoupper(str_replace('_', ' ', $validated['status'])),
            "application_status",
            route('applications.candidate.index'),
            Auth::user()
        );

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

        CandidateNote::create([
            'company_id' => $application->opportunity->company_id,
            'user_id' => $application->user_id,
            'author_id' => Auth::id(),
            'note' => $request->input('note'),
        ]);

        return back()->with('success', 'Candidate note added.');
    }

    /**
     * Toggle candidate shortlist status.
     */
    public function toggleShortlist(JobApplication $application)
    {
        $this->authorizeCompanyAccess($application->opportunity);

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

        if (!$opportunity->company) {
            abort(403, 'Unauthorized opportunity access.');
        }

        $isMember = $opportunity->company->members()->where('user_id', Auth::id())->exists();
        if (!$isMember) {
            abort(403, 'Unauthorized company applicant access.');
        }
    }
}

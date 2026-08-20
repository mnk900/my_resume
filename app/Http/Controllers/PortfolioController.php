<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Portfolio;
use App\Models\PortfolioSection;
use App\Services\PortfolioService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Services\JobMatchingService;

class PortfolioController extends Controller
{
    protected $portfolioService;
    protected $matchingService;

    public function __construct(PortfolioService $portfolioService, JobMatchingService $matchingService)
    {
        $this->portfolioService = $portfolioService;
        $this->matchingService = $matchingService;
    }

    public function show($username)
    {
        $portfolio = $this->portfolioService->getByUsername($username);

        // Force fresh load of all relations — prevents stale cached data
        $portfolio->load([
            'user', 'skills', 'projects', 'experiences',
            'education', 'certifications', 'trainings',
            'achievements', 'contributions', 'testimonials',
            'services', 'messages', 'sections'
        ]);

        $user = $portfolio->user;

        // Block admin profiles and suspended user accounts from being publicly visible
        if ($user->role === 'admin' || $user->account_status === 'suspended' || $user->isSuspended()) {
            abort(404);
        }

        // Privacy check
        if (!$portfolio->is_public) {
            $isAuthorized = false;
            $connection = null;

            if (Auth::check()) {
                $currentUser = Auth::user();
                if ($currentUser->id === $user->id || $currentUser->isAdmin()) {
                    $isAuthorized = true;
                } else {
                    $connection = $currentUser->connectionWith($user);
                    if ($connection && $connection->status === 'accepted') {
                        $isAuthorized = true;
                    }
                }
            }

            if (!$isAuthorized) {
                return view('portfolio.private', compact('user', 'portfolio', 'connection'));
            }
        }

        return view('portfolio.public', compact('user', 'portfolio'));
    }

    public function edit()
    {
        $user = Auth::user();
        $portfolio = $user->portfolio()->with(['sections', 'skills', 'projects', 'experiences', 'testimonials', 'services', 'certifications', 'education', 'achievements', 'contributions', 'media', 'publications'])->first();

        if (!$portfolio) {
            $portfolio = $user->portfolio()->create([
                'title' => $user->name . ' Portfolio',
                'description' => 'Welcome to my professional space.',
                'theme' => 'classic'
            ]);
        }

        $portfolio->load(['skills', 'projects', 'experiences', 'education', 'services', 'certifications', 'trainings', 'achievements', 'contributions', 'testimonials', 'media', 'publications', 'sections']);

        $themes = \App\Models\Theme::where('is_active', true)->get();

        $pendingReceived = $user->connectionsReceived()->where('status', 'pending')->with('sender.portfolio')->get();
        $pendingSent = $user->connectionsSent()->where('status', 'pending')->with('receiver.portfolio')->get();
        $acceptedConnections = $user->acceptedConnections();
        $connectionsCount = $acceptedConnections->count();

        $search = request('search');
        $searchResults = collect();
        if ($search) {
            $searchResults = User::where('id', '!=', $user->id)
                ->where('role', '!=', 'admin')
                ->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('username', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                })
                ->with('portfolio')
                ->get();
        }

        // Calculate Real Profile Completion Percentage & Missing Sections across Core Portfolio Content Modules
        $completionScore = 0;
        $missingItems = [];
        $optionalMissingItems = [];

        if (!empty($portfolio->title) && !empty($portfolio->position)) {
            $completionScore += 10;
        } else {
            $missingItems[] = 'Title & Position';
        }

        if (!empty($portfolio->description) || !empty($portfolio->detailed_bio)) {
            $completionScore += 10;
        } else {
            $missingItems[] = 'Summary / Detailed Bio';
        }

        if ($portfolio->skills->count() > 0) {
            $completionScore += 10;
        } else {
            $missingItems[] = 'Technical Skills';
        }

        if ($portfolio->projects->count() > 0) {
            $completionScore += 10;
        } else {
            $missingItems[] = 'Projects Showcase';
        }

        if ($portfolio->experiences->count() > 0) {
            $completionScore += 10;
        } else {
            $missingItems[] = 'Work Experience';
        }

        if ($portfolio->education->count() > 0) {
            $completionScore += 10;
        } else {
            $missingItems[] = 'Education Records';
        }

        if ($portfolio->services->count() > 0) {
            $completionScore += 8;
        } else {
            $missingItems[] = 'Services Offered';
        }

        if ($portfolio->certifications->count() > 0) {
            $completionScore += 8;
        } else {
            $missingItems[] = 'Certifications';
        }

        if ($portfolio->trainings->count() > 0) {
            $completionScore += 6;
        } else {
            $missingItems[] = 'Trainings & Courses';
        }

        if ($portfolio->achievements->count() > 0) {
            $completionScore += 6;
        } else {
            $missingItems[] = 'Achievements';
        }

        if ($portfolio->contributions->count() > 0) {
            $completionScore += 6;
        } else {
            $missingItems[] = 'Contributions';
        }

        if ($portfolio->testimonials->count() > 0) {
            $completionScore += 6;
        } else {
            $missingItems[] = 'Testimonials';
        }

        // Clamp score between 0 and 100
        $completionScore = min(100, max(0, $completionScore));

        // Optional / Bonus Modules (Do not penalize 100% core score, but highlighted if empty)
        if ($portfolio->media->count() === 0) {
            $optionalMissingItems[] = 'Media Appearances';
        }

        if ($portfolio->publications->count() === 0) {
            $optionalMissingItems[] = 'Publications';
        }

        // Applications & Pipeline Stats
        $myApplications = \App\Models\JobApplication::where('user_id', $user->id)
            ->with(['opportunity.company'])
            ->latest()
            ->get();

        $pipelineCounts = [
            'applied' => $myApplications->where('status', 'applied')->count(),
            'under_review' => $myApplications->where('status', 'under_review')->count(),
            'shortlisted' => $myApplications->where('status', 'shortlisted')->count(),
            'interview' => $myApplications->where('status', 'interview')->count(),
            'selected' => $myApplications->where('status', 'selected')->count(),
            'rejected' => $myApplications->where('status', 'rejected')->count(),
        ];

        // Recommended Opportunities with Match Score Evaluations
        $publishedOpportunities = \App\Models\Opportunity::where('status', 'published')
            ->with(['company', 'skills'])
            ->latest()
            ->get();

        $opportunityMatches = collect();
        foreach ($publishedOpportunities as $opp) {
            $matchResult = $this->matchingService->calculateMatch($user, $opp);
            $opp->match_evaluation = $matchResult;
            $opportunityMatches->push($opp);
        }

        // Sort by match score descending
        $recommendedOpportunities = $opportunityMatches->sortByDesc(fn($o) => $o->match_evaluation['overall_score'])->take(6)->values();

        // Saved Opportunities
        $savedOpportunities = \App\Models\SavedOpportunity::where('user_id', $user->id)
            ->with(['opportunity.company'])
            ->latest()
            ->take(4)
            ->get();

        // AI Mock Interview Recent Session
        $latestMockSession = \App\Models\MockInterview::where('user_id', $user->id)
            ->latest()
            ->first();

        // Recent Social Feed Posts
        $recentPosts = \App\Models\Post::where('status', 'published')
            ->with(['user.portfolio', 'company', 'likes', 'comments'])
            ->latest()
            ->take(3)
            ->get();

        // Featured Companies
        $featuredCompanies = \App\Models\Company::withCount(['opportunities' => fn($q) => $q->where('status', 'published')])
            ->latest()
            ->take(4)
            ->get();

        // System Notifications Unread Count
        $unreadNotificationsCount = \App\Models\SystemNotification::where('user_id', $user->id)
            ->where('is_read', false)
            ->count();

        // Platform User Direct Conversations
        $userConversations = \App\Models\Conversation::where('user_one_id', $user->id)
            ->orWhere('user_two_id', $user->id)
            ->with(['userOne.portfolio', 'userTwo.portfolio', 'latestMessage'])
            ->orderBy('last_message_at', 'desc')
            ->get();

        $unreadDirectMessagesCount = $user->unreadDirectMessagesCount();

        $existingSkillsGrouped = \App\Models\Skill::select('category', 'name')
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->get()
            ->groupBy('category')
            ->map(fn($group) => $group->pluck('name')->filter()->unique()->values()->toArray())
            ->toArray();

        $existingEducationDegrees = \App\Models\Education::select('degree')
            ->whereNotNull('degree')
            ->where('degree', '!=', '')
            ->pluck('degree')
            ->filter()
            ->unique()
            ->values()
            ->toArray();

        $existingEducationInstitutions = \App\Models\Education::select('institution')
            ->whereNotNull('institution')
            ->where('institution', '!=', '')
            ->pluck('institution')
            ->filter()
            ->unique()
            ->values()
            ->toArray();

        return view('portfolio.edit', compact(
            'portfolio', 'themes', 'pendingReceived', 'pendingSent',
            'acceptedConnections', 'connectionsCount', 'searchResults',
            'completionScore', 'missingItems', 'optionalMissingItems', 'myApplications',
            'pipelineCounts', 'recommendedOpportunities', 'savedOpportunities',
            'latestMockSession', 'recentPosts', 'featuredCompanies',
            'unreadNotificationsCount', 'userConversations', 'unreadDirectMessagesCount',
            'existingSkillsGrouped', 'existingEducationDegrees', 'existingEducationInstitutions'
        ));
    }

    public function update(Request $request)
    {
        $portfolio = Auth::user()->portfolio;

        $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'detailed_bio' => 'nullable|string',
            'theme' => 'nullable|string|max:50',
            'is_active' => 'nullable|in:active,inactive',
            'is_public' => 'nullable|in:public,private',
            'show_email' => 'nullable|in:show,hide',
            'show_phone' => 'nullable|in:show,hide',
            'show_linkedin' => 'nullable|in:show,hide',
            'show_skills' => 'nullable|in:show,hide',
            'show_projects' => 'nullable|in:show,hide',
            'show_experience' => 'nullable|in:show,hide',
            'show_education' => 'nullable|in:show,hide',
            'show_services' => 'nullable|in:show,hide',
            'show_certifications' => 'nullable|in:show,hide',
            'show_trainings' => 'nullable|in:show,hide',
            'show_achievements' => 'nullable|in:show,hide',
            'show_contributions' => 'nullable|in:show,hide',
            'show_testimonials' => 'nullable|in:show,hide',
            'show_media' => 'nullable|in:show,hide',
            'show_publications' => 'nullable|in:show,hide',
            'position' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'organization' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'contact_number' => 'nullable|string|max:50',
            'linkedin_url' => 'nullable|url|max:255',
            'profile_image' => 'nullable|image|max:5120',
        ]);

        $data = array_filter($request->only(['title', 'description', 'detailed_bio', 'theme', 'position', 'city', 'organization', 'country', 'contact_number', 'linkedin_url']), fn($v) => !is_null($v));
        
        if ($request->has('title')) $data['title'] = $request->input('title');
        if ($request->has('theme')) {
            $rawTheme = strtolower(trim($request->input('theme')));
            if (str_contains($rawTheme, 'premium')) {
                $data['theme'] = 'premium';
            } elseif (str_contains($rawTheme, 'elegant')) {
                $data['theme'] = 'elegant';
            } else {
                $data['theme'] = 'classic';
            }
        }
        if ($request->has('is_active')) $data['is_active'] = $request->input('is_active') === 'active';
        if ($request->has('is_public')) $data['is_public'] = $request->input('is_public') === 'public';
        
        if ($request->has('show_email')) $data['show_email'] = $request->input('show_email') === 'show';
        if ($request->has('show_phone')) $data['show_phone'] = $request->input('show_phone') === 'show';
        if ($request->has('show_linkedin')) $data['show_linkedin'] = $request->input('show_linkedin') === 'show';
        if ($request->has('show_skills')) $data['show_skills'] = $request->input('show_skills') === 'show';
        if ($request->has('show_projects')) $data['show_projects'] = $request->input('show_projects') === 'show';
        if ($request->has('show_experience')) $data['show_experience'] = $request->input('show_experience') === 'show';
        if ($request->has('show_education')) $data['show_education'] = $request->input('show_education') === 'show';
        if ($request->has('show_services')) $data['show_services'] = $request->input('show_services') === 'show';
        if ($request->has('show_certifications')) $data['show_certifications'] = $request->input('show_certifications') === 'show';
        if ($request->has('show_trainings')) $data['show_trainings'] = $request->input('show_trainings') === 'show';
        if ($request->has('show_achievements')) $data['show_achievements'] = $request->input('show_achievements') === 'show';
        if ($request->has('show_contributions')) $data['show_contributions'] = $request->input('show_contributions') === 'show';
        if ($request->has('show_testimonials')) $data['show_testimonials'] = $request->input('show_testimonials') === 'show';
        if ($request->has('show_media')) $data['show_media'] = $request->input('show_media') === 'show';
        if ($request->has('show_publications')) $data['show_publications'] = $request->input('show_publications') === 'show';

        if ($request->hasFile('profile_image')) {
            $data['profile_image'] = $request->file('profile_image');
        }

        $this->portfolioService->updatePortfolio(Auth::user()->portfolio, $data);

        $activeTab = $request->input('active_tab');
        if (!$activeTab) {
            if ($request->has('theme')) {
                $activeTab = 'themesPane';
            } else {
                $activeTab = 'settingsPane';
            }
        }

        return back()->with('status', 'portfolio-updated')->with('active_tab', $activeTab);
    }

    public function storeSection(Request $request)
    {
        $request->validate([
            'type' => 'required|string',
            'title' => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,docx,doc|max:10240',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $data = $request->all();
        if (empty($data['title'])) {
            $data['title'] = 'Resume / CV File';
        }

        $this->portfolioService->addSection(Auth::user()->portfolio, $data);

        return back()->with('status', 'section-added')->with('active_tab', 'cmsPane');
    }

    public function updateSection(Request $request, PortfolioSection $section)
    {
        if ($section->portfolio->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'type' => 'required|string',
            'title' => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,docx,doc|max:10240',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $section->update($request->only('type', 'title', 'content'));

        return back()->with('status', 'section-updated')->with('active_tab', 'cmsPane');
    }

    public function destroySection(PortfolioSection $section)
    {
        if ($section->portfolio->user_id !== Auth::id()) {
            abort(403);
        }

        $section->delete();

        return back()->with('status', 'section-deleted')->with('active_tab', 'cmsPane');
    }
}

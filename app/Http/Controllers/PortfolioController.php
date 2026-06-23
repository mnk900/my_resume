<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Portfolio;
use App\Models\PortfolioSection;
use App\Services\PortfolioService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PortfolioController extends Controller
{
    protected $portfolioService;

    public function __construct(PortfolioService $portfolioService)
    {
        $this->portfolioService = $portfolioService;
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

        // Block admin profiles from being publicly visible
        if ($user->role === 'admin') {
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

        // Already handled by booted() but safety check
        if (!$portfolio) {
            $portfolio = $user->portfolio()->create([
                'title' => $user->name . ' Portfolio',
                'description' => 'Welcome to my professional space.',
                'theme' => 'classic'
            ]);
        }

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

        return view('portfolio.edit', compact(
            'portfolio', 'themes', 'pendingReceived', 'pendingSent',
            'acceptedConnections', 'connectionsCount', 'searchResults'
        ));
    }

    public function update(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'detailed_bio' => 'nullable|string',
            'theme' => 'required|string|max:50',
            'is_active' => 'required|in:active,inactive',
            'is_public' => 'required|in:public,private',
            'show_email' => 'required|in:show,hide',
            'show_phone' => 'required|in:show,hide',
            'show_linkedin' => 'required|in:show,hide',
            'show_skills' => 'required|in:show,hide',
            'show_projects' => 'required|in:show,hide',
            'show_experience' => 'required|in:show,hide',
            'show_education' => 'required|in:show,hide',
            'show_services' => 'required|in:show,hide',
            'show_certifications' => 'required|in:show,hide',
            'show_trainings' => 'required|in:show,hide',
            'show_achievements' => 'required|in:show,hide',
            'show_contributions' => 'required|in:show,hide',
            'show_testimonials' => 'required|in:show,hide',
            'show_media' => 'required|in:show,hide',
            'show_publications' => 'required|in:show,hide',
            'position' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'organization' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'contact_number' => 'nullable|string|max:50',
            'linkedin_url' => 'nullable|url|max:255',
            'profile_image' => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['title', 'description', 'detailed_bio', 'theme', 'position', 'city', 'organization', 'country', 'contact_number', 'linkedin_url']);
        $data['is_active'] = $request->input('is_active') === 'active';
        $data['is_public'] = $request->input('is_public') === 'public';
        $data['show_email'] = $request->input('show_email') === 'show';
        $data['show_phone'] = $request->input('show_phone') === 'show';
        $data['show_linkedin'] = $request->input('show_linkedin') === 'show';
        $data['show_skills'] = $request->input('show_skills') === 'show';
        $data['show_projects'] = $request->input('show_projects') === 'show';
        $data['show_experience'] = $request->input('show_experience') === 'show';
        $data['show_education'] = $request->input('show_education') === 'show';
        $data['show_services'] = $request->input('show_services') === 'show';
        $data['show_certifications'] = $request->input('show_certifications') === 'show';
        $data['show_trainings'] = $request->input('show_trainings') === 'show';
        $data['show_achievements'] = $request->input('show_achievements') === 'show';
        $data['show_contributions'] = $request->input('show_contributions') === 'show';
        $data['show_testimonials'] = $request->input('show_testimonials') === 'show';
        $data['show_media'] = $request->input('show_media') === 'show';
        $data['show_publications'] = $request->input('show_publications') === 'show';

        if ($request->hasFile('profile_image')) {
            $data['profile_image'] = $request->file('profile_image');
        }

        $this->portfolioService->updatePortfolio(Auth::user()->portfolio, $data);

        return back()->with('status', 'portfolio-updated');
    }

    public function storeSection(Request $request)
    {
        $request->validate([
            'type' => 'required|string',
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,docx,doc|max:10240',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $this->portfolioService->addSection(Auth::user()->portfolio, $request->all());

        return back()->with('status', 'section-added');
    }

    public function updateSection(Request $request, PortfolioSection $section)
    {
        if ($section->portfolio->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'type' => 'required|string',
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,docx,doc|max:10240',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $this->portfolioService->addSection($section->portfolio, $request->all()); // Reuse logic or add updateSection to service

        // Actually let's just use service for updates too
        $section->update($request->only('type', 'title', 'content'));

        return back()->with('status', 'section-updated');
    }

    public function destroySection(PortfolioSection $section)
    {
        if ($section->portfolio->user_id !== Auth::id()) {
            abort(403);
        }

        $section->delete();

        return back()->with('status', 'section-deleted');
    }
}

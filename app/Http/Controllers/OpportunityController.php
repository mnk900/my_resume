<?php

namespace App\Http\Controllers;

use App\Models\Opportunity;
use App\Models\OpportunitySkill;
use App\Models\SavedOpportunity;
use App\Models\Company;
use App\Services\JobMatchingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class OpportunityController extends Controller
{
    protected JobMatchingService $matchingService;

    public function __construct(JobMatchingService $matchingService)
    {
        $this->matchingService = $matchingService;
    }

    /**
     * Public Job & Opportunity Search
     */
    public function index(Request $request)
    {
        $query = Opportunity::where('status', 'published')->with(['company', 'skills']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%")
                  ->orWhere('industry', 'like', "%{$search}%")
                  ->orWhereHas('company', function($cq) use ($search) {
                      $cq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        if ($request->filled('location_type')) {
            $query->where('location_type', $request->input('location_type'));
        }

        if ($request->filled('employment_type')) {
            $query->where('employment_type', $request->input('employment_type'));
        }

        if ($request->filled('city')) {
            $query->where('city', 'like', "%{$request->input('city')}%");
        }

        if ($request->filled('country')) {
            $query->where('country', 'like', "%{$request->input('country')}%");
        }

        if ($request->filled('min_exp')) {
            $query->where('min_experience', '<=', (int) $request->input('min_exp'));
        }

        $opportunities = $query->latest('published_at')->paginate(12);

        // Personalized recommendations if user is authenticated
        $recommendedJobs = collect();
        if (Auth::check() && Auth::user()->portfolio) {
            $allJobs = Opportunity::where('status', 'published')->where('type', 'job')->take(20)->get();
            $scored = [];
            foreach ($allJobs as $job) {
                $match = $this->matchingService->calculateMatch(Auth::user(), $job);
                $scored[] = [
                    'job' => $job,
                    'match' => $match,
                ];
            }
            usort($scored, fn($a, $b) => $b['match']['overall_score'] <=> $a['match']['overall_score']);
            $recommendedJobs = collect(array_slice($scored, 0, 5));
        }

        return view('opportunities.index', compact('opportunities', 'recommendedJobs'));
    }

    /**
     * Show Opportunity Details
     */
    public function show(string $slug)
    {
        $opportunity = Opportunity::where('slug', $slug)->with(['company', 'skills', 'postedBy'])->firstOrFail();

        $matchResult = null;
        $userApplication = null;
        $isSaved = false;

        if (Auth::check()) {
            $matchResult = $this->matchingService->calculateMatch(Auth::user(), $opportunity);
            $userApplication = \App\Models\JobApplication::where('opportunity_id', $opportunity->id)
                ->where('user_id', Auth::id())
                ->first();
            $isSaved = SavedOpportunity::where('opportunity_id', $opportunity->id)
                ->where('user_id', Auth::id())
                ->exists();
        }

        return view('opportunities.show', compact('opportunity', 'matchResult', 'userApplication', 'isSaved'));
    }

    /**
     * Show Opportunity creation form.
     */
    public function create(Request $request)
    {
        $companyId = $request->query('company_id');
        $companies = Auth::user()->companies;

        if ($companies->isEmpty() && !Auth::user()->isAdmin()) {
            return redirect()->route('companies.create')->with('info', 'Please create a company profile first before posting jobs.');
        }

        return view('opportunities.create', compact('companies', 'companyId'));
    }

    /**
     * Store new opportunity.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_id' => 'nullable|exists:companies,id',
            'type' => 'required|string|in:job,internship,freelance,training,workshop,scholarship,event,volunteer,other',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'responsibilities' => 'nullable|string',
            'category' => 'nullable|string|max:255',
            'industry' => 'nullable|string|max:255',
            'min_experience' => 'required|integer|min:0',
            'max_experience' => 'nullable|integer|gte:min_experience',
            'education_required' => 'nullable|string|max:255',
            'location_type' => 'required|string|in:onsite,remote,hybrid',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'employment_type' => 'required|string|in:full-time,part-time,contract,freelance,internship',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|gte:salary_min',
            'salary_currency' => 'nullable|string|max:10',
            'salary_period' => 'nullable|string|in:yearly,monthly,hourly',
            'application_deadline' => 'nullable|date|after_or_equal:today',
            'external_url' => 'nullable|url|max:255',
            'is_internal_application' => 'nullable|boolean',
            'vacancies_count' => 'required|integer|min:1',
            'skills' => 'nullable|string', // comma separated skills
        ]);

        $slug = Str::slug($validated['title']);
        $originalSlug = $slug;
        $count = 1;
        while (Opportunity::where('slug', $slug)->exists()) {
            $slug = "{$originalSlug}-{$count}";
            $count++;
        }

        $opportunity = Opportunity::create([
            'company_id' => $validated['company_id'] ?? null,
            'posted_by_user_id' => Auth::id(),
            'type' => $validated['type'],
            'title' => $validated['title'],
            'slug' => $slug,
            'description' => $validated['description'],
            'responsibilities' => $validated['responsibilities'] ?? null,
            'category' => $validated['category'] ?? null,
            'industry' => $validated['industry'] ?? null,
            'min_experience' => $validated['min_experience'],
            'max_experience' => $validated['max_experience'] ?? null,
            'education_required' => $validated['education_required'] ?? null,
            'location_type' => $validated['location_type'],
            'city' => $validated['city'] ?? null,
            'country' => $validated['country'] ?? null,
            'employment_type' => $validated['employment_type'],
            'salary_min' => $validated['salary_min'] ?? null,
            'salary_max' => $validated['salary_max'] ?? null,
            'salary_currency' => $validated['salary_currency'] ?? 'USD',
            'salary_period' => $validated['salary_period'] ?? 'monthly',
            'application_deadline' => $validated['application_deadline'] ?? null,
            'external_url' => $validated['external_url'] ?? null,
            'is_internal_application' => $request->has('is_internal_application'),
            'vacancies_count' => $validated['vacancies_count'],
            'status' => 'published',
            'published_at' => now(),
        ]);

        // Process attached skills string
        if (!empty($validated['skills'])) {
            $skillNames = array_map('trim', explode(',', $validated['skills']));
            foreach ($skillNames as $name) {
                if (!empty($name)) {
                    OpportunitySkill::create([
                        'opportunity_id' => $opportunity->id,
                        'skill_name' => $name,
                        'is_required' => true,
                        'weight' => 1,
                    ]);
                }
            }
        }

        return redirect()->route('opportunities.show', $opportunity->slug)->with('success', 'Opportunity published successfully!');
    }

    /**
     * Bookmark / Save an opportunity.
     */
    public function toggleSave(Opportunity $opportunity)
    {
        $existing = SavedOpportunity::where('opportunity_id', $opportunity->id)
            ->where('user_id', Auth::id())
            ->first();

        if ($existing) {
            $existing->delete();
            return back()->with('success', 'Job removed from saved list.');
        }

        SavedOpportunity::create([
            'opportunity_id' => $opportunity->id,
            'user_id' => Auth::id(),
        ]);

        return back()->with('success', 'Job saved successfully.');
    }

    /**
     * Show Opportunity edit form.
     */
    public function edit(Opportunity $opportunity)
    {
        $user = Auth::user();
        $isOwner = $user->id === $opportunity->posted_by_user_id || 
                   ($opportunity->company && $opportunity->company->user_id === $user->id) || 
                   $user->isAdmin();

        if (!$isOwner) {
            abort(403, 'Unauthorized to edit this job posting.');
        }

        $companies = $user->companies;
        $existingSkills = $opportunity->skills->pluck('skill_name')->implode(', ');

        return view('opportunities.edit', compact('opportunity', 'companies', 'existingSkills'));
    }

    /**
     * Update existing opportunity.
     */
    public function update(Request $request, Opportunity $opportunity)
    {
        $user = Auth::user();
        $isOwner = $user->id === $opportunity->posted_by_user_id || 
                   ($opportunity->company && $opportunity->company->user_id === $user->id) || 
                   $user->isAdmin();

        if (!$isOwner) {
            abort(403, 'Unauthorized to update this job posting.');
        }

        $validated = $request->validate([
            'company_id' => 'nullable|exists:companies,id',
            'type' => 'required|string|in:job,internship,freelance,training,workshop,scholarship,event,volunteer,other',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'responsibilities' => 'nullable|string',
            'category' => 'nullable|string|max:255',
            'industry' => 'nullable|string|max:255',
            'min_experience' => 'required|integer|min:0',
            'max_experience' => 'nullable|integer|gte:min_experience',
            'education_required' => 'nullable|string|max:255',
            'location_type' => 'required|string|in:onsite,remote,hybrid',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'employment_type' => 'required|string|in:full-time,part-time,contract,freelance,internship',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|gte:salary_min',
            'salary_currency' => 'nullable|string|max:10',
            'salary_period' => 'nullable|string|in:yearly,monthly,hourly',
            'application_deadline' => 'nullable|date',
            'external_url' => 'nullable|url|max:255',
            'is_internal_application' => 'nullable|boolean',
            'vacancies_count' => 'required|integer|min:1',
            'skills' => 'nullable|string',
        ]);

        $opportunity->update([
            'company_id' => $validated['company_id'] ?? null,
            'type' => $validated['type'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'responsibilities' => $validated['responsibilities'] ?? null,
            'category' => $validated['category'] ?? null,
            'industry' => $validated['industry'] ?? null,
            'min_experience' => $validated['min_experience'],
            'max_experience' => $validated['max_experience'] ?? null,
            'education_required' => $validated['education_required'] ?? null,
            'location_type' => $validated['location_type'],
            'city' => $validated['city'] ?? null,
            'country' => $validated['country'] ?? null,
            'employment_type' => $validated['employment_type'],
            'salary_min' => $validated['salary_min'] ?? null,
            'salary_max' => $validated['salary_max'] ?? null,
            'salary_currency' => $validated['salary_currency'] ?? 'USD',
            'salary_period' => $validated['salary_period'] ?? 'monthly',
            'application_deadline' => $validated['application_deadline'] ?? null,
            'external_url' => $validated['external_url'] ?? null,
            'is_internal_application' => $request->has('is_internal_application'),
            'vacancies_count' => $validated['vacancies_count'],
        ]);

        // Sync skills
        OpportunitySkill::where('opportunity_id', $opportunity->id)->delete();
        if (!empty($validated['skills'])) {
            $skillNames = array_map('trim', explode(',', $validated['skills']));
            foreach ($skillNames as $name) {
                if (!empty($name)) {
                    OpportunitySkill::create([
                        'opportunity_id' => $opportunity->id,
                        'skill_name' => $name,
                        'is_required' => true,
                        'weight' => 1,
                    ]);
                }
            }
        }

        return redirect()->route('opportunities.show', $opportunity->slug)->with('success', 'Job posting updated successfully.');
    }

    /**
     * Delete existing opportunity.
     */
    public function destroy(Opportunity $opportunity)
    {
        $user = Auth::user();
        $isOwner = $user->id === $opportunity->posted_by_user_id || 
                   ($opportunity->company && $opportunity->company->user_id === $user->id) || 
                   $user->isAdmin();

        if (!$isOwner) {
            abort(403, 'Unauthorized to delete this job posting.');
        }

        $opportunity->delete();

        return redirect()->route('opportunities.index')->with('success', 'Job posting deleted successfully.');
    }
}

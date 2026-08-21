<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\CompanyMember;
use App\Models\User;
use App\Services\CompanyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyController extends Controller
{
    protected CompanyService $companyService;

    public function __construct(CompanyService $companyService)
    {
        $this->companyService = $companyService;
    }

    /**
     * Display public directory of companies.
     */
    public function index(Request $request)
    {
        $query = Company::where('verification_status', '!=', 'suspended');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('industry', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('industry')) {
            $query->where('industry', $request->input('industry'));
        }

        if ($request->filled('city')) {
            $query->where('city', $request->input('city'));
        }

        $companies = $query->withCount('opportunities')->latest()->paginate(12);

        \App\Services\SeoService::set([
            'title' => 'Companies & Employers | Discover Opportunities | MyResume.cloud',
            'description' => 'Discover verified companies, hiring organizations, and employers. Explore company profiles, culture, and active career opportunities.',
            'canonical' => url('/companies'),
        ]);

        return view('company.index', compact('companies'));
    }

    /**
     * Show registration/creation form for a company.
     */
    public function create()
    {
        \App\Services\SeoService::set([
            'title' => 'Register Company | MyResume.cloud',
            'robots' => 'noindex, nofollow'
        ]);
        return view('company.create');
    }

    /**
     * Store new company profile.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'industry' => 'nullable|string|max:255',
            'org_type' => 'nullable|string|max:255',
            'description' => 'required|string',
            'website' => 'nullable|url|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'country' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:255',
            'company_size' => 'nullable|string|max:50',
            'founded_year' => 'nullable|integer|min:1800|max:' . date('Y'),
            'logo' => 'nullable|image|max:2048',
            'cover' => 'nullable|image|max:4096',
        ]);

        $company = $this->companyService->createCompany(Auth::user(), array_merge($validated, [
            'logo' => $request->file('logo'),
            'cover' => $request->file('cover'),
        ]));

        return redirect()->route('companies.show', $company->slug)->with('success', 'Company profile created successfully! It is pending admin verification.');
    }

    /**
     * View company public profile.
     */
    public function show(string $slug)
    {
        $company = Company::where('slug', $slug)->with(['members.user', 'opportunities' => function($q) {
            $q->where('status', 'published')->latest();
        }, 'posts.user'])->firstOrFail();

        $userMembership = Auth::check() ? $company->members->where('user_id', Auth::id())->first() : null;

        \App\Services\SeoService::set(\App\Services\SeoService::generateCompanySeo($company));

        return view('company.show', compact('company', 'userMembership'));
    }

    /**
     * Show edit form for company owner/recruiter.
     */
    public function edit(Company $company)
    {
        $this->authorizeCompanyMember($company);
        \App\Services\SeoService::set([
            'title' => 'Edit Company Profile | ' . $company->name . ' | MyResume.cloud',
            'robots' => 'noindex, nofollow'
        ]);
        return view('company.edit', compact('company'));
    }

    /**
     * Update company details.
     */
    public function update(Request $request, Company $company)
    {
        $this->authorizeCompanyMember($company);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'industry' => 'nullable|string|max:255',
            'org_type' => 'nullable|string|max:255',
            'description' => 'required|string',
            'website' => 'nullable|url|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'country' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:255',
            'company_size' => 'nullable|string|max:50',
            'founded_year' => 'nullable|integer|min:1800|max:' . date('Y'),
            'logo' => 'nullable|image|max:2048',
            'cover' => 'nullable|image|max:4096',
        ]);

        $this->companyService->updateCompany($company, array_merge($validated, [
            'logo' => $request->file('logo'),
            'cover' => $request->file('cover'),
        ]));

        return redirect()->route('companies.show', $company->slug)->with('success', 'Company profile updated successfully.');
    }

    /**
     * Company dashboard for owners/recruiters.
     */
    public function dashboard(Company $company)
    {
        $this->authorizeCompanyMember($company);

        \App\Services\SeoService::set([
            'title' => $company->name . ' Dashboard | MyResume.cloud',
            'robots' => 'noindex, nofollow'
        ]);

        $stats = [
            'total_jobs' => $company->opportunities()->where('type', 'job')->count(),
            'active_jobs' => $company->opportunities()->where('type', 'job')->where('status', 'published')->count(),
            'total_applications' => \App\Models\JobApplication::whereIn('opportunity_id', $company->opportunities->pluck('id'))->count(),
            'shortlisted' => \App\Models\JobApplication::whereIn('opportunity_id', $company->opportunities->pluck('id'))->where('status', 'shortlisted')->count(),
            'interviews' => \App\Models\JobApplication::whereIn('opportunity_id', $company->opportunities->pluck('id'))->where('status', 'interview')->count(),
        ];

        $recentOpportunities = $company->opportunities()->withCount('applications')->latest()->take(5)->get();
        $recentApplications = \App\Models\JobApplication::whereIn('opportunity_id', $company->opportunities->pluck('id'))
            ->with(['user.portfolio', 'opportunity'])
            ->latest()
            ->take(5)
            ->get();

        return view('company.dashboard', compact('company', 'stats', 'recentOpportunities', 'recentApplications'));
    }

    private function authorizeCompanyMember(Company $company)
    {
        $isMember = $company->members()->where('user_id', Auth::id())->exists();
        if (!$isMember && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized company management access.');
        }
    }
}

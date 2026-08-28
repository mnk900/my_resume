<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Portfolio;
use App\Models\Company;
use App\Models\Opportunity;
use App\Models\Connection;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $query = Portfolio::where('is_active', true)
            ->with('user')
            ->whereHas('user', function($q) {
                $q->where('role', '!=', 'admin');
            });

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('position', 'like', "%{$search}%")
                  ->orWhere('organization', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('country', 'like', "%{$search}%")
                  ->orWhereHas('user', function($qu) use ($search) {
                      $qu->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('sections', function($qs) use ($search) {
                      $qs->where('content', 'like', "%{$search}%")
                         ->orWhere('title', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('skills')) {
            $skills = $request->skills;
            $query->whereHas('sections', function($q) use ($skills) {
                $q->where('type', 'skills')->where('content', 'like', "%{$skills}%");
            });
        }

        if ($request->filled('position')) {
            $query->where('position', 'like', "%{$request->position}%");
        }

        if ($request->filled('city')) {
            $query->where('city', 'like', "%{$request->city}%");
        }

        if ($request->filled('organization')) {
            $query->where('organization', 'like', "%{$request->organization}%");
        }

        if ($request->filled('country')) {
            $query->where('country', 'like', "%{$request->country}%");
        }

        $portfolios = $query->latest()->paginate(12);

        // Real platform statistics from database
        $stats = [
            'total_portfolios' => Portfolio::where('is_active', true)->count(),
            'total_companies' => Company::count(),
            'total_opportunities' => Opportunity::where('status', 'published')->count(),
            'total_connections' => Connection::where('status', 'accepted')->count(),
        ];

        // Recent live jobs for homepage showcase
        $recentJobs = Opportunity::with('company')
            ->where('status', 'published')
            ->latest()
            ->take(6)
            ->get();

        // Featured verified companies (prioritizing companies with logos)
        $featuredCompanies = Company::withCount(['opportunities' => function($q) {
                $q->where('status', 'published');
            }])
            ->orderByRaw('CASE WHEN logo_path IS NOT NULL AND logo_path != "" THEN 0 ELSE 1 END')
            ->latest()
            ->take(4)
            ->get();

        // Featured candidate profiles (prioritizing portfolios with uploaded profile pictures)
        $featuredCandidates = User::whereHas('portfolio', function($q) {
                $q->where('is_active', true);
            })
            ->where('role', '!=', 'admin')
            ->where('account_status', '!=', 'suspended')
            ->with('portfolio', 'professionalPreference')
            ->join('portfolios', 'users.id', '=', 'portfolios.user_id')
            ->select('users.*')
            ->orderByRaw('CASE WHEN portfolios.profile_image IS NOT NULL AND portfolios.profile_image != "" THEN 0 ELSE 1 END')
            ->orderBy('users.id', 'desc')
            ->take(6)
            ->get();

        \App\Services\SeoService::set([
            'title' => 'MyResume.cloud | Professional Portfolios, Jobs & Career Opportunities',
            'description' => 'Create your verified professional portfolio, discover matched job opportunities, connect with top organizations, and prepare for your career with MyResume.cloud.',
            'canonical' => url('/'),
            'schema' => [
                '@context' => 'https://schema.org',
                '@type' => 'WebSite',
                'name' => 'MyResume.cloud',
                'url' => url('/'),
                'potentialAction' => [
                    '@type' => 'SearchAction',
                    'target' => url('/search') . '?q={search_term_string}',
                    'query-input' => 'required name=search_term_string'
                ]
            ]
        ]);

        return view('welcome', compact(
            'portfolios',
            'stats',
            'recentJobs',
            'featuredCompanies',
            'featuredCandidates'
        ));
    }
}

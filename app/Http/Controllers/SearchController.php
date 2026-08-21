<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Company;
use App\Models\Opportunity;
use App\Models\Post;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Unified Global Multi-Entity Search across Professionals, Companies, Opportunities, and Feed Posts.
     */
    public function index(Request $request)
    {
        $q = trim($request->input('q', ''));

        if (empty($q)) {
            return view('search.index', [
                'query' => '',
                'professionals' => collect(),
                'companies' => collect(),
                'opportunities' => collect(),
                'posts' => collect(),
            ]);
        }

        $professionals = User::where('role', 'user')
            ->where('account_status', '!=', 'suspended')
            ->where(function($queryGroup) use ($q) {
                $queryGroup->whereHas('portfolio', function($pq) use ($q) {
                    $pq->where('is_active', true)
                      ->where(function($sub) use ($q) {
                          $sub->where('title', 'like', "%{$q}%")
                              ->orWhere('position', 'like', "%{$q}%")
                              ->orWhere('city', 'like', "%{$q}%")
                              ->orWhere('organization', 'like', "%{$q}%")
                              ->orWhere('description', 'like', "%{$q}%");
                      });
                })
                ->orWhere('name', 'like', "%{$q}%");
            })
            ->with('portfolio')
            ->take(6)
            ->get();

        $companies = Company::where('verification_status', '!=', 'suspended')
            ->where(function($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('industry', 'like', "%{$q}%")
                    ->orWhere('city', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            })
            ->take(6)
            ->get();

        $opportunities = Opportunity::where('status', 'published')
            ->where(function($sub) use ($q) {
                $sub->where('title', 'like', "%{$q}%")
                    ->orWhere('category', 'like', "%{$q}%")
                    ->orWhere('industry', 'like', "%{$q}%")
                    ->orWhere('city', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            })
            ->with('company')
            ->take(6)
            ->get();

        $posts = Post::where('status', 'published')
            ->where('content', 'like', "%{$q}%")
            ->with(['user.portfolio', 'company'])
            ->take(6)
            ->get();

        $sanitizedQuery = \App\Services\SeoService::sanitizeText($q);
        $title = !empty($sanitizedQuery)
            ? 'Search Results for "' . $sanitizedQuery . '" | MyResume.cloud'
            : 'Search Candidates & Jobs | MyResume.cloud';

        \App\Services\SeoService::set([
            'title' => $title,
            'description' => !empty($sanitizedQuery)
                ? 'Search results for ' . $sanitizedQuery . ' on MyResume.cloud. Discover matching professional portfolios, job vacancies, and companies.'
                : 'Search professional portfolios, job vacancies, companies, and community posts on MyResume.cloud.',
            'robots' => 'noindex, follow',
            'canonical' => url('/search'),
        ]);

        return view('search.index', compact('q', 'professionals', 'companies', 'opportunities', 'posts'));
    }
}

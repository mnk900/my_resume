<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Portfolio;
use App\Models\ProfessionalPreference;
use Illuminate\Http\Request;

class TalentDiscoveryController extends Controller
{
    /**
     * Display filtered Candidate Discovery directory.
     */
    public function index(Request $request)
    {
        $query = User::where('role', 'user')
            ->whereHas('portfolio', function($q) {
                $q->where('is_active', true);
            })
            ->with(['portfolio.skills', 'portfolio.experiences', 'portfolio.education', 'professionalPreference']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhereHas('portfolio', function($pq) use ($search) {
                      $pq->where('title', 'like', "%{$search}%")
                        ->orWhere('position', 'like', "%{$search}%")
                        ->orWhere('organization', 'like', "%{$search}%")
                        ->orWhere('city', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('skill')) {
            $skill = $request->input('skill');
            $query->whereHas('portfolio.skills', function($sq) use ($skill) {
                $sq->where('name', 'like', "%{$skill}%");
            });
        }

        if ($request->filled('city')) {
            $city = $request->input('city');
            $query->whereHas('portfolio', function($pq) use ($city) {
                $pq->where('city', 'like', "%{$city}%");
            });
        }

        if ($request->filled('country')) {
            $country = $request->input('country');
            $query->whereHas('portfolio', function($pq) use ($country) {
                $pq->where('country', 'like', "%{$country}%");
            });
        }

        if ($request->filled('availability')) {
            $avail = $request->input('availability');
            $query->whereHas('professionalPreference', function($prefQ) use ($avail) {
                $prefQ->where('availability', $avail);
            });
        }

        $candidates = $query->paginate(12);

        return view('talent.index', compact('candidates'));
    }
}

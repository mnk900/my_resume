<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Portfolio;
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
                // Search in Portfolio attributes
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('position', 'like', "%{$search}%")
                  ->orWhere('organization', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('country', 'like', "%{$search}%")
                  // Search in User name
                  ->orWhereHas('user', function($qu) use ($search) {
                      $qu->where('name', 'like', "%{$search}%");
                  })
                  // Search in Sections (Skills, etc)
                  ->orWhereHas('sections', function($qs) use ($search) {
                      $qs->where('content', 'like', "%{$search}%")
                         ->orWhere('title', 'like', "%{$search}%");
                  });
            });
        }

        // Specific filters
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

        return view('welcome', compact('portfolios'));
    }
}

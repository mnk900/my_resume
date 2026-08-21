<?php

namespace App\Http\Controllers;

use App\Models\ProfessionalPreference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfessionalPreferenceController extends Controller
{
    public function edit()
    {
        $preference = Auth::user()->professionalPreference ?? new ProfessionalPreference(['user_id' => Auth::id()]);

        \App\Services\SeoService::set([
            'title' => 'Career Preferences | MyResume.cloud',
            'robots' => 'noindex, nofollow'
        ]);

        return view('profile.preferences', compact('preference'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'availability' => 'required|string|in:open_to_work,open_to_opportunities,freelance,internship,not_looking',
            'preferred_titles' => 'nullable|string', // comma separated
            'preferred_industries' => 'nullable|string', // comma separated
            'preferred_locations' => 'nullable|string', // comma separated
            'remote_preference' => 'required|string|in:remote_only,hybrid,onsite,any',
            'salary_expectation_min' => 'nullable|numeric|min:0',
            'salary_expectation_currency' => 'nullable|string|max:10',
            'willing_to_relocate' => 'nullable|boolean',
        ]);

        $titles = !empty($validated['preferred_titles']) ? array_map('trim', explode(',', $validated['preferred_titles'])) : [];
        $industries = !empty($validated['preferred_industries']) ? array_map('trim', explode(',', $validated['preferred_industries'])) : [];
        $locations = !empty($validated['preferred_locations']) ? array_map('trim', explode(',', $validated['preferred_locations'])) : [];

        ProfessionalPreference::updateOrCreate(
            ['user_id' => Auth::id()],
            [
                'availability' => $validated['availability'],
                'preferred_titles' => $titles,
                'preferred_industries' => $industries,
                'preferred_locations' => $locations,
                'remote_preference' => $validated['remote_preference'],
                'salary_expectation_min' => $validated['salary_expectation_min'] ?? null,
                'salary_expectation_currency' => $validated['salary_expectation_currency'] ?? 'USD',
                'willing_to_relocate' => $request->has('willing_to_relocate'),
            ]
        );

        return back()->with('success', 'Professional career preferences updated successfully.');
    }
}

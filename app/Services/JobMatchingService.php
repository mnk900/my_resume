<?php

namespace App\Services;

use App\Models\Opportunity;
use App\Models\User;
use App\Models\Portfolio;
use App\Models\ProfessionalPreference;

class JobMatchingService
{
    /**
     * Calculate match score breakdown between a user candidate and an opportunity.
     *
     * @param User $user
     * @param Opportunity $opportunity
     * @return array
     */
    public function calculateMatch(User $user, Opportunity $opportunity): array
    {
        $portfolio = $user->portfolio;

        // If no portfolio exists, return default minimal match
        if (!$portfolio) {
            return [
                'overall_score' => 0,
                'breakdown' => [
                    'skills' => 0,
                    'experience' => 0,
                    'education' => 0,
                    'industry' => 0,
                    'location' => 0,
                    'preference' => 0,
                ],
                'explanations' => ['User has not set up a professional portfolio yet.'],
            ];
        }

        $explanations = [];

        // 1. Skills Match (Weight: 35%)
        $skillsScore = $this->evaluateSkills($portfolio, $opportunity, $explanations);

        // 2. Experience Match (Weight: 20%)
        $experienceScore = $this->evaluateExperience($portfolio, $opportunity, $explanations);

        // 3. Education Match (Weight: 15%)
        $educationScore = $this->evaluateEducation($portfolio, $opportunity, $explanations);

        // 4. Industry Match (Weight: 10%)
        $industryScore = $this->evaluateIndustry($portfolio, $opportunity, $explanations);

        // 5. Location Match (Weight: 10%)
        $locationScore = $this->evaluateLocation($portfolio, $opportunity, $user->professionalPreference, $explanations);

        // 6. Preference Match (Weight: 10%)
        $preferenceScore = $this->evaluatePreferences($user->professionalPreference, $opportunity, $explanations);

        $overallScore = (int) round(
            ($skillsScore * 0.35) +
            ($experienceScore * 0.20) +
            ($educationScore * 0.15) +
            ($industryScore * 0.10) +
            ($locationScore * 0.10) +
            ($preferenceScore * 0.10)
        );

        return [
            'overall_score' => min(100, max(0, $overallScore)),
            'breakdown' => [
                'skills' => $skillsScore,
                'experience' => $experienceScore,
                'education' => $educationScore,
                'industry' => $industryScore,
                'location' => $locationScore,
                'preference' => $preferenceScore,
            ],
            'explanations' => $explanations,
        ];
    }

    private function evaluateSkills(Portfolio $portfolio, Opportunity $opportunity, array &$explanations): int
    {
        $requiredSkills = $opportunity->skills;
        $userSkills = $portfolio->skills->pluck('name')->map(fn($s) => strtolower(trim($s)))->toArray();

        if ($requiredSkills->isEmpty()) {
            // Fallback to searching title/description keywords if opportunity_skills not defined
            $keywords = array_filter(explode(' ', strtolower($opportunity->title . ' ' . $opportunity->category)));
            if (empty($keywords)) return 80;
            $matched = 0;
            foreach ($keywords as $kw) {
                if (strlen($kw) > 2 && in_array($kw, $userSkills)) {
                    $matched++;
                }
            }
            return count($keywords) > 0 ? (int) min(100, ($matched / count($keywords)) * 100 + 50) : 75;
        }

        $matchedCount = 0;
        $totalWeight = 0;

        foreach ($requiredSkills as $reqSkill) {
            $weight = $reqSkill->weight ?? 1;
            $totalWeight += $weight;
            $name = strtolower(trim($reqSkill->skill_name));

            foreach ($userSkills as $userSkill) {
                if (str_contains($userSkill, $name) || str_contains($name, $userSkill)) {
                    $matchedCount += $weight;
                    break;
                }
            }
        }

        $score = $totalWeight > 0 ? (int) round(($matchedCount / $totalWeight) * 100) : 80;
        if ($score >= 80) {
            $explanations[] = "Strong skills alignment with {$matchedCount} of {$totalWeight} required skill weights matched.";
        } elseif ($score < 50) {
            $explanations[] = "Limited skill overlap found between opportunity requirements and candidate portfolio skills.";
        }

        return $score;
    }

    private function evaluateExperience(Portfolio $portfolio, Opportunity $opportunity, array &$explanations): int
    {
        $experiences = $portfolio->experiences;
        $totalMonths = 0;

        foreach ($experiences as $exp) {
            $start = $exp->start_date ? \Carbon\Carbon::parse($exp->start_date) : null;
            $end = $exp->end_date ? \Carbon\Carbon::parse($exp->end_date) : \Carbon\Carbon::now();
            if ($start) {
                $totalMonths += max(1, $start->diffInMonths($end));
            }
        }

        $totalYears = round($totalMonths / 12, 1);
        $reqMinYears = $opportunity->min_experience ?? 0;
        $reqMaxYears = $opportunity->max_experience ?? 20;

        if ($totalYears >= $reqMinYears && $totalYears <= $reqMaxYears) {
            $score = 100;
            $explanations[] = "Candidate has {$totalYears} years of total experience, satisfying required range ({$reqMinYears}-{$reqMaxYears} yrs).";
        } elseif ($totalYears < $reqMinYears) {
            $diff = $reqMinYears - $totalYears;
            $score = max(30, (int) round(100 - ($diff * 20)));
            $explanations[] = "Candidate has {$totalYears} years vs minimum requirement of {$reqMinYears} years.";
        } else {
            $score = 90; // Slightly overqualified
            $explanations[] = "Candidate is highly experienced ({$totalYears} years).";
        }

        return $score;
    }

    private function evaluateEducation(Portfolio $portfolio, Opportunity $opportunity, array &$explanations): int
    {
        if (empty($opportunity->education_required)) {
            return 90;
        }

        $userDegrees = $portfolio->education->pluck('degree')->map(fn($d) => strtolower($d))->join(' ');
        $reqEducation = strtolower($opportunity->education_required);

        if (str_contains($userDegrees, $reqEducation) || (str_contains($reqEducation, 'bachelor') && str_contains($userDegrees, 'bachelor')) || (str_contains($reqEducation, 'master') && str_contains($userDegrees, 'master'))) {
            $explanations[] = "Candidate education aligns with requested qualification: {$opportunity->education_required}.";
            return 100;
        }

        if ($portfolio->education->isNotEmpty()) {
            return 75;
        }

        return 50;
    }

    private function evaluateIndustry(Portfolio $portfolio, Opportunity $opportunity, array &$explanations): int
    {
        if (empty($opportunity->industry)) {
            return 85;
        }

        $portfolioOrg = strtolower($portfolio->organization ?? '');
        $oppIndustry = strtolower($opportunity->industry);

        if (str_contains($portfolioOrg, $oppIndustry) || str_contains($oppIndustry, $portfolioOrg)) {
            $explanations[] = "Direct industry match between candidate background and opportunity.";
            return 100;
        }

        return 70;
    }

    private function evaluateLocation(Portfolio $portfolio, Opportunity $opportunity, ?ProfessionalPreference $pref, array &$explanations): int
    {
        // If remote, minimal penalty
        if (in_array(strtolower($opportunity->location_type), ['remote', 'hybrid'])) {
            $explanations[] = "Opportunity supports Remote/Hybrid location.";
            return 100;
        }

        $userCity = strtolower(trim($portfolio->city ?? ''));
        $userCountry = strtolower(trim($portfolio->country ?? ''));
        $oppCity = strtolower(trim($opportunity->city ?? ''));
        $oppCountry = strtolower(trim($opportunity->country ?? ''));

        if (!empty($userCity) && !empty($oppCity) && $userCity === $oppCity) {
            $explanations[] = "Candidate is located in the same city ({$opportunity->city}).";
            return 100;
        }

        if (!empty($userCountry) && !empty($oppCountry) && $userCountry === $oppCountry) {
            $explanations[] = "Candidate is located in the same country ({$opportunity->country}).";
            return 80;
        }

        if ($pref && $pref->willing_to_relocate) {
            $explanations[] = "Candidate indicated willingness to relocate.";
            return 85;
        }

        return 50;
    }

    private function evaluatePreferences(?ProfessionalPreference $pref, Opportunity $opportunity, array &$explanations): int
    {
        if (!$pref) {
            return 80;
        }

        if ($pref->availability === 'not_looking') {
            return 40;
        }

        $score = 80;

        if ($pref->remote_preference === 'remote_only' && $opportunity->location_type !== 'remote') {
            $score -= 20;
        }

        if (!empty($pref->preferred_titles) && is_array($pref->preferred_titles)) {
            $titleLower = strtolower($opportunity->title);
            foreach ($pref->preferred_titles as $prefTitle) {
                if (str_contains($titleLower, strtolower($prefTitle))) {
                    $score += 15;
                    break;
                }
            }
        }

        return min(100, max(20, $score));
    }
}

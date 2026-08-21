<?php

namespace App\Services;

use App\Models\Opportunity;
use App\Models\User;
use App\Models\Portfolio;
use App\Models\ProfessionalPreference;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class JobMatchingService
{
    /**
     * Common stop words to filter out during keyword extraction.
     */
    protected array $stopWords = [
        'and', 'or', 'the', 'a', 'an', 'in', 'on', 'at', 'to', 'for', 'with', 'by',
        'of', 'is', 'are', 'was', 'were', 'be', 'been', 'being', 'have', 'has', 'had',
        'do', 'does', 'did', 'senior', 'junior', 'lead', 'principal', 'staff', 'head',
        'associate', 'intern', 'manager', 'director', 'specialist', 'expert', 'job',
        'role', 'position', 'work', 'working', 'experience', 'years'
    ];

    /**
     * Domain synonym maps for technology and role terms.
     */
    protected array $synonyms = [
        'laravel' => ['php', 'backend', 'fullstack', 'web', 'framework'],
        'php' => ['laravel', 'backend', 'symfony', 'wordpress', 'codeigniter'],
        'react' => ['javascript', 'js', 'frontend', 'typescript', 'nextjs', 'reactjs'],
        'vue' => ['javascript', 'js', 'frontend', 'nuxt', 'vuejs'],
        'angular' => ['javascript', 'js', 'typescript', 'frontend'],
        'node' => ['javascript', 'express', 'nestjs', 'backend', 'nodejs'],
        'python' => ['django', 'flask', 'fastapi', 'backend', 'ai', 'machine learning', 'data'],
        'java' => ['spring', 'springboot', 'backend'],
        'sql' => ['mysql', 'postgresql', 'postgres', 'database', 'sqlite', 'oracle'],
        'devops' => ['aws', 'docker', 'kubernetes', 'ci/cd', 'cloud', 'linux', 'azure'],
        'mobile' => ['flutter', 'react native', 'ios', 'android', 'swift', 'kotlin'],
        'designer' => ['ui', 'ux', 'figma', 'product design', 'graphic', 'web design'],
        'frontend' => ['javascript', 'html', 'css', 'react', 'vue', 'angular', 'web'],
        'backend' => ['php', 'python', 'node', 'java', 'sql', 'laravel', 'api', 'server'],
        'fullstack' => ['php', 'laravel', 'react', 'javascript', 'vue', 'node', 'sql', 'frontend', 'backend'],
    ];

    /**
     * Calculate match score breakdown between a user candidate and an opportunity.
     * Uses AI semantic analysis if an OpenAI key is configured, or advanced heuristic domain matching.
     *
     * @param User $user
     * @param Opportunity $opportunity
     * @return array
     */
    public function calculateMatch(User $user, Opportunity $opportunity): array
    {
        $portfolio = $user->portfolio;

        // If no portfolio exists, return default zero match score
        if (!$portfolio) {
            return [
                'overall_score' => 0,
                'breakdown' => [
                    'role' => 0,
                    'skills' => 0,
                    'experience' => 0,
                    'education' => 0,
                    'industry' => 0,
                    'location' => 0,
                    'preference' => 0,
                ],
                'explanations' => ['Candidate has not set up a professional portfolio yet.'],
            ];
        }

        // Check if OpenAI API key is configured for AI match score calculation
        $openAiKey = config('services.openai.api_key') ?: env('OPENAI_API_KEY');
        if (!empty($openAiKey)) {
            $aiMatch = $this->calculateAiMatch($portfolio, $opportunity, $openAiKey);
            if ($aiMatch !== null) {
                return $aiMatch;
            }
        }

        // Traditional / Heuristic AI-like Matching Algorithm
        return $this->calculateTraditionalMatch($user, $portfolio, $opportunity);
    }

    /**
     * Advanced Traditional Matching Engine
     * Evaluates Role Relevance, Relevant Experience Years, Technical Skills, Education & Location.
     */
    protected function calculateTraditionalMatch(User $user, Portfolio $portfolio, Opportunity $opportunity): array
    {
        $explanations = [];

        // 1. Role Title & Domain Relevance (Weight: 25%)
        $roleScore = $this->evaluateRoleFit($portfolio, $opportunity, $explanations);

        // 2. Technical Skills & Stack Match (Weight: 30%)
        $skillsScore = $this->evaluateSkills($portfolio, $opportunity, $explanations);

        // 3. Relevant Work Experience Duration (Weight: 20%)
        $experienceScore = $this->evaluateExperience($portfolio, $opportunity, $explanations);

        // 4. Education Qualification Alignment (Weight: 10%)
        $educationScore = $this->evaluateEducation($portfolio, $opportunity, $explanations);

        // 5. Industry Alignment (Weight: 5%)
        $industryScore = $this->evaluateIndustry($portfolio, $opportunity, $explanations);

        // 6. Location & Location Type Fit (Weight: 5%)
        $locationScore = $this->evaluateLocation($portfolio, $opportunity, $user->professionalPreference, $explanations);

        // 7. Career Preferences Fit (Weight: 5%)
        $preferenceScore = $this->evaluatePreferences($user->professionalPreference, $opportunity, $explanations);

        // Weighted Overall Score
        $rawScore = (
            ($roleScore * 0.25) +
            ($skillsScore * 0.30) +
            ($experienceScore * 0.20) +
            ($educationScore * 0.10) +
            ($industryScore * 0.05) +
            ($locationScore * 0.05) +
            ($preferenceScore * 0.05)
        );

        // Apply Domain Misalignment Penalty: If role and skills have minimal relevance, cap the score
        if ($roleScore < 25 && $skillsScore < 25) {
            $rawScore = min($rawScore, 20);
            $explanations[] = "Significant domain mismatch: Candidate background and skills have minimal relevance to this role.";
        }

        $overallScore = (int) round($rawScore);

        return [
            'overall_score' => min(100, max(0, $overallScore)),
            'breakdown' => [
                'role' => $roleScore,
                'skills' => $skillsScore,
                'experience' => $experienceScore,
                'education' => $educationScore,
                'industry' => $industryScore,
                'location' => $locationScore,
                'preference' => $preferenceScore,
            ],
            'explanations' => array_values(array_unique($explanations)),
        ];
    }

    /**
     * 1. Evaluate Role Title and Domain Alignment
     */
    private function evaluateRoleFit(Portfolio $portfolio, Opportunity $opportunity, array &$explanations): int
    {
        $targetTitle = strtolower($opportunity->title . ' ' . $opportunity->category);
        $targetKeywords = $this->extractKeywords($targetTitle);

        if (empty($targetKeywords)) {
            return 80;
        }

        // Candidate positions from headline and experience records
        $candidatePositions = [];
        if (!empty($portfolio->position)) {
            $candidatePositions[] = strtolower($portfolio->position);
        }
        foreach ($portfolio->experiences as $exp) {
            if (!empty($exp->position)) {
                $candidatePositions[] = strtolower($exp->position);
            }
        }

        if (empty($candidatePositions)) {
            $explanations[] = "Candidate has not listed a specific job position or title in portfolio.";
            return 30;
        }

        $maxRelevance = 0;
        foreach ($candidatePositions as $pos) {
            $posKeywords = $this->extractKeywords($pos);
            $relevance = $this->calculateTokenOverlap($targetKeywords, $posKeywords);
            if ($relevance > $maxRelevance) {
                $maxRelevance = $relevance;
            }
        }

        $score = (int) round($maxRelevance * 100);

        if ($score >= 80) {
            $explanations[] = "High role alignment: Candidate's past titles match target position ({$opportunity->title}).";
        } elseif ($score < 30) {
            $explanations[] = "Low position alignment: Candidate's past role titles differ significantly from {$opportunity->title}.";
        }

        return max(10, min(100, $score));
    }

    /**
     * 2. Evaluate Relevant Work Experience Duration (Filtering out Irrelevant Positions)
     */
    private function evaluateExperience(Portfolio $portfolio, Opportunity $opportunity, array &$explanations): int
    {
        $experiences = $portfolio->experiences;
        $targetKeywords = $this->extractKeywords(strtolower($opportunity->title . ' ' . $opportunity->category . ' ' . $opportunity->industry));
        
        $oppSkills = $opportunity->skills->pluck('skill_name')->map(fn($s) => strtolower($s))->toArray();
        $targetKeywords = array_unique(array_merge($targetKeywords, $oppSkills));

        $totalMonths = 0;
        $relevantMonths = 0;

        foreach ($experiences as $exp) {
            $start = $exp->start_date ? \Carbon\Carbon::parse($exp->start_date) : null;
            $end = $exp->end_date ? \Carbon\Carbon::parse($exp->end_date) : \Carbon\Carbon::now();
            if (!$start) continue;

            $duration = max(1, $start->diffInMonths($end));
            $totalMonths += $duration;

            // Calculate relevance multiplier of this specific job entry
            $expText = strtolower(($exp->position ?? '') . ' ' . ($exp->description ?? ''));
            $expKeywords = $this->extractKeywords($expText);

            $overlap = $this->calculateTokenOverlap($targetKeywords, $expKeywords);

            if ($overlap >= 0.35) {
                $relevanceMultiplier = 1.0; // Fully relevant experience
            } elseif ($overlap >= 0.15) {
                $relevanceMultiplier = 0.5; // Partially relevant experience
            } else {
                $relevanceMultiplier = 0.0; // Irrelevant experience (e.g. Chef vs Developer)
            }

            $relevantMonths += ($duration * $relevanceMultiplier);
        }

        $totalYears = round($totalMonths / 12, 1);
        $relevantYears = round($relevantMonths / 12, 1);
        $reqMinYears = $opportunity->min_experience ?? 0;
        $reqMaxYears = $opportunity->max_experience ?? 20;

        if ($experiences->isEmpty()) {
            if ($reqMinYears == 0) {
                return 80;
            }
            $explanations[] = "No formal work experience listed in portfolio (required min: {$reqMinYears} yrs).";
            return 20;
        }

        // Calculate score based on RELEVANT years rather than raw total years
        if ($relevantYears >= $reqMinYears) {
            $score = 100;
            if ($totalYears > $relevantYears) {
                $explanations[] = "Candidate has {$relevantYears} years of RELEVANT experience (out of {$totalYears} yrs total), satisfying the required {$reqMinYears}+ years.";
            } else {
                $explanations[] = "Candidate has {$relevantYears} years of relevant experience, satisfying the required range ({$reqMinYears}-{$reqMaxYears} yrs).";
            }
        } elseif ($relevantYears > 0) {
            $ratio = $reqMinYears > 0 ? ($relevantYears / $reqMinYears) : 0.8;
            $score = max(25, (int) round($ratio * 85));
            $explanations[] = "Candidate has {$relevantYears} years of relevant experience vs minimum requirement of {$reqMinYears} years (total experience: {$totalYears} yrs).";
        } else {
            $score = 15;
            $explanations[] = "Candidate has {$totalYears} years of total work experience, but 0 years are relevant to {$opportunity->title}.";
        }

        return $score;
    }

    /**
     * 3. Evaluate Technical Skills Alignment
     */
    private function evaluateSkills(Portfolio $portfolio, Opportunity $opportunity, array &$explanations): int
    {
        $requiredSkills = $opportunity->skills;
        $userSkills = $portfolio->skills->pluck('name')->map(fn($s) => strtolower(trim($s)))->toArray();

        // Include skills mentioned in projects & experience descriptions
        foreach ($portfolio->projects as $proj) {
            $userSkills = array_merge($userSkills, $this->extractKeywords(strtolower($proj->title . ' ' . $proj->description)));
        }
        $userSkills = array_unique($userSkills);

        if ($requiredSkills->isEmpty()) {
            $keywords = $this->extractKeywords(strtolower($opportunity->title . ' ' . $opportunity->category));
            if (empty($keywords)) return 75;
            $matched = 0;
            foreach ($keywords as $kw) {
                if ($this->hasSkillMatch($kw, $userSkills)) {
                    $matched++;
                }
            }
            $score = (int) min(100, ($matched / count($keywords)) * 100);
            return max(20, $score);
        }

        $matchedCount = 0;
        $totalWeight = 0;

        foreach ($requiredSkills as $reqSkill) {
            $weight = $reqSkill->weight ?? 1;
            $totalWeight += $weight;
            $name = strtolower(trim($reqSkill->skill_name));

            if ($this->hasSkillMatch($name, $userSkills)) {
                $matchedCount += $weight;
            }
        }

        $score = $totalWeight > 0 ? (int) round(($matchedCount / $totalWeight) * 100) : 75;

        if ($score >= 80) {
            $explanations[] = "Strong skills alignment: Candidate matches {$matchedCount} of {$totalWeight} required skill points.";
        } elseif ($score < 40) {
            $explanations[] = "Limited skill overlap with required opportunity skills.";
        }

        return $score;
    }

    /**
     * Check skill match considering synonyms and aliases
     */
    private function hasSkillMatch(string $reqSkill, array $userSkills): bool
    {
        foreach ($userSkills as $userSkill) {
            if ($userSkill === $reqSkill || str_contains($userSkill, $reqSkill) || str_contains($reqSkill, $userSkill)) {
                return true;
            }
            // Check synonyms
            if (isset($this->synonyms[$reqSkill])) {
                foreach ($this->synonyms[$reqSkill] as $syn) {
                    if (str_contains($userSkill, $syn) || str_contains($syn, $userSkill)) {
                        return true;
                    }
                }
            }
        }
        return false;
    }

    /**
     * 4. Evaluate Education Qualification Alignment
     */
    private function evaluateEducation(Portfolio $portfolio, Opportunity $opportunity, array &$explanations): int
    {
        if (empty($opportunity->education_required)) {
            return 85;
        }

        $userDegrees = strtolower($portfolio->education->pluck('degree')->join(' '));
        $reqEducation = strtolower($opportunity->education_required);

        if (empty($userDegrees)) {
            return 50;
        }

        if (str_contains($userDegrees, $reqEducation) || str_contains($reqEducation, $userDegrees)) {
            $explanations[] = "Candidate education directly aligns with requested qualification: {$opportunity->education_required}.";
            return 100;
        }

        if ((str_contains($reqEducation, 'bachelor') && str_contains($userDegrees, 'bachelor')) ||
            (str_contains($reqEducation, 'master') && str_contains($userDegrees, 'master')) ||
            (str_contains($reqEducation, 'phd') && str_contains($userDegrees, 'phd'))) {
            $explanations[] = "Candidate holds a qualifying degree matching level of {$opportunity->education_required}.";
            return 90;
        }

        return 65;
    }

    /**
     * 5. Evaluate Industry Match
     */
    private function evaluateIndustry(Portfolio $portfolio, Opportunity $opportunity, array &$explanations): int
    {
        if (empty($opportunity->industry)) {
            return 80;
        }

        $oppIndustry = strtolower($opportunity->industry);
        $portfolioOrg = strtolower($portfolio->organization ?? '');
        $category = strtolower($opportunity->category ?? '');

        if (!empty($portfolioOrg) && (str_contains($portfolioOrg, $oppIndustry) || str_contains($oppIndustry, $portfolioOrg))) {
            $explanations[] = "Direct industry match with candidate's background organization.";
            return 100;
        }

        if (!empty($category) && (str_contains($category, $oppIndustry) || str_contains($oppIndustry, $category))) {
            return 85;
        }

        return 60;
    }

    /**
     * 6. Evaluate Location Match
     */
    private function evaluateLocation(Portfolio $portfolio, Opportunity $opportunity, ?ProfessionalPreference $pref, array &$explanations): int
    {
        if (in_array(strtolower($opportunity->location_type), ['remote', 'hybrid'])) {
            return 100;
        }

        $userCity = strtolower(trim($portfolio->city ?? ''));
        $userCountry = strtolower(trim($portfolio->country ?? ''));
        $oppCity = strtolower(trim($opportunity->city ?? ''));
        $oppCountry = strtolower(trim($opportunity->country ?? ''));

        if (!empty($userCity) && !empty($oppCity) && $userCity === $oppCity) {
            $explanations[] = "Location match: Candidate is located in {$opportunity->city}.";
            return 100;
        }

        if (!empty($userCountry) && !empty($oppCountry) && $userCountry === $oppCountry) {
            return 80;
        }

        if ($pref && $pref->willing_to_relocate) {
            $explanations[] = "Candidate indicated willingness to relocate for opportunities.";
            return 85;
        }

        return 45;
    }

    /**
     * 7. Evaluate Preferences Match
     */
    private function evaluatePreferences(?ProfessionalPreference $pref, Opportunity $opportunity, array &$explanations): int
    {
        if (!$pref) {
            return 80;
        }

        if ($pref->availability === 'not_looking') {
            return 30;
        }

        $score = 80;
        if ($pref->remote_preference === 'remote_only' && $opportunity->location_type !== 'remote') {
            $score -= 25;
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

    /**
     * Calculate AI Match using OpenAI API when key is configured.
     */
    protected function calculateAiMatch(Portfolio $portfolio, Opportunity $opportunity, string $apiKey): ?array
    {
        try {
            $prompt = $this->buildAiPrompt($portfolio, $opportunity);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(8)->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    ['role' => 'system', 'content' => 'You are an expert HR AI evaluator. Return JSON with overall_score (0-100), breakdown (role, skills, experience, education, location), and explanations (array of short strings).'],
                    ['role' => 'user', 'content' => $prompt]
                ],
                'response_format' => ['type' => 'json_object'],
                'temperature' => 0.2,
            ]);

            if ($response->successful()) {
                $data = $response->json('choices.0.message.content');
                $parsed = json_decode($data, true);
                if (isset($parsed['overall_score'])) {
                    return [
                        'overall_score' => (int) $parsed['overall_score'],
                        'breakdown' => [
                            'role' => (int) ($parsed['breakdown']['role'] ?? 70),
                            'skills' => (int) ($parsed['breakdown']['skills'] ?? 70),
                            'experience' => (int) ($parsed['breakdown']['experience'] ?? 70),
                            'education' => (int) ($parsed['breakdown']['education'] ?? 70),
                            'industry' => (int) ($parsed['breakdown']['industry'] ?? 70),
                            'location' => (int) ($parsed['breakdown']['location'] ?? 70),
                            'preference' => (int) ($parsed['breakdown']['preference'] ?? 70),
                        ],
                        'explanations' => (array) ($parsed['explanations'] ?? []),
                    ];
                }
            }
        } catch (\Exception $e) {
            Log::warning('AI Match evaluation failed, falling back to heuristic matching: ' . $e->getMessage());
        }

        return null; // Fallback to heuristic
    }

    private function buildAiPrompt(Portfolio $portfolio, Opportunity $opportunity): string
    {
        $skills = $portfolio->skills->pluck('name')->join(', ');
        $expList = $portfolio->experiences->map(fn($e) => "{$e->position} at {$e->company} ({$e->start_date} to {$e->end_date}): {$e->description}")->join("\n");
        $reqSkills = $opportunity->skills->pluck('skill_name')->join(', ');

        return "Evaluate candidate fit for Job Opportunity.\n" .
               "JOB TITLE: {$opportunity->title}\n" .
               "REQUIRED SKILLS: {$reqSkills}\n" .
               "REQUIRED MIN EXP: {$opportunity->min_experience} years\n" .
               "CANDIDATE HEADLINE: {$portfolio->position}\n" .
               "CANDIDATE SKILLS: {$skills}\n" .
               "CANDIDATE WORK HISTORY:\n{$expList}\n" .
               "Return JSON format: { \"overall_score\": int, \"breakdown\": { \"role\": int, \"skills\": int, \"experience\": int, \"education\": int, \"location\": int }, \"explanations\": [string] }";
    }

    /**
     * Extract clean keywords from text string
     */
    protected function extractKeywords(string $text): array
    {
        $clean = strtolower(preg_replace('/[^a-zA-Z0-9\s\#\+\.\/]/', ' ', $text));
        $words = array_filter(explode(' ', $clean));
        $filtered = [];

        foreach ($words as $w) {
            $w = trim($w);
            if (strlen($w) >= 2 && !in_array($w, $this->stopWords)) {
                $filtered[] = $w;
            }
        }

        return array_values(array_unique($filtered));
    }

    /**
     * Calculate Token Overlap between two arrays of keywords
     */
    protected function calculateTokenOverlap(array $targetKeywords, array $candidateKeywords): float
    {
        if (empty($targetKeywords) || empty($candidateKeywords)) {
            return 0.0;
        }

        $matched = 0;
        foreach ($targetKeywords as $targetKw) {
            foreach ($candidateKeywords as $candKw) {
                if ($targetKw === $candKw || str_contains($candKw, $targetKw) || str_contains($targetKw, $candKw)) {
                    $matched++;
                    break;
                }
                // Check synonym maps
                if (isset($this->synonyms[$targetKw])) {
                    foreach ($this->synonyms[$targetKw] as $syn) {
                        if (str_contains($candKw, $syn) || str_contains($syn, $candKw)) {
                            $matched += 0.8;
                            break 2;
                        }
                    }
                }
            }
        }

        return min(1.0, $matched / count($targetKeywords));
    }
}

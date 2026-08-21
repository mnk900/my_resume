<?php

namespace App\Services;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;

class SeoService
{
    /**
     * Stored SEO metadata array for current request cycle.
     */
    protected static array $seoData = [];

    /**
     * Set dynamic SEO properties.
     */
    public static function set(array $params = []): void
    {
        static::$seoData = array_merge(static::$seoData, $params);
    }

    /**
     * Get processed SEO metadata array ready for HTML rendering.
     */
    public static function render(): array
    {
        $baseUrl = config('app.url', 'https://myresume.cloud');
        $currentUrl = Request::url();

        $rawTitle = static::$seoData['title'] ?? null;
        $rawDescription = static::$seoData['description'] ?? null;
        $robots = static::$seoData['robots'] ?? 'index, follow';
        $type = static::$seoData['type'] ?? 'website';
        $image = static::$seoData['image'] ?? asset('images/logo.jpeg');
        $canonical = static::$seoData['canonical'] ?? $currentUrl;
        $schema = static::$seoData['schema'] ?? null;
        $keywords = static::$seoData['keywords'] ?? null;

        $title = static::formatTitle($rawTitle);
        $description = static::formatDescription($rawDescription);

        return [
            'title' => $title,
            'description' => $description,
            'keywords' => $keywords ? static::sanitizeText($keywords) : null,
            'robots' => $robots,
            'canonical' => $canonical,
            'og_title' => static::$seoData['og_title'] ?? $title,
            'og_description' => static::$seoData['og_description'] ?? $description,
            'og_url' => $canonical,
            'og_type' => $type,
            'og_image' => $image,
            'og_site_name' => 'MyResume.cloud',
            'twitter_card' => 'summary_large_image',
            'twitter_title' => static::$seoData['twitter_title'] ?? $title,
            'twitter_description' => static::$seoData['twitter_description'] ?? $description,
            'twitter_image' => static::$seoData['twitter_image'] ?? $image,
            'schema' => $schema,
        ];
    }

    /**
     * Format and standardize Page Title: [Primary Page Topic] | MyResume.cloud
     */
    public static function formatTitle(?string $title): string
    {
        if (empty($title)) {
            return 'MyResume.cloud | Professional Portfolios, Jobs & Career Opportunities';
        }

        $clean = static::sanitizeText($title);
        
        // Remove redundant trailing branding if controller passed it manually
        $clean = preg_replace('/\s*\|\s*MyResume\.cloud$/i', '', $clean);
        $clean = preg_replace('/\s*-\s*MyResume\.cloud$/i', '', $clean);

        // Keep target length ~50-65 chars where practical
        if (mb_strlen($clean) > 65) {
            $clean = mb_substr($clean, 0, 62) . '...';
        }

        return $clean . ' | MyResume.cloud';
    }

    /**
     * Format and standardize Meta Description: Clean, non-HTML, 150-160 characters.
     */
    public static function formatDescription(?string $description): string
    {
        if (empty($description)) {
            return 'Create your verified professional portfolio, discover matched job opportunities, connect with top organizations, and prepare for your career with MyResume.cloud.';
        }

        $clean = static::sanitizeText($description);

        if (mb_strlen($clean) > 160) {
            $clean = mb_substr($clean, 0, 157) . '...';
        }

        return $clean;
    }

    /**
     * Clean raw string from HTML tags, newlines, multiple spaces, and XSS artifacts.
     */
    public static function sanitizeText(?string $text): string
    {
        if (empty($text)) {
            return '';
        }

        $stripped = strip_tags($text);
        $stripped = html_entity_decode($stripped, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $stripped = preg_replace('/[\r\n\t]+/', ' ', $stripped);
        $stripped = preg_replace('/\s+/', ' ', $stripped);

        return trim($stripped);
    }

    /**
     * Generate Dynamic Portfolio SEO Array for User Profile
     */
    public static function generatePortfolioSeo($portfolio, bool $isOwner = false): array
    {
        $user = $portfolio->user ?? null;
        $name = $user->name ?? 'Professional';
        $position = static::sanitizeText($portfolio->position ?? '');
        $skills = $portfolio->skills->pluck('name')->take(2)->join(' & ');
        $location = implode(', ', array_filter([$portfolio->city, $portfolio->country]));

        // Build Title Hierarchy
        $titleParts = [$name];
        if (!empty($position)) {
            $titleParts[] = $position;
        } elseif (!empty($portfolio->title)) {
            $titleParts[] = static::sanitizeText($portfolio->title);
        }

        if (!empty($skills)) {
            $titleParts[] = $skills;
        }

        $title = implode(' | ', array_slice($titleParts, 0, 3));

        // Build Description
        $summary = static::sanitizeText($portfolio->description ?? $portfolio->bio ?? '');
        if (!empty($summary)) {
            $description = "Explore {$name}'s professional portfolio. " . Str::limit($summary, 110);
        } else {
            $roleText = !empty($position) ? "{$position} experience" : "professional background";
            $locText = !empty($location) ? " in {$location}" : "";
            $description = "Explore {$name}'s professional portfolio, {$roleText}, skills, projects, and career background{$locText} on MyResume.cloud.";
        }

        // Image
        $image = $portfolio->profile_image
            ? asset('storage/' . $portfolio->profile_image)
            : asset('images/logo.jpeg');

        // Schema JSON-LD Person
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Person',
            'name' => $name,
            'url' => url('/' . ($user->username ?? '')),
            'image' => $image,
        ];
        if (!empty($position)) {
            $schema['jobTitle'] = $position;
        }
        if (!empty($portfolio->organization)) {
            $schema['worksFor'] = [
                '@type' => 'Organization',
                'name' => static::sanitizeText($portfolio->organization)
            ];
        }

        $robots = ($portfolio->is_active && $portfolio->is_public) ? 'index, follow' : 'noindex, nofollow';

        return [
            'title' => $title,
            'description' => $description,
            'type' => 'profile',
            'image' => $image,
            'robots' => $robots,
            'canonical' => url('/' . ($user->username ?? '')),
            'schema' => $schema,
        ];
    }

    /**
     * Generate Dynamic Opportunity/Job SEO Array
     */
    public static function generateOpportunitySeo($opportunity): array
    {
        $jobTitle = static::sanitizeText($opportunity->title);
        $companyName = $opportunity->company ? static::sanitizeText($opportunity->company->name) : 'Employer';
        $location = implode(', ', array_filter([$opportunity->city, $opportunity->country])) ?: ucfirst($opportunity->location_type ?? 'Remote');

        $title = "{$jobTitle} at {$companyName}";
        if (!empty($location)) {
            $title .= " ({$location})";
        }

        $shortDesc = static::sanitizeText($opportunity->description ?? '');
        $description = "Apply for the {$jobTitle} position at {$companyName} in {$location}. " . Str::limit($shortDesc, 100);

        $image = ($opportunity->company && $opportunity->company->logo_path)
            ? asset('storage/' . $opportunity->company->logo_path)
            : asset('images/logo.jpeg');

        // JobPosting JSON-LD Schema
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'JobPosting',
            'title' => $jobTitle,
            'description' => static::sanitizeText($opportunity->description),
            'datePosted' => $opportunity->published_at ? $opportunity->published_at->toIso8601String() : now()->toIso8601String(),
            'employmentType' => strtoupper(str_replace(' ', '_', $opportunity->employment_type ?? 'FULL_TIME')),
            'hiringOrganization' => [
                '@type' => 'Organization',
                'name' => $companyName,
                'sameAs' => $opportunity->company->website ?? url('/company/' . ($opportunity->company->slug ?? '')),
                'logo' => $image,
            ],
            'jobLocation' => [
                '@type' => 'Place',
                'address' => [
                    '@type' => 'PostalAddress',
                    'addressLocality' => $opportunity->city ?? 'Remote',
                    'addressCountry' => $opportunity->country ?? 'Global',
                ]
            ]
        ];

        if ($opportunity->application_deadline) {
            $schema['validThrough'] = $opportunity->application_deadline->toIso8601String();
        }

        if ($opportunity->salary_min || $opportunity->salary_max) {
            $schema['baseSalary'] = [
                '@type' => 'MonetaryAmount',
                'currency' => $opportunity->salary_currency ?? 'USD',
                'value' => [
                    '@type' => 'QuantitativeValue',
                    'minValue' => $opportunity->salary_min,
                    'maxValue' => $opportunity->salary_max,
                    'unitText' => strtoupper($opportunity->salary_period ?? 'MONTH')
                ]
            ];
        }

        return [
            'title' => $title,
            'description' => $description,
            'type' => 'article',
            'image' => $image,
            'robots' => $opportunity->isExpired() ? 'noindex, follow' : 'index, follow',
            'canonical' => url('/job/' . $opportunity->slug),
            'schema' => $schema,
        ];
    }

    /**
     * Generate Dynamic Company SEO Array
     */
    public static function generateCompanySeo($company): array
    {
        $name = static::sanitizeText($company->name);
        $industry = static::sanitizeText($company->industry ?? 'Organization');
        $location = implode(', ', array_filter([$company->city, $company->country]));

        $title = "{$name} | Company Profile & Jobs";
        
        $shortDesc = static::sanitizeText($company->description ?? '');
        $description = "Explore {$name}'s company profile, {$industry} industry background, career opportunities, and hiring information on MyResume.cloud. " . Str::limit($shortDesc, 80);

        $image = $company->logo_path
            ? asset('storage/' . $company->logo_path)
            : asset('images/logo.jpeg');

        // Organization JSON-LD Schema
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => $name,
            'url' => url('/company/' . $company->slug),
            'logo' => $image,
            'description' => Str::limit($shortDesc, 200),
        ];

        if (!empty($company->website)) {
            $schema['sameAs'] = $company->website;
        }

        if (!empty($location)) {
            $schema['address'] = [
                '@type' => 'PostalAddress',
                'addressLocality' => $company->city ?? '',
                'addressCountry' => $company->country ?? ''
            ];
        }

        return [
            'title' => $title,
            'description' => $description,
            'type' => 'profile',
            'image' => $image,
            'robots' => 'index, follow',
            'canonical' => url('/company/' . $company->slug),
            'schema' => $schema,
        ];
    }
}

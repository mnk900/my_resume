<?php

namespace App\Services;

use App\Models\User;
use App\Models\Portfolio;
use App\Models\PortfolioSection;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class PortfolioService
{
    /**
     * Get portfolio by username with all sections.
     */
    public function getByUsername(string $username)
    {
        return Cache::remember("portfolio_{$username}", 3600, function() use ($username) {
            $user = User::where('username', $username)->firstOrFail();
            return Portfolio::where('user_id', $user->id)
                ->where('is_active', true)
                ->with(['user', 'sections', 'skills', 'projects', 'experiences', 'testimonials', 'services', 'certifications', 'education', 'achievements', 'contributions'])
                ->firstOrFail();
        });
    }

    /**
     * Clear cached portfolio for a specific user.
     */
    public function clearCache(string $username)
    {
        Cache::forget("portfolio_{$username}");
    }

    /**
     * Update portfolio data and handle uploads.
     */
    public function updatePortfolio(Portfolio $portfolio, array $data)
    {
        if (isset($data['profile_image']) && $data['profile_image'] instanceof \Illuminate\Http\UploadedFile) {
            $data['profile_image'] = $data['profile_image']->store('profile_images', 'public');
        }

        $portfolio->update($data);
        $this->clearCache($portfolio->user->username);
        return $portfolio;
    }

    /**
     * Add a dynamic section to the portfolio.
     */
    public function addSection(Portfolio $portfolio, array $data)
    {
        if (isset($data['image']) && $data['image'] instanceof \Illuminate\Http\UploadedFile) {
            $data['image_path'] = $data['image']->store('portfolio_images', 'public');
        }

        if (isset($data['file']) && $data['file'] instanceof \Illuminate\Http\UploadedFile) {
            $data['file_path'] = $data['file']->store('portfolio_files', 'public');
        }

        // Determine order
        $data['order'] = $portfolio->sections()->count() + 1;

        $section = $portfolio->sections()->create($data);
        $this->clearCache($portfolio->user->username);
        return $section;
    }
}

<?php

namespace App\Services;

use App\Models\Company;
use App\Models\CompanyMember;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class CompanyService
{
    /**
     * Create a new company profile and assign the user as owner.
     */
    public function createCompany(User $user, array $data): Company
    {
        $slug = Str::slug($data['name']);
        $originalSlug = $slug;
        $count = 1;

        while (Company::where('slug', $slug)->exists()) {
            $slug = "{$originalSlug}-{$count}";
            $count++;
        }

        if (isset($data['logo']) && $data['logo'] instanceof UploadedFile) {
            $data['logo_path'] = $data['logo']->store('company_logos', 'public');
        }

        if (isset($data['cover']) && $data['cover'] instanceof UploadedFile) {
            $data['cover_path'] = $data['cover']->store('company_covers', 'public');
        }

        $company = Company::create([
            'name' => $data['name'],
            'slug' => $slug,
            'logo_path' => $data['logo_path'] ?? null,
            'cover_path' => $data['cover_path'] ?? null,
            'industry' => $data['industry'] ?? null,
            'org_type' => $data['org_type'] ?? null,
            'description' => $data['description'] ?? null,
            'website' => $data['website'] ?? null,
            'email' => $data['email'] ?? $user->email,
            'phone' => $data['phone'] ?? null,
            'country' => $data['country'] ?? null,
            'city' => $data['city'] ?? null,
            'address' => $data['address'] ?? null,
            'social_links' => $data['social_links'] ?? [],
            'company_size' => $data['company_size'] ?? null,
            'founded_year' => $data['founded_year'] ?? null,
            'verification_status' => 'pending',
        ]);

        // Attach owner
        CompanyMember::create([
            'company_id' => $company->id,
            'user_id' => $user->id,
            'role' => 'owner',
            'title' => $data['owner_title'] ?? 'Founder / Authorized Representative',
        ]);

        // Update user type to company_rep if professional
        if ($user->user_type !== 'admin') {
            $user->update(['user_type' => 'company_rep']);
        }

        return $company;
    }

    /**
     * Update existing company profile.
     */
    public function updateCompany(Company $company, array $data): Company
    {
        if (isset($data['logo']) && $data['logo'] instanceof UploadedFile) {
            $data['logo_path'] = $data['logo']->store('company_logos', 'public');
        }

        if (isset($data['cover']) && $data['cover'] instanceof UploadedFile) {
            $data['cover_path'] = $data['cover']->store('company_covers', 'public');
        }

        $company->update(array_filter([
            'name' => $data['name'] ?? $company->name,
            'logo_path' => $data['logo_path'] ?? $company->logo_path,
            'cover_path' => $data['cover_path'] ?? $company->cover_path,
            'industry' => $data['industry'] ?? $company->industry,
            'org_type' => $data['org_type'] ?? $company->org_type,
            'description' => $data['description'] ?? $company->description,
            'website' => $data['website'] ?? $company->website,
            'email' => $data['email'] ?? $company->email,
            'phone' => $data['phone'] ?? $company->phone,
            'country' => $data['country'] ?? $company->country,
            'city' => $data['city'] ?? $company->city,
            'address' => $data['address'] ?? $company->address,
            'social_links' => $data['social_links'] ?? $company->social_links,
            'company_size' => $data['company_size'] ?? $company->company_size,
            'founded_year' => $data['founded_year'] ?? $company->founded_year,
        ]));

        return $company;
    }
}

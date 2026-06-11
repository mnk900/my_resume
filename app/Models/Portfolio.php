<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'title', 'description', 'detailed_bio', 'theme', 'is_active', 'show_contact_info', 'show_email', 'show_phone', 'show_linkedin', 'position', 'city', 'organization', 'country', 'contact_number', 'linkedin_url', 'profile_image', 'show_skills', 'show_projects', 'show_experience', 'show_education', 'show_services', 'show_certifications', 'show_trainings', 'show_achievements', 'show_contributions', 'show_testimonials', 'show_media', 'show_publications'];

    protected $casts = [
        'is_active' => 'boolean',
        'show_contact_info' => 'boolean',
        'show_email' => 'boolean',
        'show_phone' => 'boolean',
        'show_linkedin' => 'boolean',
        'show_skills' => 'boolean',
        'show_projects' => 'boolean',
        'show_experience' => 'boolean',
        'show_education' => 'boolean',
        'show_services' => 'boolean',
        'show_certifications' => 'boolean',
        'show_trainings' => 'boolean',
        'show_achievements' => 'boolean',
        'show_contributions' => 'boolean',
        'show_testimonials' => 'boolean',
        'show_media' => 'boolean',
        'show_publications' => 'boolean',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sections()
    {
        return $this->hasMany(PortfolioSection::class)->orderBy('order');
    }

    public function skills()
    {
        return $this->hasMany(Skill::class);
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function experiences()
    {
        return $this->hasMany(Experience::class);
    }

    public function testimonials()
    {
        return $this->hasMany(Testimonial::class);
    }

    public function services()
    {
        return $this->hasMany(Service::class);
    }

    public function certifications()
    {
        return $this->hasMany(Certification::class);
    }

    public function education()
    {
        return $this->hasMany(Education::class);
    }

    public function trainings()
    {
        return $this->hasMany(Training::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function achievements()
    {
        return $this->hasMany(Achievement::class);
    }

    public function contributions()
    {
        return $this->hasMany(Contribution::class);
    }

    public function settings()
    {
        return $this->hasMany(PortfolioSetting::class);
    }

    public function media()
    {
        return $this->hasMany(Media::class);
    }

    public function publications()
    {
        return $this->hasMany(Publication::class);
    }
}

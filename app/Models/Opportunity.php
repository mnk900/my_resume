<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Opportunity extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id',
        'posted_by_user_id',
        'type',
        'title',
        'slug',
        'description',
        'responsibilities',
        'category',
        'industry',
        'min_experience',
        'max_experience',
        'education_required',
        'location_type',
        'city',
        'country',
        'employment_type',
        'salary_min',
        'salary_max',
        'salary_currency',
        'salary_period',
        'benefits',
        'application_deadline',
        'external_url',
        'is_internal_application',
        'status',
        'is_featured',
        'vacancies_count',
        'published_at',
        'expires_at',
    ];

    protected $casts = [
        'benefits' => 'array',
        'application_deadline' => 'date',
        'published_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_internal_application' => 'boolean',
        'is_featured' => 'boolean',
        'min_experience' => 'integer',
        'max_experience' => 'integer',
        'vacancies_count' => 'integer',
        'salary_min' => 'decimal:2',
        'salary_max' => 'decimal:2',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function postedBy()
    {
        return $this->belongsTo(User::class, 'posted_by_user_id');
    }

    public function skills()
    {
        return $this->hasMany(OpportunitySkill::class);
    }

    public function applications()
    {
        return $this->hasMany(JobApplication::class);
    }

    public function savedByUsers()
    {
        return $this->hasMany(SavedOpportunity::class);
    }

    public function mockInterviews()
    {
        return $this->hasMany(MockInterview::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    public function isExpired(): bool
    {
        if ($this->expires_at && $this->expires_at->isPast()) {
            return true;
        }
        if ($this->application_deadline && $this->application_deadline->isPast()) {
            return true;
        }
        return false;
    }
}

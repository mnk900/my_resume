<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'logo_path',
        'cover_path',
        'industry',
        'org_type',
        'description',
        'website',
        'email',
        'phone',
        'country',
        'city',
        'address',
        'social_links',
        'company_size',
        'founded_year',
        'verification_status',
        'verified_at',
    ];

    protected $casts = [
        'social_links' => 'array',
        'verified_at' => 'datetime',
        'founded_year' => 'integer',
    ];

    public function members()
    {
        return $this->hasMany(CompanyMember::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'company_members')->withPivot('role', 'title')->withTimestamps();
    }

    public function opportunities()
    {
        return $this->hasMany(Opportunity::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function shortlists()
    {
        return $this->hasMany(CandidateShortlist::class);
    }

    public function candidateNotes()
    {
        return $this->hasMany(CandidateNote::class);
    }

    public function followers()
    {
        return $this->morphMany(Follow::class, 'followable');
    }

    public function isVerified(): bool
    {
        return $this->verification_status === 'verified';
    }
}

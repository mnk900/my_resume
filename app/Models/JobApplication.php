<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'opportunity_id',
        'user_id',
        'is_public_applicant',
        'applicant_name',
        'applicant_email',
        'applicant_phone',
        'is_currently_employed',
        'current_designation',
        'current_organization_name',
        'current_organization_address',
        'cover_letter',
        'cover_letter_path',
        'resume_version_path',
        'status',
        'status_notes',
        'match_score',
        'applied_at',
    ];

    protected $casts = [
        'is_public_applicant' => 'boolean',
        'is_currently_employed' => 'boolean',
        'match_score' => 'decimal:2',
        'applied_at' => 'datetime',
    ];

    public function opportunity()
    {
        return $this->belongsTo(Opportunity::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get candidate display name (works for both platform users & public applicants)
     */
    public function getCandidateNameAttribute(): string
    {
        if ($this->is_public_applicant) {
            return $this->applicant_name ?? 'Guest Candidate';
        }
        return $this->user->name ?? 'Registered Candidate';
    }

    /**
     * Get candidate email address
     */
    public function getCandidateEmailAttribute(): string
    {
        if ($this->is_public_applicant) {
            return $this->applicant_email ?? '';
        }
        return $this->user->email ?? '';
    }

    /**
     * Get candidate contact number
     */
    public function getCandidatePhoneAttribute(): ?string
    {
        if ($this->is_public_applicant) {
            return $this->applicant_phone;
        }
        return $this->user->portfolio->contact_number ?? null;
    }
}

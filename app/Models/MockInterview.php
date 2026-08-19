<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MockInterview extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'opportunity_id',
        'job_title',
        'target_skills',
        'status',
        'overall_score',
        'technical_score',
        'communication_score',
        'readiness_rating',
        'feedback_summary',
        'detailed_report',
        'completed_at',
    ];

    protected $casts = [
        'target_skills' => 'array',
        'detailed_report' => 'array',
        'completed_at' => 'datetime',
        'overall_score' => 'integer',
        'technical_score' => 'integer',
        'communication_score' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function opportunity()
    {
        return $this->belongsTo(Opportunity::class);
    }

    public function questions()
    {
        return $this->hasMany(MockInterviewQuestion::class)->orderBy('question_number');
    }
}

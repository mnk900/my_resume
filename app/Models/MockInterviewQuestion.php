<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MockInterviewQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'mock_interview_id',
        'question_number',
        'question_category',
        'question_text',
        'expected_key_points',
        'user_answer',
        'score',
        'feedback',
        'sample_improved_answer',
    ];

    protected $casts = [
        'expected_key_points' => 'array',
        'question_number' => 'integer',
        'score' => 'integer',
    ];

    public function mockInterview()
    {
        return $this->belongsTo(MockInterview::class);
    }
}

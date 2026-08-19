<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfessionalPreference extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'availability',
        'preferred_titles',
        'preferred_industries',
        'preferred_locations',
        'remote_preference',
        'salary_expectation_min',
        'salary_expectation_currency',
        'willing_to_relocate',
    ];

    protected $casts = [
        'preferred_titles' => 'array',
        'preferred_industries' => 'array',
        'preferred_locations' => 'array',
        'willing_to_relocate' => 'boolean',
        'salary_expectation_min' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpportunitySkill extends Model
{
    use HasFactory;

    protected $fillable = [
        'opportunity_id',
        'skill_name',
        'is_required',
        'weight',
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'weight' => 'integer',
    ];

    public function opportunity()
    {
        return $this->belongsTo(Opportunity::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Training extends Model
{
    use HasFactory;

    protected $fillable = [
        'portfolio_id',
        'title',
        'institution',
        'description',
        'date',
        'is_active'
    ];

    protected $casts = [
        'date' => 'date',
        'is_active' => 'boolean'
    ];

    public function portfolio()
    {
        return $this->belongsTo(Portfolio::class);
    }
}

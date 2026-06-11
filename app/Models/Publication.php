<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Publication extends Model
{
    use HasFactory;

    protected $fillable = [
        'portfolio_id',
        'type',
        'authors',
        'year',
        'title',
        'publisher',
        'link',
        'report_path'
    ];

    /**
     * Get the portfolio that owns this publication.
     */
    public function portfolio()
    {
        return $this->belongsTo(Portfolio::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;

    protected $fillable = [
        'portfolio_id',
        'type',
        'title',
        'channel_platform',
        'newspaper_name',
        'date',
        'link'
    ];

    /**
     * Get the portfolio that owns this media appearance.
     */
    public function portfolio()
    {
        return $this->belongsTo(Portfolio::class);
    }
}

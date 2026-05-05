<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PortfolioSection extends Model
{
    use HasFactory;

    protected $fillable = ['portfolio_id', 'type', 'title', 'content', 'order', 'file_path', 'image_path'];

    protected $casts = [
        'content' => 'array',
    ];

    public function portfolio()
    {
        return $this->belongsTo(Portfolio::class);
    }
}

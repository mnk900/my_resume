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
        'date'
    ];

    protected $casts = [
        'date' => 'date'
    ];

    public function portfolio()
    {
        return $this->belongsTo(Portfolio::class);
    }
}

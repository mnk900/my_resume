<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasFactory;
    protected $fillable = ['portfolio_id', 'client_name', 'designation', 'content', 'is_active'];
    protected $casts = ['is_active' => 'boolean'];

    public function portfolio()
    {
        return $this->belongsTo(Portfolio::class);
    }
}

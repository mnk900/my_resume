<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasFactory;
    protected $fillable = ['portfolio_id', 'client_name', 'designation', 'content'];

    public function portfolio()
    {
        return $this->belongsTo(Portfolio::class);
    }
}

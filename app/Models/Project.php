<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;
    protected $fillable = ['portfolio_id', 'title', 'description', 'link', 'image_path'];

    public function portfolio()
    {
        return $this->belongsTo(Portfolio::class);
    }
}

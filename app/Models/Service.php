<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;
    protected $fillable = ['portfolio_id', 'title', 'description', 'icon'];

    public function portfolio()
    {
        return $this->belongsTo(Portfolio::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certification extends Model
{
    use HasFactory;
    protected $fillable = ['portfolio_id', 'name', 'issuer', 'date', 'link'];

    public function portfolio()
    {
        return $this->belongsTo(Portfolio::class);
    }
}

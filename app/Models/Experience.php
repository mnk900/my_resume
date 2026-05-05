<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    use HasFactory;
    protected $fillable = ['portfolio_id', 'company', 'position', 'start_date', 'end_date', 'description'];
    protected $casts = ['start_date' => 'date', 'end_date' => 'date'];

    public function portfolio()
    {
        return $this->belongsTo(Portfolio::class);
    }
}

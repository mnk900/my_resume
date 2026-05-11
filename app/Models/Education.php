<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    use HasFactory;
    protected $table = 'education';
    protected $fillable = ['portfolio_id', 'institution', 'degree', 'start_date', 'end_date'];
    protected $casts = ['start_date' => 'date', 'end_date' => 'date'];

    public function portfolio()
    {
        return $this->belongsTo(Portfolio::class);
    }
}

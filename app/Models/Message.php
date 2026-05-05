<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'portfolio_id',
        'name',
        'email',
        'subject',
        'message',
        'is_read',
        'reply'
    ];

    public function portfolio()
    {
        return $this->belongsTo(Portfolio::class);
    }
}

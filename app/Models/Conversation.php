<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_one_id',
        'user_two_id',
        'last_message_at',
    ];

    protected $casts = [
        'last_message_at' => 'datetime',
    ];

    public function userOne()
    {
        return $this->belongsTo(User::class, 'user_one_id');
    }

    public function userTwo()
    {
        return $this->belongsTo(User::class, 'user_two_id');
    }

    public function messages()
    {
        return $this->hasMany(DirectMessage::class);
    }

    public function latestMessage()
    {
        return $this->hasOne(DirectMessage::class)->latestOfMany();
    }

    public function getOtherUser($authUserId)
    {
        return $this->user_one_id == $authUserId ? $this->userTwo : $this->userOne;
    }

    public function unreadCountFor($authUserId)
    {
        return $this->messages()
            ->where('receiver_id', $authUserId)
            ->where('is_read', false)
            ->count();
    }

    public static function findOrCreateBetween($userId1, $userId2)
    {
        $min = min($userId1, $userId2);
        $max = max($userId1, $userId2);

        return static::firstOrCreate([
            'user_one_id' => $min,
            'user_two_id' => $max,
        ]);
    }
}

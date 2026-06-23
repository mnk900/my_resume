<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function portfolio()
    {
        return $this->hasOne(Portfolio::class);
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function connectionsSent()
    {
        return $this->hasMany(Connection::class, 'sender_id');
    }

    public function connectionsReceived()
    {
        return $this->hasMany(Connection::class, 'receiver_id');
    }

    public function connectionWith(User $other)
    {
        return Connection::where(function($query) use ($other) {
            $query->where('sender_id', $this->id)
                  ->where('receiver_id', $other->id);
        })->orWhere(function($query) use ($other) {
            $query->where('sender_id', $other->id)
                  ->where('receiver_id', $this->id);
        })->first();
    }

    public function isConnectionWith(User $other)
    {
        $conn = $this->connectionWith($other);
        return $conn && $conn->status === 'accepted';
    }

    public function hasPendingRequestFrom(User $other)
    {
        $conn = Connection::where('sender_id', $other->id)
            ->where('receiver_id', $this->id)
            ->where('status', 'pending')
            ->first();
        return (bool)$conn;
    }

    public function hasPendingRequestTo(User $other)
    {
        $conn = Connection::where('sender_id', $this->id)
            ->where('receiver_id', $other->id)
            ->where('status', 'pending')
            ->first();
        return (bool)$conn;
    }

    public function acceptedConnections()
    {
        $sent = Connection::where('sender_id', $this->id)
            ->where('status', 'accepted')
            ->pluck('receiver_id')
            ->toArray();

        $received = Connection::where('receiver_id', $this->id)
            ->where('status', 'accepted')
            ->pluck('sender_id')
            ->toArray();

        $userIds = array_merge($sent, $received);

        return User::whereIn('id', $userIds)->get();
    }

    protected static function booted()
    {
        static::created(function ($user) {
            $user->portfolio()->create([
                'title'        => $user->name . '\'s Portfolio',
                'description'  => 'Welcome to my portfolio.',
                'theme'        => 'premium',
            ]);
        });
    }
}

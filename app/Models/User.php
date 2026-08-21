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
        'user_type',
        'admin_role',
        'account_status',
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

    public function isAdmin(): bool
    {
        return $this->role === 'admin' || in_array($this->admin_role, ['super_admin', 'admin', 'moderator', 'support']);
    }

    public function isSuperAdmin(): bool
    {
        return $this->admin_role === 'super_admin' || ($this->role === 'admin' && empty($this->admin_role));
    }

    public function isModerator(): bool
    {
        return $this->isSuperAdmin() || in_array($this->admin_role, ['admin', 'moderator']);
    }

    public function isSuspended(): bool
    {
        return $this->account_status === 'suspended';
    }

    public function isCompanyRep(): bool
    {
        return $this->user_type === 'company_rep' || $this->companyMemberships()->exists();
    }

    public function companyMemberships()
    {
        return $this->hasMany(CompanyMember::class);
    }

    public function companies()
    {
        return $this->belongsToMany(Company::class, 'company_members')->withPivot('role', 'title')->withTimestamps();
    }

    public function jobApplications()
    {
        return $this->hasMany(JobApplication::class);
    }

    public function savedOpportunities()
    {
        return $this->hasMany(SavedOpportunity::class);
    }

    public function professionalPreference()
    {
        return $this->hasOne(ProfessionalPreference::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function systemNotifications()
    {
        return $this->hasMany(SystemNotification::class);
    }

    public function mockInterviews()
    {
        return $this->hasMany(MockInterview::class);
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

    public function conversations()
    {
        return Conversation::where('user_one_id', $this->id)
            ->orWhere('user_two_id', $this->id);
    }

    public function unreadDirectMessagesCount()
    {
        return DirectMessage::where('receiver_id', $this->id)
            ->where('is_read', false)
            ->count();
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

    public function suggestedConnections(int $limit = 5)
    {
        $connectedIds = Connection::where('sender_id', $this->id)
            ->orWhere('receiver_id', $this->id)
            ->pluck('sender_id')
            ->merge(
                Connection::where('sender_id', $this->id)
                    ->orWhere('receiver_id', $this->id)
                    ->pluck('receiver_id')
            )
            ->push($this->id)
            ->unique()
            ->toArray();

        return User::whereNotIn('id', $connectedIds)
            ->where('role', '!=', 'admin')
            ->where('account_status', '!=', 'suspended')
            ->whereHas('portfolio', function($q) {
                $q->where('is_active', true);
            })
            ->with(['portfolio', 'professionalPreference'])
            ->inRandomOrder()
            ->take($limit)
            ->get();
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

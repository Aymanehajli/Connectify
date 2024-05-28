<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Block;


class User extends Authenticatable 
{
    use HasFactory, Notifiable;
    use SoftDeletes;
    use Notifiable;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'image',
        'role',
        'blocked',
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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function publications()
    {
        return $this->hasMany(Publication::class);
    }

    
    public function notifications()
{
    return $this->hasMany(Notification::class);
}

    public function is_friend()
    {
        return (Friend::where(["user_id" => $this->id])->orWhere("friend_id", $this->id)->first()->status ?? "");
    }

    public function unreadNotifications()
    {
        return $this->notifications()->unread();
    }

    public function toggleActiveStatus()
    {
        $this->active_status = !$this->active_status; // Toggle active status
        $this->save(); // Save the updated status
    }

    public function sentFriendRequests()
    {
        return $this->hasMany(Friend::class, 'sender_id');
    }

    public function receivedFriendRequests()
    {
        return $this->hasMany(Friend::class, 'receiver_id');
    }

//block funtion
public function blockers()
{
    return $this->hasMany(Block::class, 'blocked_id');
}

public function isBlockedBy($user)
{
    $user1 = User::find($user->id);
    return $user1->blockers()->where('blocker_id', $this->id)->exists();
}



public function blockedUsers()
{
    return $this->belongsToMany(User::class, 'user_blocks', 'blocked_id', 'blocker_id');
}

public function hasBlocked($user)
{
    $user1 = User::find($user->id);
    return $this->blockedUsers()->where('blocker_id', $user1->id)->exists();
}
}

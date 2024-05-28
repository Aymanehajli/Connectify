<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Publication extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'titre',
        'body',
        'user_id',
        'image',
        'video',
        'likes',
        'comments',
        'shared_by',
        
    ];
    public function isLikedByUser($userId)
    {
        return $this->likes()->where('user_id', $userId)->exists();
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function sharedByUser()
    {
        return $this->belongsTo(User::class, 'shared_by');
    }
    
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Friend extends Model
{
    use HasFactory;
    
    protected $fillable = [
        
        'friend_id',
        'user_id',
        'accepted_at',
        'status',
        
        
    ];

    public function user() :BelongsTo{
        return $this->belongsTo(User::class);
    }
    public function friend(): BelongsTo
    {
        return $this->belongsTo(User::class, 'friend_id');
    }
}

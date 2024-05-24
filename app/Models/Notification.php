<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        
        'type',
        'user_id',
        'message',
        'url',
        'read_at',
        
    ];
    
    public function markAsReadForUser()
    {
        $userId = Auth::id();

        $this->where('user_id', $userId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }


    public function user() :BelongsTo{
        return $this->belongsTo(User::class);
    }
}

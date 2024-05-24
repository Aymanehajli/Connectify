<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        
        'publication_id',
        'user_id',
        'comment',
        
    ];


    public function user() :BelongsTo{
        return $this->belongsTo(User::class,'user_id');
    }
    
    public function publication() :BelongsTo{
        return $this->belongsTo(Publication::class);
    }

    
}

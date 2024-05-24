<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChMessage extends Model
{
    use HasFactory;
    protected $fillable = [
        'from_id',
        'to_id',
        'audio_path',
        'body',
        'attachment',
        'seen',

        
    ];


    public function fromUser()
{
    return $this->belongsTo(User::class, 'from_id');
}

public function toUser()
{
    return $this->belongsTo(User::class, 'to_id');
}
}

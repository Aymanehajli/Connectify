<?php

namespace App\Models;

use App\Models\User as ModelsUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;

class Block extends Model
{
    use HasFactory;

    protected $table = 'user_blocks';
    protected $fillable = ['blocker_id', 'blocked_id'];

    public function blocker()
    {
        return $this->belongsTo(ModelsUser::class, 'blocker_id');
    }

    public function blocked()
    {
        return $this->belongsTo(User::class, 'blocked_id');
    }
    
    
}

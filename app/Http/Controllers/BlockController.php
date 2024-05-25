<?php

namespace App\Http\Controllers;

use App\Models\Block;
use App\Models\Friend;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BlockController extends Controller
{
    public function block($blockedId)
    {
        $blockerId = auth()->id();

        // Ensure users aren't blocking themselves
        if ($blockerId == $blockedId) {
            return back()->with('error', 'You cannot block yourself.');
        }

        Block::create([
            'blocked_id' => $blockerId,
            'blocker_id' => $blockedId,
        ]);
        


        Friend::where(function ($query) use ($blockedId,$blockerId) {
            $query->where('user_id', $blockerId)->where('friend_id', $blockedId)
                  ->orWhere('user_id', $blockedId)->where('friend_id', $blockerId);
        })->delete();

        
        return redirect()->back()->with('status', 'User blocked successfully!');}

        public function unblock($blockedId)
        {
            $blockerId = auth()->id();
    
            Block::where('blocked_id', $blockerId)->where('blocker_id', $blockedId)->delete();
    
            return back()->with('status', 'User unblocked successfully.');
        }
}

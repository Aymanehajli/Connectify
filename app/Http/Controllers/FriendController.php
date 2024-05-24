<?php

namespace App\Http\Controllers;

use App\Models\Friend;
use App\Models\FriendRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FriendController extends Controller
{
    public function index()
    {
        $user = Auth::user();
       
        $friends = $this->getFriends($user->id);
       

        return view('friends.index', compact('friends'));
    }
    
    private function getFriends($userId)
    {
        
        $friendRequests = Friend::where(function ($query) use ($userId) {
            $query->where('user_id', $userId)
                  ->orWhere('friend_id', $userId);
        })->where('status', 'accepted')->get();

        
        $friendIds = $friendRequests->map(function ($friendRequest) use ($userId) {
            return $friendRequest->user_id == $userId ? $friendRequest->friend_id : $friendRequest->user_id;
        });

        return User::whereIn('id', $friendIds)->get();
    }

    public function search(Request $request)
    {
        $searchTerm = $request->input('search');
        $friends = User::where('name', 'like', "%$searchTerm%")->get();
        return response()->json(['friends' => $friends]);
    }


    
}

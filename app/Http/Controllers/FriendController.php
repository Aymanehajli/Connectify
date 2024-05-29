<?php

namespace App\Http\Controllers;

use App\Models\Friend;
use App\Models\FriendRequest;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FriendController extends Controller
{
    public function index()
    {
        $user = Auth::user();
       
        $friends = $this->getFriends($user->id);

        $friendRequests = Friend::where('friend_id', Auth::id())
                                        ->where('status', 'pending')
                                        ->with('sender')
                                        ->get();
       

        return view('friends.index', compact('friends','friendRequests','user'));
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

    public function accept($id)
    {
        $friendRequest = Friend::find($id);
        DB::beginTransaction();
        try {

            $user = User::findOrFail($id);
            $friendRequest->update(['status' => 'accepted']);
            $friendRequest->save();

            Notification::create([
                "type" => "friend_accepted",
                "user_id" => $user->id,
                "message" => auth()->user()->name . " accepted your friend request",
                "url" => "#",
            ]);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
        
        return response()->json(['success' => 'Friend request accepted.'], 200);
    }

    public function acceptfriend($id)
    {
        $user = User::where("id", $id)->first();

        DB::beginTransaction();
        try {
            $friendRequest = Friend::where([
                "user_id" => $id,
                "friend_id" => auth()->id(),
            ])->first();
            if (!$friendRequest) {
                abort(404, 'Friend request not found.');
            }
            $friendRequest->status = "accepted";
            $friendRequest->save();

            Notification::create([
                "type" => "friend_accepted",
                "user_id" => $user->id,
                "message" => auth()->user()->name . " accepted your friend request",
                "url" => "#",
            ]);

            
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
        return response()->json(['message' => 'Friend request accepted successfully']);
    }

    public function refuse($id)
    {
        $friendRequest = Friend::find($id);

        

        if ($friendRequest->friend_id != Auth::id()) {
            return response()->json(['error' => 'Unauthorized action.'], 403);
        }

        $friendRequest->delete();


        return response()->json(['success' => 'Friend request refused.'], 200);
    }

    
}

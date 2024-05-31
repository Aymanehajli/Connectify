<?php

namespace App\Http\Controllers;

use App\Models\Friend;
use App\Models\FriendRequest;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class FriendRequestController extends Controller
{

    public function friendrequestlist()
    {
        $friendRequests = Friend::where('friend_id', Auth::id())
                                        ->where('status', 'pending')
                                        ->with('sender')
                                        ->get();
       
        return view('friends.request', compact('friendRequests'));
    }

    
    public function send($id)
    {
        $receiver = User::findOrFail($id);
    $sender_id = Auth::id();
   
    // Check if request already exists from either side
    if (Friend::where([
        ['user_id', $sender_id],
        ['friend_id', $receiver->id],
    ])->orWhere([
        ['user_id', $receiver->id],
        ['friend_id', $sender_id],
    ])->exists()) {
        return response()->json(['error' => 'Friend request already sent or received.'], 400);
    } else {
        // Check if sender has already sent a request to receiver
        if (Friend::where('user_id', $receiver->id)->where('friend_id', $sender_id)->exists()) {
            return response()->json(['error' => 'Friend request already sent.'], 400);
        }
        
        Friend::create([
            "user_id" => auth()->id(),
            "friend_id" => $receiver->id,
        ]);
        Notification::create([
            "type" => "friend_request",
            "user_id" => $receiver->id,
            "message" => auth()->user()->name . " sent you a friend request",
            "url" => "#",
        ]);
        
        return response()->json(['message' => 'Friend request sent to ' . $receiver->name]);
    }

    }

  
    
    public function accept($id)
    {
        $friendRequest = Friend::where('user_id', $id)
                            ->where('friend_id', Auth::id())
                            ->first();


                            if (!$friendRequest) {
                                return response()->json(['error' => 'Invalid friend request.'], 403);
                            }
        


           
            $friendRequest->update(['status' => 'accepted']);
            $friendRequest->save();

            Notification::create([
                "type" => "friend_accepted",
                "user_id" => $friendRequest->user_id,
                "message" => auth()->user()->name . " accepted your friend request",
                "url" => "#",
            ]);

        
        return response()->json(['success' => 'Friend request accepted.'], 200);
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

    public function Removerequest($id)
    {
        $user = User::findOrFail($id);
        DB::beginTransaction();

        try {Friend::where([
            "user_id" => auth()->id(),
            "friend_id" => $user->id,
        ])->first()->delete();
        Notification::create([
            "type" => "friend_request",
            "user_id" => $user->id,
            "message" => auth()->user()->name . " canceled friend request",
            "url" => "#",
        ]);
        DB::commit();
        return response()->json(['message' => 'Friend request canceled from ' . $user->name]);

        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message' => 'Error canceling friend request'], 500);

        }
        
    }
 

    public function checkFriendship($userId)
    {
        $authId = Auth::id();

        $friendRequestSent = Friend::where('user_id', $authId)->where('friend_id', $userId)->exists();
        $friendRequestReceived = Friend::where('user_id', $userId)->where('friend_id', $authId)->exists();
        $friends = Friend::where(function ($query) use ($authId, $userId) {
            $query->where('user_id', $authId)->where('friend_id', $userId)->where('status', 'accepted');
        })->orWhere(function ($query) use ($authId, $userId) {
            $query->where('user_id', $userId)->where('friend_id', $authId)->where('status', 'accepted');
        })->exists();

        return response()->json([
            'friendRequestSent' => $friendRequestSent,
            'friendRequestReceived' => $friendRequestReceived,
            'friends' => $friends,
        ]);
    }
    


     public function checkFriendRequest($profileUserId)
     {
        $currentUser = auth()->user();
         $pendingRequest = Friend::where('user_id', $currentUser->id)
             ->where('friend_id', $profileUserId)
             ->where('status', 'pending')
             ->exists();
 
         return response()->json(['pendingRequest' => $pendingRequest]);
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




    

    

     


}

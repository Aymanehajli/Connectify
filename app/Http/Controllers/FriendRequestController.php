<?php

namespace App\Http\Controllers;

use App\Models\Friend;
use App\Models\FriendRequest;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class FriendRequestController extends Controller
{
    public function addFriend($id)
    {
        $user =  User::findOrFail($id);


        DB::beginTransaction();
        try {
            Friend::firstOrCreate([
                "user_id" => auth()->id(),
                "friend_id" => $user->id,
            ]);
            Notification::create([
                "type" => "friend_request",
                "user_id" => $user->id,
                "message" => auth()->user()->name . " send you friend request",
                "url" => "#",
            ]);
            DB::commit();
            return response()->json(['message' => 'Friend request sent to ' . $user->name]);


        } catch (\Throwable $th) {
            DB::rollBack();
            
            return response()->json(['message' => 'Error sending friend request'], 500);

        }
        
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
        $friendRequestSent = Friend::where('user_id', auth()->id())
            ->where('friend_id', $userId)
            ->exists();

        return response()->json(['friendRequestSent' => $friendRequestSent]);
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

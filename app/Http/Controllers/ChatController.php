<?php

namespace App\Http\Controllers;

use App\Models\ChMessage;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use SebastianBergmann\Diff\Chunk;

class ChatController extends Controller
{public function index()
    {
        $messages = ChMessage::with(['fromUser', 'toUser'])
        ->where(function ($query) {
            $query->where('from_id', auth()->id())
                ->orWhere('to_id', auth()->id());
        })
        ->orderBy('created_at', 'asc')
        ->get();
        

    // Fetch users to display their information in the chat
    $users = User::where('id', '<>', auth()->id())->get();

    return view('chat.inbox', compact('messages', 'users')); }

    
    public function sendMessage(Request $request)
    {
        // Validate request
        
        
        $message = new ChMessage();
        $message->from_id = $request->input('from_id');
        $message->to_id = $request->input('to_id');
        $message->body = $request->input('body');
        $message->save();


        $userProfileUrl = route('user.show', $message->from_id);
    
        Notification::create([
            "type" => "Message",
            "user_id" => $message->to_id ,
            "message" => auth()->user()->name . " send you message",
            "url" => $userProfileUrl,
        ]);
        return response()->json(['success' => true]);
}
public function markAsRead(Request $request) {
    $conversationId = $request->input('conversationId');
    $authUserId = auth()->id();

    ChMessage::where('from_id', $conversationId)
           ->where('to_id', $authUserId)
           ->where('seen', false)
           ->update(['seen' => true]);

    return response()->json(['success' => true]);
}

public function fetchUnseenMessages(Request $request) {
    $conversationId = $request->input('conversationId');
    $authUserId = auth()->id();

    $messages = ChMessage::where('from_id', $conversationId)
                         ->where('to_id', $authUserId)
                         ->where('seen', false)
                         ->orderBy('created_at', 'asc')
                         ->get();

    return response()->json([
        'success' => true,
        'messages' => $messages->map(function ($message) {
            return [
                'id' => $message->id,
                'body' => $message->body,
                'from_id' => $message->from_id,
                'to_id' => $message->to_id,
                'timestamp' => $message->created_at,
            ];
        }),
    ]);
}


public function fetchMessages(Request $request)
    {
        $conversationId = $request->input('conversationId');
        $lastTimestamp = $request->input('lastTimestamp');

        $query = ChMessage::where(function($query) use ($conversationId) {
            $query->where('from_id', Auth::id())
                  ->where('to_id', $conversationId);
        })->orWhere(function($query) use ($conversationId) {
            $query->where('from_id', $conversationId)
                  ->where('to_id', Auth::id());
        });

        if ($lastTimestamp) {
            $query->where('created_at', '>', $lastTimestamp);
        }

        $messages = $query->orderBy('created_at')->get();

        return response()->json([
            'success' => true,
            'messages' => $messages->map(function ($message) {
                return [
                    'id' => $message->id,
                    'body' => $message->body,
                    'from_id' => $message->from_user_id,
                    'to_id' => $message->to_user_id,
                    'timestamp' => $message->created_at,
                ];
            }),
        ]);
    }

    // Poll for new messages
    public function pollMessages(Request $request)
    {
        return $this->fetchMessages($request);
    }


public function searchUsers(Request $request) {
    $query = $request->input('query');
    $users = User::where('name', 'LIKE', "%{$query}%")->get();

    return response()->json(['success' => true, 'users' => $users]);
}

}



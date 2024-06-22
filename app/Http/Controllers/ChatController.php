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
        $request->validate([
            'from_id' => 'required|integer',
            'to_id' => 'required|integer',
            'body' => 'nullable|string',
            'attachment' => 'nullable|file|mimes:jpeg,png,jpg,gif,mp4,mov,avi,flv'
        ]);
        
        
        $message = new ChMessage();
        $message->from_id = $request->input('from_id');
        $message->to_id = $request->input('to_id');
        $message->body = $request->input('body');
        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('attachments', 'public');
            $message->attachment = $path;
        }
        $message->save();


        $userProfileUrl = route('user.show', $message->from_id);
    
        Notification::create([
            "type" => "Message",
            "user_id" => $message->to_id ,
            "message" => "<svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-envelope-fill' viewBox='0 0 16 16'>
            <path d='M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414zM0 4.697v7.104l5.803-3.558zM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586zm3.436-.586L16 11.801V4.697z'/>
          </svg>" . auth()->user()->name . " send you message",
          
            "url" => $userProfileUrl,
        ]);
        return response()->json(['success' => true]);
}





public function markAsRead(Request $request) {
    $conversationId = $request->input('conversationId');
    $authUserId = auth()->id();

    ChMessage::where('from_id', $conversationId)
           ->where('to_id', $authUserId)
           ->where('seen', "0")
           ->update(['seen' => "1"]);

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
                'attachment' => $message->attachment,
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
                    'attachment' => $message->attachment,
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



<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notification as NotificationsNotification;

class NotificationController extends Controller
{

    public function showNotifications(Request $request)
{
    if ($request->ajax()) {
        $userId = auth()->id();

        $notifications = Notification::where('user_id', $userId)
            ->latest()
            ->take(5)
            ->get();

        $unreadNotifications = Notification::where('user_id', $userId)
            ->whereNull('read_at')
            ->latest()
            ->get();

        $unreadNotifications->each(function ($notification) {
            $notification->markAsReadForUser();
        });
        return response()->json([
            'notifications' => $notifications->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'message' => $notification->message,
                    'url' => $notification->url,
                    'created_at' => $notification->created_at->toDateTimeString(), // Format created_at
                ];
            }),
            'unreadNotificationsCount' => $unreadNotifications->count(), // Count unread notifications
        ]);
    }

  }



    public function markAsRead(Request $request, $id)
    {
        $notification = Notification::findOrFail($id);
    
        if ($notification->user_id === auth()->id()) {
            $notification->update(['read_at' => now()]);
            return response()->json(['success' => true]);
        }
    
        return response()->json(['success' => false]);
    }
}

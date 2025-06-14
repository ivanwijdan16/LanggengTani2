<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        // Get all unread notifications
        $notifications = Notification::get();

        return view('notification.index', compact('notifications'));
    }

    public function getNotifications()
    {
        // Get all unread notifications
        $unreadNotifications = Notification::where('read', false)->get();

        return response()->json([
            'notifications' => $unreadNotifications,
            'unread_count' => $unreadNotifications->count()
        ]);
    }

    public function markAsRead($id)
    {
        // Find the notification by ID and mark it as read
        $notification = Notification::find($id);
        if ($notification) {
            $notification->read = true;
            $notification->save();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Notification marked as read'
        ]);
    }
}

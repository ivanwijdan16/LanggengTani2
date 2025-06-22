<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        // Get all unread notifications
        $notifications = Notification::with('stock.masterStock')->get();

        return view('notification.index', compact('notifications'));
    }

    public function getNotifications()
    {
        // Get all unread notifications
        $unreadNotifications = Notification::with('stock.masterStock')->where('read', false)->get();

        return response()->json([
            'notifications' => $unreadNotifications,
            'unread_count' => $unreadNotifications->count()
        ]);
    }

    public function markAsRead($id)
    {
        // Find the notification by ID and mark it as read
        $notification = Notification::with('stock.masterStock')->find($id);
        if ($notification) {
            $notification->read = true;
            $notification->save();

            // Generate the redirect URL based on the stock information
            $redirectUrl = $this->generateRedirectUrl($notification);

            return response()->json([
                'status' => 'success',
                'message' => 'Notification marked as read',
                'redirect_url' => $redirectUrl
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Notification not found'
        ], 404);
    }

    private function generateRedirectUrl($notification)
    {
        if (!$notification->stock || !$notification->stock->masterStock) {
            return route('stocks.index');
        }

        $stock = $notification->stock;
        $masterStock = $stock->masterStock;

        // Generate URL to the batches page for this specific stock
        return route('stocks.batches', [
            'master_id' => $masterStock->id,
            'size' => $stock->size
        ]);
    }
}

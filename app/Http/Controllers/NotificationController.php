<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Show user notifications
     */
    public function index()
    {
        $user = auth()->user();
        $notifications = $user->notifications()->latest()->paginate(20);
        
        return view('user.notifications', compact('notifications'));
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(Notification $notification)
    {
        // Ensure user can only mark their own notifications as read
        if ($notification->user_id !== auth()->id()) {
            abort(403);
        }

        NotificationService::markAsRead($notification);

        return back()->with('success', 'Notifikasi ditandai sebagai telah dibaca.');
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        $user = auth()->user();
        NotificationService::markAllAsRead($user);

        return back()->with('success', 'Semua notifikasi ditandai sebagai telah dibaca.');
    }

    /**
     * Delete notification
     */
    public function destroy(Notification $notification)
    {
        // Ensure user can only delete their own notifications
        if ($notification->user_id !== auth()->id()) {
            abort(403);
        }

        $notification->delete();

        return back()->with('success', 'Notifikasi berhasil dihapus.');
    }

    /**
     * Get unread notifications count (for AJAX)
     */
    public function unreadCount()
    {
        $count = auth()->user()->unreadNotificationsCount();
        
        return response()->json(['count' => $count]);
    }

    /**
     * Get latest notifications (for AJAX)
     */
    public function latest()
    {
        $notifications = auth()->user()->notifications()->latest()->take(5)->get();
        
        return response()->json($notifications);
    }
} 
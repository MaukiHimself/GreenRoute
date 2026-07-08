<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Backs the notification bell shared by the client and contractor portals.
 * All actions operate on the authenticated user's own notifications.
 */
class NotificationController extends Controller
{
    /**
     * Open a single notification: mark it read, then redirect to its target
     * URL (or back if it has none).
     */
    public function open(Request $request, string $id)
    {
        $user = Auth::user();
        $notification = $user->notifications()->findOrFail($id);

        if (is_null($notification->read_at)) {
            $notification->markAsRead();
        }

        $url = $notification->data['url'] ?? null;

        return $url ? redirect()->to($url) : redirect()->back();
    }

    /**
     * Mark every unread notification as read.
     */
    public function markAllRead(Request $request)
    {
        Auth::user()->unreadNotifications->markAsRead();

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'All notifications marked as read.');
    }
}

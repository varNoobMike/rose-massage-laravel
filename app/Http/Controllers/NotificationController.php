<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $notifications = $user->notifications()
            ->latest()
            ->paginate(10);

        return view($this->currentRoleView() . '.notifications.index', compact('notifications'));
    }

    public function markAsRead($id)
    {
        $user = Auth::user();

        $notification = $user->notifications()
            ->where('id', $id)
            ->firstOrFail();

        $notification->markAsRead();

        return back()->with(
            'success',
            'Notification marked as read.'
        );
    }

    public function markAllAsRead()
    {
        $user = Auth::user();

        $user->unreadNotifications()
            ->update([
                'read_at' => now()
            ]);

        return back()->with(
            'success',
            'All notifications marked as read.'
        );
    }
}

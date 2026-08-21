<?php

namespace App\Http\Controllers;

use App\Models\SystemNotification;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function index()
    {
        // Automatically mark all unread notifications as read on click
        SystemNotification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        $notifications = SystemNotification::where('user_id', Auth::id())
            ->with('sender')
            ->latest()
            ->paginate(15);

        \App\Services\SeoService::set([
            'title' => 'Notifications | MyResume.cloud',
            'robots' => 'noindex, nofollow'
        ]);

        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead(SystemNotification $notification)
    {
        $this->notificationService->markAsRead($notification, Auth::user());
        if ($notification->action_url) {
            return redirect($notification->action_url);
        }
        return back()->with('success', 'Notification marked as read.');
    }

    public function markAllRead()
    {
        $this->notificationService->markAllAsRead(Auth::user());
        return back()->with('success', 'All notifications marked as read.');
    }
}

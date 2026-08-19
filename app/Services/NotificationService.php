<?php

namespace App\Services;

use App\Models\SystemNotification;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Send in-app system notification to a recipient user.
     */
    public function notify(
        User $recipient,
        string $title,
        string $message,
        string $type = 'info',
        ?string $actionUrl = null,
        ?User $sender = null
    ): SystemNotification {
        $notification = SystemNotification::create([
            'user_id' => $recipient->id,
            'sender_id' => $sender?->id,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'action_url' => $actionUrl,
            'is_read' => false,
        ]);

        Log::info("Notification sent to User #{$recipient->id} ({$recipient->email}): {$title}");

        return $notification;
    }

    /**
     * Mark a notification as read.
     */
    public function markAsRead(SystemNotification $notification, User $user): bool
    {
        if ($notification->user_id !== $user->id) {
            return false;
        }

        return $notification->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }

    /**
     * Mark all notifications as read for a user.
     */
    public function markAllAsRead(User $user): int
    {
        return SystemNotification::where('user_id', $user->id)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
    }
}

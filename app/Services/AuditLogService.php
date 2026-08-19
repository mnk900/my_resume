<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AuditLogService
{
    /**
     * Log an administrative event.
     */
    public static function log(string $action, $target = null, array $details = []): AuditLog
    {
        $userId = Auth::id();
        $targetType = null;
        $targetId = null;

        if (is_object($target)) {
            $targetType = get_class($target);
            $targetId = $target->id ?? null;
        } elseif (is_array($target)) {
            $targetType = $target['type'] ?? null;
            $targetId = $target['id'] ?? null;
        }

        return AuditLog::create([
            'user_id' => $userId,
            'action' => $action,
            'target_type' => $targetType,
            'target_id' => $targetId,
            'details' => $details,
            'ip_address' => Request::ip(),
            'user_agent' => Request::header('User-Agent'),
        ]);
    }
}

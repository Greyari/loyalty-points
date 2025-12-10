<?php

namespace App\Helpers;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class LogHelper
{
    public static function log($module, $action, $id, array $before = null, array $after = null)
    {
        ActivityLog::create([
            'module' => $module,
            'action' => $action,
            'user_id' => Auth::id(),
            'target_id' => $id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'changes' => [
                'before' => $before,
                'after'  => $after
            ]
        ]);

    }
}

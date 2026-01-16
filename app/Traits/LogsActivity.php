<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

trait LogsActivity
{
    protected static function bootLogsActivity()
    {
        static::created(function ($model) {
            static::logActivity('created', $model);
        });

        static::updated(function ($model) {
            static::logActivity('updated', $model, $model->getOriginal(), $model->getAttributes());
        });

        static::deleted(function ($model) {
            static::logActivity('deleted', $model);
        });
    }

    protected static function logActivity(string $action, $model, array $oldValues = null, array $newValues = null)
    {
        try {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => $action,
                'model_type' => get_class($model),
                'model_id' => $model->id ?? null,
                'old_values' => $oldValues,
                'new_values' => $newValues,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        } catch (\Exception $e) {
            // Silently fail if activity logging fails to prevent breaking the main operation
            \Log::warning('Activity log failed: ' . $e->getMessage());
        }
    }
}


<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

trait LogsActivity
{
    protected static function bootLogsActivity()
    {
        static::created(function ($model) {
            self::logActivity('create', $model, null, $model->toArray());
        });

        static::updated(function ($model) {
            $oldValues = $model->getOriginal();
            $newValues = $model->getChanges();

            if (!empty($newValues)) {
                self::logActivity('update', $model, $oldValues, $newValues);
            }
        });

        static::deleted(function ($model) {
            $action = $model->isForceDeleting() ? 'force_delete' : 'delete';
            self::logActivity($action, $model, $model->toArray(), null);
        });

        if (method_exists(static::class, 'restored')) {
            static::restored(function ($model) {
                self::logActivity('restore', $model, null, $model->toArray());
            });
        }
    }

    protected static function logActivity(string $action, $model, ?array $oldValues = null, ?array $newValues = null)
    {
        if (!Auth::check()) {
            return;
        }

        // Clean sensitive data from logging
        $cleanOldValues = self::cleanSensitiveData($oldValues);
        $cleanNewValues = self::cleanSensitiveData($newValues);

        ActivityLog::create([
            'action' => $action,
            'model_type' => get_class($model),
            'model_id' => $model->id ?? 0,
            'old_values' => $cleanOldValues,
            'new_values' => $cleanNewValues,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'user_id' => Auth::id(),
        ]);
    }

    protected static function cleanSensitiveData(?array $data): ?array
    {
        if (!$data) {
            return $data;
        }

        $sensitiveFields = ['password', 'password_confirmation', 'token', 'api_token', 'remember_token'];

        foreach ($sensitiveFields as $field) {
            if (isset($data[$field])) {
                $data[$field] = '[HIDDEN]';
            }
        }

        return $data;
    }
}

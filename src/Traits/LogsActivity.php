<?php

namespace Kazitoha\ActivityLogger\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Kazitoha\ActivityLogger\Models\ActivityLog;

trait LogsActivity
{
    public static function bootLogsActivity(): void
    {
        static::created(function (Model $model) {
            self::logAction('created', $model);
        });

        // Use "updating" (pre-save) to capture old/new values accurately
        static::updating(function (Model $model) {
            self::logAction('updated', $model);
        });

        static::deleting(function (Model $model) {
            self::logAction('deleted', $model);
        });
    }

    protected static function logAction(string $action, Model $model): void
    {
        // Skip if model is not persisted for non-create actions
        if (!in_array($action, ['created']) && !$model->exists) {
            return;
        }

        $original   = $model->getOriginal();
        $attributes = $model->getAttributes();
        $changes    = $model->getDirty();

        $payload = [];

        if ($action === 'created') {
            $payload = ['attributes' => self::withoutHidden($model, $attributes)];
        } elseif ($action === 'updated') {
            $old = $new = [];
            foreach ($changes as $key => $newValue) {
                $old[$key] = $original[$key] ?? null;
                $new[$key] = $newValue;
            }
            $payload = ['attributes' => $new, 'old' => $old];
        } else { // deleted
            $payload = ['old' => self::withoutHidden($model, $original)];
        }

        $payload = self::redact($payload);

        ActivityLog::create([
            'user_id'       => Auth::id(),
            'action'        => $action,
            'description'   => $payload,
            'loggable_type' => $model->getMorphClass(),
            'loggable_id'   => $model->getKey(),
            'ip_address'    => request()->ip(),
        ]);
    }

    protected static function withoutHidden(Model $model, array $data): array
    {
        $hidden = method_exists($model, 'getHidden') ? $model->getHidden() : [];
        foreach ($hidden as $key) {
            unset($data[$key]);
        }
        return $data;
    }

    /** Redact sensitive keys recursively. */
    protected static function redact(array $data): array
    {
        $keys = array_map('strtolower', config('activity-logger.redact_keys', []));

        $walker = function (&$value, $key) use ($keys, &$walker) {
            if (is_array($value)) {
                array_walk($value, $walker);
            } else {
                if (in_array(strtolower((string)$key), $keys, true)) {
                    $value = '[REDACTED]';
                }
            }
        };

        array_walk($data, $walker);
        return $data;
    }
}

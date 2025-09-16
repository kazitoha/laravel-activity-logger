<?php

namespace Kazitoha\ActivityLogger\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Kazitoha\ActivityLogger\Models\ActivityLog;

trait LogsActivity
{
    public static function bootLogsActivity(): void
    {
        static::created(fn ($model) => self::logAction('created', $model));
        static::updating(fn ($model) => self::logAction('updated', $model));
        static::deleting(fn ($model) => self::logAction('deleted', $model));
    }

    protected static function logAction(string $action, Model $model): void
    {
        $original = $model->getOriginal();
        $changes  = $model->getDirty();

        $old = $new = [];

        foreach ($changes as $key => $newValue) {
            $old[$key] = $original[$key] ?? null;
            $new[$key] = $newValue;
        }

        $payload = ($action === 'deleted')
            ? ['old' => $original]
            : ['attributes' => $new, 'old' => $old];

        $payload = self::redact($payload);

        ActivityLog::create([
            'user_id'       => Auth::id(),
            'action'        => $action,
            'description'   => json_encode($payload),
            'loggable_type' => $model->getMorphClass(),
            'loggable_id'   => $model->getKey(),
            'ip_address'    => Request::ip(),
        ]);
    }

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

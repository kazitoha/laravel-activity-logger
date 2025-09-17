<?php

namespace Kazitoha\ActivityLogger\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $guarded = [];

    protected $casts = [
        'description' => 'array',
    ];

    public function getTable()
    {
        // Allow table name to be configured
        return config('activity-logger.table', 'activity_logs');
    }
}

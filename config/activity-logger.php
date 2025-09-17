<?php

return [
    // Database table for activity logs
    'table' => 'activity_logs',

    // User model class (not strictly needed but here for future features)
    'user_model' => env('ACTIVITY_LOGGER_USER_MODEL', 'App\\Models\\User'),

    // Keys to mask in payloads
    'redact_keys' => [
        'password', 'password_confirmation', 'current_password',
        'token', 'access_token', 'refresh_token', 'api_key',
        '_token', 'authorization', 'secret', 'remember', 'remember_token',
    ],
];

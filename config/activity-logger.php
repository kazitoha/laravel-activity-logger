<?php

return [
    // Table name for logs
    'table' => env('ACTIVITY_LOGGER_TABLE', 'activity_logs'),

    // Redacted keys when logging payload arrays
    'redact_keys' => [
        'password', 'password_confirmation', 'current_password',
        'token', 'access_token', 'refresh_token', '_token',
        'authorization', 'api_key', 'secret', 'remember', 'remember_token',
    ],
];

<?php
return [
    // Application configuration.
    'settings' => [
        // Monolog settings.
        'logger' => [
            'name' => 'slim-app',
            'path' => 'log/app.log',
            'level' => \Monolog\Logger::DEBUG,
        ],

        // Set default timezone, eg "Europe/London", "Asia/Kuala_Lumpur", "America/New_York", etc.
        // Get full list of timezones at: http://php.net/manual/en/timezones.php
        'timezone' => "Asia/Jakarta",

        // Set multiple language accessibility.
        // Get full list of languages at: http://www.w3schools.com/tags/ref_language_codes.asp
        'languages' => [
            'en' => 'English',
        ],
        'row_per_page' => 10,

        // To see the whole error logging text.
        'display_error_details' => true,
        'log_errors' => true,
        'log_error_details' => true
    ],
];

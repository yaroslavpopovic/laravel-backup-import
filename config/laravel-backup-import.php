<?php
return [
    'source' =>  [
        'disk' => env('LARAVEL_DB_IMPORT_SOURCE_DISK', 'local'),
        'path' => env('LARAVEL_DB_IMPORT_SOURCE_PATH', 'laravel_db_import/source'),
    ],
    'destination' => [
        'disk' => env('LARAVEL_DB_IMPORT_DESTINATION_DISK', 'local'),
        'path' => env('LARAVEL_DB_IMPORT_DESTINATION_PATH', 'laravel_db_import/destination'),
        'file_name' => env('LARAVEL_DB_IMPORT_FILE_NAME', \Illuminate\Support\Str::snake(\Illuminate\Support\Facades\Config::get('app.name'))),
        'db_dumps_path' => env('LARAVEL_DB_IMPORT_DESTINATION_PATH', 'laravel_db_import/destination').'/'.env('LARAVEL_DB_IMPORT_FILE_NAME', \Illuminate\Support\Str::snake(\Illuminate\Support\Facades\Config::get('app.name'))).'/db-dumps'
    ],
    'notifications' => [
        'mail' => [
            'to' => [env('LARAVEL_DB_IMPORT_NOTIFICATION_EMAIL_TO', 'hello@example.com')],
            'from' => [
                'address' => env('MAIL_FROM_ADDRESS', 'hello@example.com'),
                'name' => \Illuminate\Support\Facades\Config::get('app.name'),
            ],
        ],
    ]
];

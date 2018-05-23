<?php
$dotenv = new Dotenv\Dotenv(__DIR__.'\\..\\..\\');
$dotenv->load();
$dotenv->required(['DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASSWORD','GOOGLE_DRIVE_CLIENT_ID','GOOGLE_DRIVE_ACCESS_TOKEN','GOOGLE_DRIVE_REFRESH_TOKEN']);
/**
 * Main Configuration
 */
return [
    'environment' => getenv('ENVIRONMENT'),
    'database' => [
        'driver' => 'mysql',
        'host' => getenv('DB_HOST'),
        'database' => getenv('DB_NAME'),
        'username' => getenv('DB_USER'),
        'password' => getenv('DB_PASSWORD')
    ],
    'google_drive' => [
        'client_id' => getenv('GOOGLE_DRIVE_CLIENT_ID'),
        'access_token' => getenv('GOOGLE_DRIVE_ACCESS_TOKEN'),
        'refresh_token' => getenv('GOOGLE_DRIVE_REFRESH_TOKEN')
    ]
];
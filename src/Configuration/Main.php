<?php
$rootDir = DIRECTORY_SEPARATOR . '..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR;
$dotenv = new Dotenv\Dotenv(__DIR__.$rootDir);
$dotenv->load();
$dotenv->required(['DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASSWORD','GOOGLE_DRIVE_CLIENT_ID','GOOGLE_DRIVE_ACCESS_TOKEN','GOOGLE_DRIVE_REFRESH_TOKEN']);
/**
 * Main Configuration
 */
return [
    'app_key' => getenv('APP_KEY'),
    'environment' => getenv('ENVIRONMENT'),
    'main_log' => (__DIR__.$rootDir) . 'log/main.log',
    'database' => [
        'driver' => 'mysql',
        'host' => getenv('DB_HOST'),
        'database' => getenv('DB_NAME'),
        'username' => getenv('DB_USER'),
        'password' => getenv('DB_PASSWORD'),
        'charset'   => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci'
    ],
    'google_drive' => [
        'client_id' => getenv('GOOGLE_DRIVE_CLIENT_ID'),
        'client_secret' => getenv('GOOGLE_DRIVE_CLIENT_SECRET'),
        'access_token' => getenv('GOOGLE_DRIVE_ACCESS_TOKEN'),
        'refresh_token' => getenv('GOOGLE_DRIVE_REFRESH_TOKEN')
    ],
    'stackexchange' => [
        'client_id' => getenv('SE_CLIENT_ID'),
        'client_secret' => getenv('SE_CLIENT_SECRET'),
        'key' => getenv('SE_KEY'),
        'redirect_uri' => getenv('SE_REDIRECT_URI')
    ],
    'email' => [
        'aws_id' => getenv('AWS_ID'),
        'aws_key' => getenv('AWS_KEY'),
        'aws_region' => getenv('AWS_REGION'),
        'dev_email' => getenv('DEV_EMAIL')
    ]
];
<?php
declare(strict_types=1);

return [
    'app' => [
        'name' => env('APP_NAME', 'Forms'),
    ],

    'session' => [
        'name' => env('SESSION_NAME', 'forms_session'),
        'cookie_secure' => env('SESSION_COOKIE_SECURE', '0') === '1', // set true if using HTTPS
        'cookie_httponly' => true,
        'cookie_samesite' => env('SESSION_COOKIE_SAMESITE', 'Lax'),
    ],

    'db' => [
        // Supported: sqlite, mysql
        'driver' => env('DB_DRIVER', 'sqlite'),

        'sqlite' => [
            'path' => env('DB_SQLITE_PATH', __DIR__ . '/../data/app.sqlite'),
        ],

        'mysql' => [
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => (int)(env('DB_PORT', '3306') ?? '3306'),
            'database' => env('DB_DATABASE', 'forms'),
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => env('DB_CHARSET', 'utf8mb4'),
        ],
    ],

    'captcha' => [
        // Supported: yandex, recaptcha, none
        'provider' => env('CAPTCHA_PROVIDER', 'yandex'),

        // Yandex SmartCaptcha
        'yandex' => [
            'client_key' => env('YANDEX_CLIENT_KEY', ''),
            'server_key' => env('YANDEX_SERVER_KEY', ''),
            'endpoint' => 'https://smartcaptcha.yandexcloud.net/validate',
        ],

        // Google reCAPTCHA v2 ("I'm not a robot")
        'recaptcha' => [
            'site_key' => env('RECAPTCHA_SITE_KEY', ''),
            'secret_key' => env('RECAPTCHA_SECRET_KEY', ''),
            'endpoint' => 'https://www.google.com/recaptcha/api/siteverify',
        ],
    ],
];


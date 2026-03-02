<?php
declare(strict_types=1);

return [
    'app' => [
        'name' => 'Forms',
    ],

    'session' => [
        'name' => 'forms_session',
        'cookie_secure' => false, // set true if using HTTPS
        'cookie_httponly' => true,
        'cookie_samesite' => 'Lax',
    ],

    'db' => [
        // Supported: sqlite, mysql
        'driver' => 'sqlite',

        'sqlite' => [
            'path' => __DIR__ . '/../data/app.sqlite',
        ],

        'mysql' => [
            'host' => '127.0.0.1',
            'port' => 3306,
            'database' => 'forms',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8mb4',
        ],
    ],

    'captcha' => [
        // Supported: yandex, recaptcha, none
        'provider' => 'yandex',

        // Yandex SmartCaptcha
        'yandex' => [
            'client_key' => 'PUT_YANDEX_CLIENT_KEY_HERE',
            'server_key' => 'PUT_YANDEX_SERVER_KEY_HERE',
            'endpoint' => 'https://smartcaptcha.yandexcloud.net/validate',
        ],

        // Google reCAPTCHA v2 ("I'm not a robot")
        'recaptcha' => [
            'site_key' => 'PUT_RECAPTCHA_SITE_KEY_HERE',
            'secret_key' => 'PUT_RECAPTCHA_SECRET_KEY_HERE',
            'endpoint' => 'https://www.google.com/recaptcha/api/siteverify',
        ],
    ],
];


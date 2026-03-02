<?php
declare(strict_types=1);

function session_bootstrap(array $sessionConfig): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }

    $name = (string)($sessionConfig['name'] ?? 'PHPSESSID');
    if ($name !== '') {
        session_name($name);
    }

    $secure = (bool)($sessionConfig['cookie_secure'] ?? false);
    $httponly = (bool)($sessionConfig['cookie_httponly'] ?? true);
    $samesite = (string)($sessionConfig['cookie_samesite'] ?? 'Lax');

    $params = session_get_cookie_params();
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => $params['path'] ?? '/',
        'domain' => $params['domain'] ?? '',
        'secure' => $secure,
        'httponly' => $httponly,
        'samesite' => $samesite,
    ]);

    session_start();
}


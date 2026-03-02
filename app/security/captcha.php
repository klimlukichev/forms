<?php
declare(strict_types=1);

function captcha_provider(array $config): string
{
    $provider = (string)($config['captcha']['provider'] ?? 'none');
    $provider = trim($provider);
    $provider = function_exists('mb_strtolower') ? mb_strtolower($provider) : strtolower($provider);
    return in_array($provider, ['yandex', 'recaptcha', 'none'], true) ? $provider : 'none';
}

/**
 * @return array{ok:bool, error:?string}
 */
function captcha_verify(array $config, string $ip, array $post): array
{
    $provider = captcha_provider($config);
    if ($provider === 'none') {
        return ['ok' => true, 'error' => null];
    }
    if ($provider === 'yandex') {
        $token = $post['smart-token'] ?? '';
        if (!is_string($token) || trim($token) === '') {
            return ['ok' => false, 'error' => 'Подтвердите, что вы не робот (SmartCaptcha).'];
        }
        return captcha_verify_yandex($config, $ip, $token);
    }

    // recaptcha
    $token = $post['g-recaptcha-response'] ?? '';
    if (!is_string($token) || trim($token) === '') {
        return ['ok' => false, 'error' => 'Подтвердите, что вы не робот (reCAPTCHA).'];
    }
    return captcha_verify_recaptcha($config, $ip, $token);
}

/**
 * @return array{ok:bool, error:?string}
 */
function captcha_verify_yandex(array $config, string $ip, string $token): array
{
    $y = $config['captcha']['yandex'] ?? [];
    $secret = (string)($y['server_key'] ?? '');
    $endpoint = (string)($y['endpoint'] ?? 'https://smartcaptcha.yandexcloud.net/validate');

    if ($secret === '' || str_starts_with($secret, 'PUT_')) {
        return ['ok' => false, 'error' => 'SmartCaptcha не настроена: заполните server_key в app/config.php.'];
    }

    $payload = http_build_query([
        'secret' => $secret,
        'token' => $token,
        'ip' => $ip,
    ]);

    $resp = http_post_form($endpoint, $payload, 2);
    if ($resp['http_code'] !== 200) {
        return ['ok' => false, 'error' => 'Ошибка проверки SmartCaptcha. Повторите попытку.'];
    }

    $data = json_decode($resp['body'], true);
    if (!is_array($data)) {
        return ['ok' => false, 'error' => 'Ошибка проверки SmartCaptcha.'];
    }

    return (($data['status'] ?? '') === 'ok')
        ? ['ok' => true, 'error' => null]
        : ['ok' => false, 'error' => 'Проверка SmartCaptcha не пройдена.'];
}

/**
 * @return array{ok:bool, error:?string}
 */
function captcha_verify_recaptcha(array $config, string $ip, string $token): array
{
    $r = $config['captcha']['recaptcha'] ?? [];
    $secret = (string)($r['secret_key'] ?? '');
    $endpoint = (string)($r['endpoint'] ?? 'https://www.google.com/recaptcha/api/siteverify');

    if ($secret === '' || str_starts_with($secret, 'PUT_')) {
        return ['ok' => false, 'error' => 'reCAPTCHA не настроена: заполните secret_key в app/config.php.'];
    }

    $payload = http_build_query([
        'secret' => $secret,
        'response' => $token,
        'remoteip' => $ip,
    ]);

    $resp = http_post_form($endpoint, $payload, 3);
    if ($resp['http_code'] !== 200) {
        return ['ok' => false, 'error' => 'Ошибка проверки reCAPTCHA. Повторите попытку.'];
    }

    $data = json_decode($resp['body'], true);
    if (!is_array($data)) {
        return ['ok' => false, 'error' => 'Ошибка проверки reCAPTCHA.'];
    }

    return !empty($data['success'])
        ? ['ok' => true, 'error' => null]
        : ['ok' => false, 'error' => 'Проверка reCAPTCHA не пройдена.'];
}

/**
 * @return array{http_code:int, body:string}
 */
function http_post_form(string $url, string $payload, int $timeoutSeconds): array
{
    if (function_exists('curl_init')) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeoutSeconds);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/x-www-form-urlencoded',
        ]);

        $body = (string)curl_exec($ch);
        $http = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return ['http_code' => $http, 'body' => $body];
    }

    $context = stream_context_create([
        'http' => [
            'method' => 'POST',
            'header' => "Content-Type: application/x-www-form-urlencoded\r\n" .
                "Content-Length: " . strlen($payload) . "\r\n",
            'content' => $payload,
            'timeout' => $timeoutSeconds,
        ],
    ]);

    $body = @file_get_contents($url, false, $context);
    $body = is_string($body) ? $body : '';
    $http = 0;
    if (isset($http_response_header) && is_array($http_response_header)) {
        foreach ($http_response_header as $line) {
            if (preg_match('#^HTTP/\S+\s+(\d{3})#', $line, $m)) {
                $http = (int)$m[1];
                break;
            }
        }
    }
    return ['http_code' => $http, 'body' => $body];
}


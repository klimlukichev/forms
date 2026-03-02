<?php
declare(strict_types=1);

if (!function_exists('str_contains')) {
    function str_contains(string $haystack, string $needle): bool
    {
        return $needle === '' || strpos($haystack, $needle) !== false;
    }
}

if (!function_exists('str_starts_with')) {
    function str_starts_with(string $haystack, string $needle): bool
    {
        return $needle === '' || strncmp($haystack, $needle, strlen($needle)) === 0;
    }
}

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function redirect(string $to): void
{
    header('Location: ' . $to);
    exit;
}

function request_method(): string
{
    return strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
}

function is_post(): bool
{
    return request_method() === 'POST';
}

function now_iso(): string
{
    return (new DateTimeImmutable('now'))->format(DateTimeInterface::ATOM);
}

function normalize_email(string $email): string
{
    $email = trim($email);
    return function_exists('mb_strtolower') ? mb_strtolower($email) : strtolower($email);
}

function normalize_login(string $login): string
{
    $login = trim($login);
    $login = preg_replace('/\s+/u', ' ', $login) ?? $login;
    return function_exists('mb_strtolower') ? mb_strtolower($login) : strtolower($login);
}

function normalize_phone(string $phone): string
{
    $digits = preg_replace('/\D+/', '', $phone) ?? '';
    return $digits;
}

function client_ip(): string
{
    // Basic IP extraction. If behind a proxy/load balancer, configure it properly.
    $ip = $_SERVER['REMOTE_ADDR'] ?? '';
    return is_string($ip) ? $ip : '';
}


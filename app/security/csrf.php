<?php
declare(strict_types=1);

function csrf_token(): string
{
    if (!isset($_SESSION['_csrf']) || !is_string($_SESSION['_csrf']) || $_SESSION['_csrf'] === '') {
        $_SESSION['_csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['_csrf'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="_csrf" value="' . e(csrf_token()) . '">';
}

function csrf_verify_or_400(): void
{
    $sent = $_POST['_csrf'] ?? '';
    if (!is_string($sent) || $sent === '' || !hash_equals(csrf_token(), $sent)) {
        http_response_code(400);
        echo 'Bad Request (CSRF)';
        exit;
    }
}


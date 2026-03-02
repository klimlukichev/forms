<?php
declare(strict_types=1);

function auth_user_id(): ?int
{
    $id = $_SESSION['user_id'] ?? null;
    if (!is_int($id) && !is_string($id)) {
        return null;
    }
    $id = (int)$id;
    return $id > 0 ? $id : null;
}

/**
 * @return array<string, mixed>|null
 */
function auth_user(PDO $pdo): ?array
{
    $id = auth_user_id();
    if ($id === null) {
        return null;
    }
    return user_find_by_id($pdo, $id);
}

function auth_login(int $userId): void
{
    session_regenerate_id(true);
    $_SESSION['user_id'] = $userId;
}

function auth_logout(): void
{
    unset($_SESSION['user_id']);
    session_regenerate_id(true);
}

function auth_require_or_redirect(PDO $pdo, string $to): array
{
    $user = auth_user($pdo);
    if ($user === null) {
        redirect($to);
    }
    return $user;
}


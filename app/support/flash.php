<?php
declare(strict_types=1);

/**
 * @return array<int, array{type:string, message:string}>
 */
function flash_get_all(): array
{
    $items = $_SESSION['_flash'] ?? [];
    unset($_SESSION['_flash']);
    return is_array($items) ? $items : [];
}

function flash_add(string $type, string $message): void
{
    if (!isset($_SESSION['_flash']) || !is_array($_SESSION['_flash'])) {
        $_SESSION['_flash'] = [];
    }
    $_SESSION['_flash'][] = ['type' => $type, 'message' => $message];
}


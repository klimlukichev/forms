<?php
declare(strict_types=1);

/**
 * @param array<string, mixed> $params
 */
function render(string $view, array $params = []): void
{
    $viewFile = __DIR__ . '/../views/' . $view . '.php';
    if (!is_file($viewFile)) {
        http_response_code(500);
        echo 'View not found: ' . e($view);
        exit;
    }

    extract($params, EXTR_SKIP);

    require __DIR__ . '/../views/layout.php';
}


<?php
declare(strict_types=1);

[$config, $pdo] = require __DIR__ . '/../app/bootstrap.php';
require __DIR__ . '/../app/routing/Router.php';

$router = new Router();
require __DIR__ . '/../app/routes/web.php';

$route = $_GET['r'] ?? 'home';
if (!is_string($route)) {
    $route = 'home';
}

$router->dispatch($route, request_method(), $config, $pdo);


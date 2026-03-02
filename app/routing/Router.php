<?php
declare(strict_types=1);

final class Router
{
    /**
     * @var array<int, array{name:string,methods:array<int,string>,handler:callable}>
     */
    private array $routes = [];

    /**
     * @param array<int,string> $methods
     * @param callable $handler function (array $config, PDO $pdo): void
     */
    public function add(array $methods, string $name, callable $handler): void
    {
        $normName = strtolower(trim($name));
        $normMethods = [];
        foreach ($methods as $m) {
            $normMethods[] = strtoupper(trim($m));
        }
        $this->routes[] = [
            'name' => $normName,
            'methods' => $normMethods,
            'handler' => $handler,
        ];
    }

    public function get(string $name, callable $handler): void
    {
        $this->add(['GET'], $name, $handler);
    }

    public function post(string $name, callable $handler): void
    {
        $this->add(['POST'], $name, $handler);
    }

    public function any(string $name, callable $handler): void
    {
        $this->add(['GET', 'POST'], $name, $handler);
    }

    public function dispatch(string $name, string $method, array $config, PDO $pdo): void
    {
        $name = strtolower(trim($name));
        $method = strtoupper(trim($method));

        foreach ($this->routes as $route) {
            if ($route['name'] !== $name) {
                continue;
            }
            if (!in_array($method, $route['methods'], true)) {
                continue;
            }

            ($route['handler'])($config, $pdo);
            return;
        }

        http_response_code(404);
        echo 'Страница не найдена';
    }
}


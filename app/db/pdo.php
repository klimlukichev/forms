<?php
declare(strict_types=1);

function db_connect(array $dbConfig): PDO
{
    $driver = (string)($dbConfig['driver'] ?? 'sqlite');

    if ($driver === 'mysql') {
        $mysql = $dbConfig['mysql'] ?? [];
        $host = (string)($mysql['host'] ?? '127.0.0.1');
        $port = (int)($mysql['port'] ?? 3306);
        $database = (string)($mysql['database'] ?? '');
        $username = (string)($mysql['username'] ?? '');
        $password = (string)($mysql['password'] ?? '');
        $charset = (string)($mysql['charset'] ?? 'utf8mb4');

        $dsn = "mysql:host={$host};port={$port};dbname={$database};charset={$charset}";
        $pdo = new PDO($dsn, $username, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
        return $pdo;
    }

    $sqlite = $dbConfig['sqlite'] ?? [];
    $path = (string)($sqlite['path'] ?? (__DIR__ . '/../../data/app.sqlite'));
    $dir = dirname($path);
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
    $dsn = 'sqlite:' . $path;
    $pdo = new PDO($dsn, null, null, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    $pdo->exec('PRAGMA foreign_keys = ON');
    return $pdo;
}


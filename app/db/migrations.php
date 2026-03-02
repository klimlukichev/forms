<?php
declare(strict_types=1);

function db_table_exists(PDO $pdo, string $table): bool
{
    $driver = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
    if ($driver === 'mysql') {
        // MySQL не позволяет использовать плейсхолдеры в SHOW TABLES,
        // поэтому собираем запрос вручную. $table приходит из кода, а не от пользователя.
        $sql = 'SHOW TABLES LIKE ' . $pdo->quote($table);
        $stmt = $pdo->query($sql);
        return $stmt !== false && $stmt->fetchColumn() !== false;
    }

    $stmt = $pdo->prepare("SELECT name FROM sqlite_master WHERE type='table' AND name=:t");
    $stmt->execute([':t' => $table]);
    return (bool)$stmt->fetchColumn();
}

function db_migrate(PDO $pdo): void
{
    if (db_table_exists($pdo, 'users')) {
        return;
    }

    $driver = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);

    if ($driver === 'mysql') {
        $pdo->exec(<<<SQL
CREATE TABLE users (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  login VARCHAR(64) NOT NULL,
  phone VARCHAR(32) NOT NULL,
  email VARCHAR(255) NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  created_at VARCHAR(32) NOT NULL,
  updated_at VARCHAR(32) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uq_users_login (login),
  UNIQUE KEY uq_users_phone (phone),
  UNIQUE KEY uq_users_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
SQL);
        return;
    }

    $pdo->exec(<<<SQL
CREATE TABLE users (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  name TEXT NOT NULL,
  login TEXT NOT NULL,
  phone TEXT NOT NULL,
  email TEXT NOT NULL,
  password_hash TEXT NOT NULL,
  created_at TEXT NOT NULL,
  updated_at TEXT NOT NULL
);
SQL);

    $pdo->exec('CREATE UNIQUE INDEX uq_users_login ON users(login)');
    $pdo->exec('CREATE UNIQUE INDEX uq_users_phone ON users(phone)');
    $pdo->exec('CREATE UNIQUE INDEX uq_users_email ON users(email)');
}


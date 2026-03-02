<?php
declare(strict_types=1);

/**
 * @return array<string, mixed>|null
 */
function user_find_by_id(PDO $pdo, int $id): ?array
{
    $stmt = $pdo->prepare('SELECT * FROM users WHERE id = :id');
    $stmt->execute([':id' => $id]);
    $row = $stmt->fetch();
    return $row ?: null;
}

/**
 * @return array<string, mixed>|null
 */
function user_find_by_login(PDO $pdo, string $login): ?array
{
    $stmt = $pdo->prepare('SELECT * FROM users WHERE login = :login');
    $stmt->execute([':login' => $login]);
    $row = $stmt->fetch();
    return $row ?: null;
}

/**
 * @return array<string, mixed>|null
 */
function user_find_by_email(PDO $pdo, string $email): ?array
{
    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email');
    $stmt->execute([':email' => $email]);
    $row = $stmt->fetch();
    return $row ?: null;
}

/**
 * @return array<string, mixed>|null
 */
function user_find_by_phone(PDO $pdo, string $phone): ?array
{
    $stmt = $pdo->prepare('SELECT * FROM users WHERE phone = :phone');
    $stmt->execute([':phone' => $phone]);
    $row = $stmt->fetch();
    return $row ?: null;
}

/**
 * @return array<string, mixed>|null
 */
function user_find_by_email_or_phone(PDO $pdo, string $identifier): ?array
{
    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email OR phone = :phone LIMIT 1');
    $stmt->execute([
        ':email' => $identifier,
        ':phone' => $identifier,
    ]);
    $row = $stmt->fetch();
    return $row ?: null;
}

/**
 * @param array{name:string,login:string,phone:string,email:string,password_hash:string,created_at:string,updated_at:string} $data
 * @return int inserted id
 */
function user_create(PDO $pdo, array $data): int
{
    $stmt = $pdo->prepare(
        'INSERT INTO users (name, login, phone, email, password_hash, created_at, updated_at)
         VALUES (:name, :login, :phone, :email, :password_hash, :created_at, :updated_at)'
    );

    $stmt->execute([
        ':name' => $data['name'],
        ':login' => $data['login'],
        ':phone' => $data['phone'],
        ':email' => $data['email'],
        ':password_hash' => $data['password_hash'],
        ':created_at' => $data['created_at'],
        ':updated_at' => $data['updated_at'],
    ]);

    return (int)$pdo->lastInsertId();
}

/**
 * @param array{name:string,login:string,phone:string,email:string,updated_at:string} $data
 */
function user_update_profile(PDO $pdo, int $id, array $data): void
{
    $stmt = $pdo->prepare(
        'UPDATE users
         SET name=:name, login=:login, phone=:phone, email=:email, updated_at=:updated_at
         WHERE id=:id'
    );
    $stmt->execute([
        ':id' => $id,
        ':name' => $data['name'],
        ':login' => $data['login'],
        ':phone' => $data['phone'],
        ':email' => $data['email'],
        ':updated_at' => $data['updated_at'],
    ]);
}

function user_update_password(PDO $pdo, int $id, string $passwordHash, string $updatedAt): void
{
    $stmt = $pdo->prepare(
        'UPDATE users SET password_hash=:ph, updated_at=:ua WHERE id=:id'
    );
    $stmt->execute([
        ':id' => $id,
        ':ph' => $passwordHash,
        ':ua' => $updatedAt,
    ]);
}


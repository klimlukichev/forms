<?php
declare(strict_types=1);

/** @var string $viewFile */
/** @var array $config */
/** @var PDO $pdo */

$flashItems = flash_get_all();
$me = auth_user($pdo);
$title = isset($title) && is_string($title) ? $title : ($config['app']['name'] ?? 'Forms');
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title) ?></title>
    <style>
        :root { color-scheme: light; }
        body { margin: 0; font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif; background: #0b1220; color: #e6edf7; }
        a { color: #9cc3ff; text-decoration: none; }
        a:hover { text-decoration: underline; }
        .container { max-width: 860px; margin: 0 auto; padding: 24px 16px 64px; }
        .nav { display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 14px 16px; border-bottom: 1px solid rgba(255,255,255,.08); background: rgba(255,255,255,.03); position: sticky; top: 0; backdrop-filter: blur(8px); }
        .nav .brand { font-weight: 700; letter-spacing: .2px; }
        .nav .links { display: flex; gap: 12px; flex-wrap: wrap; align-items: center; }
        .card { background: rgba(255,255,255,.04); border: 1px solid rgba(255,255,255,.08); border-radius: 14px; padding: 18px; }
        .grid { display: grid; grid-template-columns: 1fr; gap: 14px; }
        @media (min-width: 820px) { .grid.two { grid-template-columns: 1fr 1fr; } }
        label { display: block; font-size: 14px; opacity: .9; margin-bottom: 6px; }
        input { width: 100%; box-sizing: border-box; padding: 10px 12px; border-radius: 10px; border: 1px solid rgba(255,255,255,.14); background: rgba(0,0,0,.18); color: #e6edf7; outline: none; }
        input:focus { border-color: rgba(156,195,255,.65); box-shadow: 0 0 0 3px rgba(156,195,255,.18); }
        .row { display: grid; gap: 10px; }
        .btn { display: inline-flex; align-items: center; justify-content: center; padding: 10px 14px; border-radius: 10px; border: 1px solid rgba(255,255,255,.14); background: rgba(156,195,255,.12); color: #e6edf7; cursor: pointer; font-weight: 600; }
        .btn:hover { background: rgba(156,195,255,.18); }
        .btn.secondary { background: rgba(255,255,255,.06); }
        .muted { opacity: .75; }
        .flash { border-radius: 12px; padding: 12px 14px; border: 1px solid rgba(255,255,255,.12); margin-bottom: 12px; }
        .flash.error { background: rgba(255, 80, 80, .10); border-color: rgba(255, 80, 80, .28); }
        .flash.success { background: rgba(90, 255, 160, .10); border-color: rgba(90, 255, 160, .28); }
        .flash.info { background: rgba(156,195,255,.10); border-color: rgba(156,195,255,.26); }
        .footer { margin-top: 26px; opacity: .65; font-size: 13px; }
        .hint { font-size: 13px; opacity: .8; margin-top: 6px; }
        .hr { height: 1px; background: rgba(255,255,255,.08); margin: 16px 0; }
    </style>
</head>
<body>
<div class="nav">
    <div class="brand"><a href="index.php"><?= e((string)($config['app']['name'] ?? 'Forms')) ?></a></div>
    <div class="links">
        <?php if ($me === null): ?>
            <a href="index.php?r=register">Регистрация</a>
            <a href="index.php?r=login">Вход</a>
        <?php else: ?>
            <span class="muted">Привет, <?= e((string)$me['name']) ?></span>
            <a href="index.php?r=profile">Профиль</a>
            <a href="index.php?r=logout">Выход</a>
        <?php endif; ?>
    </div>
</div>

<div class="container">
    <?php foreach ($flashItems as $f): ?>
        <div class="flash <?= e((string)$f['type']) ?>"><?= e((string)$f['message']) ?></div>
    <?php endforeach; ?>

    <?php require $viewFile; ?>

    <div class="footer">
        Нативный PHP + PDO + Sessions + CSRF.
    </div>
</div>
</body>
</html>


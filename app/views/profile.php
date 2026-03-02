<?php
declare(strict_types=1);

/** @var array $me */
/** @var array $values */
?>

<div class="card">
    <h2 style="margin: 0 0 8px;">Профиль</h2>
    <div class="muted">Доступно только авторизованным пользователям. Здесь можно менять личные данные.</div>

    <form method="post" action="index.php?r=profile" style="margin-top: 14px;">
        <?= csrf_field() ?>

        <div class="grid two">
            <div class="row">
                <label for="name">Имя</label>
                <input id="name" name="name" autocomplete="name" required value="<?= e((string)($values['name'] ?? $me['name'] ?? '')) ?>">
            </div>
            <div class="row">
                <label for="login">Логин</label>
                <input id="login" name="login" autocomplete="username" required value="<?= e((string)($values['login'] ?? $me['login'] ?? '')) ?>">
            </div>
            <div class="row">
                <label for="phone">Телефон</label>
                <input id="phone" name="phone" autocomplete="tel" required value="<?= e((string)($values['phone'] ?? $me['phone'] ?? '')) ?>">
            </div>
            <div class="row">
                <label for="email">Почта</label>
                <input id="email" name="email" type="email" autocomplete="email" required value="<?= e((string)($values['email'] ?? $me['email'] ?? '')) ?>">
            </div>
        </div>

        <div class="hr"></div>

        <div class="muted" style="margin-bottom: 10px;">Смена пароля (необязательно)</div>
        <div class="grid two">
            <div class="row">
                <label for="password">Новый пароль</label>
                <input id="password" name="password" type="password" autocomplete="new-password">
            </div>
            <div class="row">
                <label for="password2">Повтор нового пароля</label>
                <input id="password2" name="password2" type="password" autocomplete="new-password">
            </div>
        </div>

        <div style="margin-top: 14px; display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
            <button class="btn" type="submit">Сохранить</button>
            <a class="btn secondary" href="index.php?r=logout">Выйти</a>
        </div>
    </form>
</div>


<?php
declare(strict_types=1);

/** @var array $values */
?>

<div class="card">
    <h2 style="margin: 0 0 8px;">Регистрация</h2>
    <div class="muted">Поля почта, логин и телефон должны быть уникальны.</div>

    <form method="post" action="index.php?r=register" style="margin-top: 14px;">
        <?= csrf_field() ?>

        <div class="grid two">
            <div class="row">
                <label for="name">Имя</label>
                <input id="name" name="name" autocomplete="name" required value="<?= e((string)($values['name'] ?? '')) ?>">
            </div>
            <div class="row">
                <label for="login">Логин</label>
                <input id="login" name="login" autocomplete="username" required value="<?= e((string)($values['login'] ?? '')) ?>">
                <div class="hint">Например: <span class="muted">klim</span> или <span class="muted">klim_2026</span></div>
            </div>
            <div class="row">
                <label for="phone">Телефон</label>
                <input id="phone" name="phone" autocomplete="tel" required value="<?= e((string)($values['phone'] ?? '')) ?>">
                <div class="hint">В базе сохраняются только цифры (для корректной уникальности).</div>
            </div>
            <div class="row">
                <label for="email">Почта</label>
                <input id="email" name="email" type="email" autocomplete="email" required value="<?= e((string)($values['email'] ?? '')) ?>">
            </div>
            <div class="row">
                <label for="password">Пароль</label>
                <input id="password" name="password" type="password" autocomplete="new-password" required>
            </div>
            <div class="row">
                <label for="password2">Повтор пароля</label>
                <input id="password2" name="password2" type="password" autocomplete="new-password" required>
            </div>
        </div>

        <div style="margin-top: 14px; display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
            <button class="btn" type="submit">Зарегистрироваться</button>
            <a class="btn secondary" href="index.php?r=login">Уже есть аккаунт? Войти</a>
        </div>
    </form>
</div>


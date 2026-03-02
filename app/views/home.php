<?php
declare(strict_types=1);
?>

<div class="card">
    <h2 style="margin: 0 0 8px;">Главная</h2>
    <div class="muted">
        Демонстрация регистрации, авторизации (email/телефон + пароль + капча) и защищённой страницы профиля.
    </div>
    <div class="hr"></div>
    <div class="grid two">
        <div class="card">
            <div style="font-weight: 700; margin-bottom: 6px;">Регистрация</div>
            <div class="muted">Создайте аккаунт: имя, логин, телефон, почта, пароль.</div>
            <div style="margin-top: 10px;">
                <a class="btn" href="index.php?r=register">Открыть регистрацию</a>
            </div>
        </div>
        <div class="card">
            <div style="font-weight: 700; margin-bottom: 6px;">Авторизация</div>
            <div class="muted">Вход по email или телефону и паролю.</div>
            <div style="margin-top: 10px;">
                <a class="btn" href="index.php?r=login">Открыть вход</a>
            </div>
        </div>
    </div>
</div>


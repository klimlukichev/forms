<?php
declare(strict_types=1);

/** @var array $values */
/** @var array $config */

$provider = captcha_provider($config);
?>

<div class="card">
    <h2 style="margin: 0 0 8px;">Вход</h2>
    <div class="muted">Введите email или телефон и пароль.</div>

    <?php if ($provider === 'yandex'): ?>
        <script src="https://smartcaptcha.yandexcloud.net/captcha.js" defer></script>
    <?php elseif ($provider === 'recaptcha'): ?>
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <?php endif; ?>

    <form method="post" action="index.php?r=login" style="margin-top: 14px;">
        <?= csrf_field() ?>

        <div class="grid two">
            <div class="row">
                <label for="identifier">Телефон или email</label>
                <input id="identifier" name="identifier" autocomplete="username" required value="<?= e((string)($values['identifier'] ?? '')) ?>">
            </div>
            <div class="row">
                <label for="password">Пароль</label>
                <input id="password" name="password" type="password" autocomplete="current-password" required>
            </div>
        </div>

        <?php if ($provider === 'yandex'): ?>
            <div style="margin-top: 14px;">
                <div class="smart-captcha" data-sitekey="<?= e((string)($config['captcha']['yandex']['client_key'] ?? '')) ?>"></div>
                <div class="hint">Если ключи не настроены — заполните `app/config.php`.</div>
            </div>
        <?php elseif ($provider === 'recaptcha'): ?>
            <div style="margin-top: 14px;">
                <div class="g-recaptcha" data-sitekey="<?= e((string)($config['captcha']['recaptcha']['site_key'] ?? '')) ?>"></div>
                <div class="hint">Если ключи не настроены — заполните `app/config.php`.</div>
            </div>
        <?php endif; ?>

        <div style="margin-top: 14px; display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
            <button class="btn" type="submit">Войти</button>
            <a class="btn secondary" href="index.php?r=register">Регистрация</a>
        </div>
    </form>
</div>


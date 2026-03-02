<?php
declare(strict_types=1);

/** @var Router $router */

$router->any('home', function (array $config, PDO $pdo): void {
    render('home', [
        'title' => 'Главная',
        'config' => $config,
        'pdo' => $pdo,
    ]);
});

$router->any('register', function (array $config, PDO $pdo): void {
    $values = [
        'name' => '',
        'login' => '',
        'phone' => '',
        'email' => '',
    ];

    if (is_post()) {
        csrf_verify_or_400();

        $name = trim((string)($_POST['name'] ?? ''));
        $login = normalize_login((string)($_POST['login'] ?? ''));
        $phoneRaw = (string)($_POST['phone'] ?? '');
        $phone = normalize_phone($phoneRaw);
        $email = normalize_email((string)($_POST['email'] ?? ''));
        $password = (string)($_POST['password'] ?? '');
        $password2 = (string)($_POST['password2'] ?? '');

        $values = [
            'name' => $name,
            'login' => $login,
            'phone' => $phoneRaw,
            'email' => $email,
        ];

        $errors = [];
        if ($name === '') {
            $errors[] = 'Введите имя.';
        }
        if ($login === '' || !preg_match('/^[a-z0-9_\-\.]{3,64}$/i', $login)) {
            $errors[] = 'Логин должен быть 3–64 символа (буквы/цифры/._-).';
        }
        if ($phone === '' || strlen($phone) < 10) {
            $errors[] = 'Введите корректный телефон.';
        }
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Введите корректный email.';
        }
        if ($password === '' || strlen($password) < 6) {
            $errors[] = 'Пароль должен быть не короче 6 символов.';
        }
        if ($password !== $password2) {
            $errors[] = 'Пароли не совпадают.';
        }

        if (user_find_by_email($pdo, $email) !== null) {
            $errors[] = 'Такая почта уже зарегистрирована.';
        }
        if (user_find_by_phone($pdo, $phone) !== null) {
            $errors[] = 'Такой телефон уже зарегистрирован.';
        }
        if (user_find_by_login($pdo, $login) !== null) {
            $errors[] = 'Такой логин уже занят.';
        }

        if (empty($errors)) {
            $id = user_create($pdo, [
                'name' => $name,
                'login' => $login,
                'phone' => $phone,
                'email' => $email,
                'password_hash' => password_hash($password, PASSWORD_DEFAULT),
                'created_at' => now_iso(),
                'updated_at' => now_iso(),
            ]);
            auth_login($id);
            flash_add('success', 'Регистрация успешна.');
            redirect('index.php?r=profile');
        }

        foreach ($errors as $m) {
            flash_add('error', $m);
        }
    }

    render('register', [
        'title' => 'Регистрация',
        'config' => $config,
        'pdo' => $pdo,
        'values' => $values,
    ]);
});

$router->any('login', function (array $config, PDO $pdo): void {
    $values = [
        'identifier' => '',
    ];

    if (is_post()) {
        csrf_verify_or_400();

        $cap = captcha_verify($config, client_ip(), $_POST);
        if (!$cap['ok']) {
            flash_add('error', (string)$cap['error']);
        } else {
            $identifierRaw = trim((string)($_POST['identifier'] ?? ''));
            $password = (string)($_POST['password'] ?? '');
            $values['identifier'] = $identifierRaw;

            $identifier = str_contains($identifierRaw, '@')
                ? normalize_email($identifierRaw)
                : normalize_phone($identifierRaw);

            $user = ($identifier !== '') ? user_find_by_email_or_phone($pdo, $identifier) : null;
            if ($user === null || !password_verify($password, (string)$user['password_hash'])) {
                flash_add('error', 'Неверный логин или пароль.');
            } else {
                auth_login((int)$user['id']);
                flash_add('success', 'Вход выполнен.');
                redirect('index.php?r=profile');
            }
        }
    }

    render('login', [
        'title' => 'Вход',
        'config' => $config,
        'pdo' => $pdo,
        'values' => $values,
    ]);
});

$router->any('profile', function (array $config, PDO $pdo): void {
    $me = auth_require_or_redirect($pdo, 'index.php');
    $values = [];

    if (is_post()) {
        csrf_verify_or_400();

        $name = trim((string)($_POST['name'] ?? ''));
        $login = normalize_login((string)($_POST['login'] ?? ''));
        $phoneRaw = (string)($_POST['phone'] ?? '');
        $phone = normalize_phone($phoneRaw);
        $email = normalize_email((string)($_POST['email'] ?? ''));
        $password = (string)($_POST['password'] ?? '');
        $password2 = (string)($_POST['password2'] ?? '');

        $values = [
            'name' => $name,
            'login' => $login,
            'phone' => $phoneRaw,
            'email' => $email,
        ];

        $errors = [];
        if ($name === '') {
            $errors[] = 'Введите имя.';
        }
        if ($login === '' || !preg_match('/^[a-z0-9_\-\.]{3,64}$/i', $login)) {
            $errors[] = 'Логин должен быть 3–64 символа (буквы/цифры/._-).';
        }
        if ($phone === '' || strlen($phone) < 10) {
            $errors[] = 'Введите корректный телефон.';
        }
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Введите корректный email.';
        }

        $byEmail = user_find_by_email($pdo, $email);
        if ($byEmail !== null && (int)$byEmail['id'] !== (int)$me['id']) {
            $errors[] = 'Такая почта уже зарегистрирована.';
        }
        $byPhone = user_find_by_phone($pdo, $phone);
        if ($byPhone !== null && (int)$byPhone['id'] !== (int)$me['id']) {
            $errors[] = 'Такой телефон уже зарегистрирован.';
        }
        $byLogin = user_find_by_login($pdo, $login);
        if ($byLogin !== null && (int)$byLogin['id'] !== (int)$me['id']) {
            $errors[] = 'Такой логин уже занят.';
        }

        $changePassword = ($password !== '' || $password2 !== '');
        if ($changePassword) {
            if (strlen($password) < 6) {
                $errors[] = 'Новый пароль должен быть не короче 6 символов.';
            }
            if ($password !== $password2) {
                $errors[] = 'Пароли (новый и повтор) не совпадают.';
            }
        }

        if (empty($errors)) {
            user_update_profile($pdo, (int)$me['id'], [
                'name' => $name,
                'login' => $login,
                'phone' => $phone,
                'email' => $email,
                'updated_at' => now_iso(),
            ]);

            if ($changePassword) {
                user_update_password($pdo, (int)$me['id'], password_hash($password, PASSWORD_DEFAULT), now_iso());
            }

            flash_add('success', 'Профиль обновлён.');
            redirect('index.php?r=profile');
        }

        foreach ($errors as $m) {
            flash_add('error', $m);
        }

        $me = user_find_by_id($pdo, (int)$me['id']) ?? $me;
    } else {
        $me = user_find_by_id($pdo, (int)$me['id']) ?? $me;
    }

    render('profile', [
        'title' => 'Профиль',
        'config' => $config,
        'pdo' => $pdo,
        'me' => $me,
        'values' => $values,
    ]);
});

$router->any('logout', function (array $config, PDO $pdo): void {
    auth_logout();
    flash_add('info', 'Вы вышли из аккаунта.');
    redirect('index.php');
});


<?php
declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors', '1');

$config = require __DIR__ . '/config.php';

require_once __DIR__ . '/support/helpers.php';
require_once __DIR__ . '/support/session.php';
require_once __DIR__ . '/support/flash.php';
require_once __DIR__ . '/support/view.php';
require_once __DIR__ . '/security/csrf.php';
require_once __DIR__ . '/db/pdo.php';
require_once __DIR__ . '/db/migrations.php';
require_once __DIR__ . '/repositories/users.php';
require_once __DIR__ . '/security/auth.php';
require_once __DIR__ . '/security/captcha.php';

session_bootstrap($config['session']);

$pdo = db_connect($config['db']);
db_migrate($pdo);

return [$config, $pdo];


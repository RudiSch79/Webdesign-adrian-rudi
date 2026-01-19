<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('ROOT_PATH', dirname(__DIR__));
define('INCLUDE_PATH', ROOT_PATH . '/include');
define('DB_PATH', ROOT_PATH . '/database');
define('DATA_PATH', ROOT_PATH . '/data');

require_once DB_PATH . '/dbconfig.php';
require_once DB_PATH . '/db.php';
require_once DB_PATH . '/helpers.php';

require_once INCLUDE_PATH . '/helpers.php';
require_once INCLUDE_PATH . '/auth.php';
?>

<?php

session_start();

require_once __DIR__ . "/../database/db.php";

$isLoggedIn = isset($_SESSION['user']);
$username   =  $isLoggedIn ? $_SESSION['user']['username'] : null;

define('ROOT_PATH', dirname(__DIR__));
define('INCLUDE_PATH', ROOT_PATH . '/include');
define('DB_PATH', ROOT_PATH . '/database');
define('DATA_PATH', ROOT_PATH . '/data');

require_once DB_PATH . '/config.php';
require_once DB_PATH . '/db.php';

require_once INCLUDE_PATH . '/helpers.php';
require_once INCLUDE_PATH . '/auth.php';
?>

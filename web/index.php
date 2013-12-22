<?php

$filename = __DIR__.preg_replace('#(\?.*)$#', '', $_SERVER['REQUEST_URI']);
if (php_sapi_name() === 'cli-server' && is_file($filename)) {
    return false;
}

$app = require_once __DIR__ . '/../app/propilex.php';
$app = include_once __DIR__ . '/../app/stack.php';
Stack\run($app);

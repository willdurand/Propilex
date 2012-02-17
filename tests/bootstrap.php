<?php

$app = require_once __DIR__ . '/../app/config/config.php';
$app['autoloader']->registerNamespaces(array(
    'Propilex'  => array(
        __DIR__  . '/../src',
        __DIR__  . '/../tests',
    )
));

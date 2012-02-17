<?php

$app = require_once __DIR__ . '/../config/app.php';
$app['autoloader']->registerNamespaces(array(
    'Propilex'  => array(
        __DIR__  . '/../src',
        __DIR__  . '/../tests',
    )
));

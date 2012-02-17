<?php

require_once __DIR__.'/../vendor/silex.phar';
require_once __DIR__.'/../vendor/propel/propel1/runtime/lib/Propel.php';

$app = new Silex\Application();
$app['autoloader']->registerNamespaces(array(
    'Propilex'  => array(
        __DIR__  . '/../src',
        __DIR__  . '/../tests',
    )
));

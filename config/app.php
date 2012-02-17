<?php

require_once __DIR__.'/../vendor/silex.phar';

$app = new Silex\Application();

$app->register(new Silex\Provider\PropelServiceProvider(), array(
    'propel.path'           => __DIR__ . '/../vendor/propel/runtime/lib',
    'propel.config_file'    => __DIR__ . '/../config/conf/Propilex-conf.php',
));

$app['autoloader']->registerNamespaces(array(
    'Propilex'  => __DIR__  . '/../src',
));

// Parser that removes "root" on JSON objects
$app['json_parser'] = new \Propilex\Parser\JsonParser();

// Debug?
$app['debug'] = true;

return $app;

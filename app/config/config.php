<?php

require_once __DIR__.'/../../vendor/silex.phar';

$app = new Silex\Application();

$app['autoloader']->registerNamespaces(array(
    'Propel\Silex'  => __DIR__ . '/../../vendor/propel/propel-service-provider/src',
    'Propilex'      => __DIR__ . '/../../src',
));

$app->register(new Propel\Silex\PropelServiceProvider(), array(
    'propel.path'           => __DIR__ . '/../../vendor/propel/propel1/runtime/lib',
    'propel.config_file'    => __DIR__ . '/conf/Propilex-conf.php',
));

// Parser that removes "root" on JSON objects
$app['json_parser'] = new \Propilex\Parser\JsonParser();

// Debug?
$app['debug'] = true;

return $app;

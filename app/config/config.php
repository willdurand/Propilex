<?php

require_once __DIR__.'/../../vendor/autoload.php';

$app = new Silex\Application();
$app->register(new Propel\Silex\PropelServiceProvider(), array(
    'propel.config_file'    => __DIR__ . '/conf/Propilex-conf.php',
));

// Parser that removes "root" on JSON objects
$app['json_parser'] = new Propilex\Parser\JsonParser();

// Debug?
$app['debug'] = true;

return $app;

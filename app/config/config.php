<?php

require_once __DIR__.'/../../vendor/autoload.php';

$app = new Silex\Application();
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());
$app->register(new Propel\Silex\PropelServiceProvider(), array(
    'propel.config_file' => __DIR__ . '/conf/Propilex-conf.php',
    'propel.model_path'  => __DIR__ . '/../../src/Propilex/Model',
));

// Debug?
$app['debug'] = true;

return $app;

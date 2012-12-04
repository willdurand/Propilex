<?php

require_once __DIR__.'/../../vendor/autoload.php';

$app = new Silex\Application();
$app->register(new Silex\Provider\ValidatorServiceProvider());
$app->register(new Propel\Silex\PropelServiceProvider(), array(
    'propel.config_file' => __DIR__ . '/conf/Propilex-conf.php',
    'propel.model_path'  => __DIR__ . '/../../src/Propilex/Model',
));

// Debug?
$app['debug'] = 'dev' === getenv('APPLICATION_ENV');

return $app;

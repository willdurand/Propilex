<?php

require_once __DIR__.'/silex.phar';

use Symfony\Component\HttpFoundation\Response;

$app = new Silex\Application();
$app->register(new Silex\Provider\PropelServiceProvider(), array(
    'propel.config_file'    => __DIR__ . '/config/conf/Propilex-conf.php',
));

$app['autoloader']->registerNamespaces(array(
    'Propilex'  => __DIR__ . '/src',
));

$app['debug'] = true;

$app->get('/', function () use ($app) {
    return new Response(
        \Propilex\Model\DocumentQuery::create()
            ->find()->toJSON(),
        200,
        array(
            'Content-type' => 'application/json'
        )
    );
});

$app->run();

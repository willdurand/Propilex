<?php

$app = require_once __DIR__ . '/../app/propilex.php';
// Debug?
$app['debug'] = 'dev' === getenv('APPLICATION_ENV');
$app->run();

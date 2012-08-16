<?php
ini_set('display_errors', 1);
error_reporting(-1);

$app = require_once __DIR__ . '/../app/propilex.php';
$app->run();

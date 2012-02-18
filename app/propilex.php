<?php

$app = require_once __DIR__ . '/config/config.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @see http://silex.sensiolabs.org/doc/cookbook/json_request_body.html
 */
$app->before(function (Request $request) {
    if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
        $data = json_decode($request->getContent(), true);

        // filter values
        foreach ($data as $k => $v) {
            if (false === array_search($k, array('Id', 'Title', 'Body'))) {
                unset($data[$k]);
            }
        }

        $request->request->replace(is_array($data) ? $data : array());
    }
});

/**
 * Entry point
 */
$app->get('/', function() use ($app) {
    return new Response(file_get_contents(__DIR__ . '/../web/index.html'), 200);
});

/**
 * Register REST methods to manage documents
 */
$app->register(new Propilex\Provider\RestControllerProvider(), array(
    'rest_controller.model_name'    => 'documents',
    'rest_controller.model_class'   => '\Propilex\Model\Document',
));

return $app;

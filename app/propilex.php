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
 * Register a REST controller to manage documents
 */
$app->mount('/documents', new Propilex\Provider\RestController(
    'document', '\Propilex\Model\Document', 'getUpdatedAt'
));

return $app;

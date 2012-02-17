<?php

$app = require_once __DIR__ . '/../config/app.php';

use Propilex\Model\Document;
use Propilex\Model\DocumentQuery;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @see http://silex.sensiolabs.org/doc/cookbook/json_request_body.html
 */
$app->before(function (Request $request) {
    if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
        $data = json_decode($request->getContent(), true);
        $request->request->replace(is_array($data) ? $data : array());
    }
});

/**
 * Entry point
 */
$app->get('/', function() use ($app) {
    return new Response(file_get_contents(__DIR__ . '/index.html'), 200);
});

/**
 * Returns all documents
 */
$app->get('/documents', function () use ($app) {
    $query = DocumentQuery::create()
        ->select(array('Id', 'Title', 'Body'));

    return new Response($query->find()->exportTo($app['json_parser']), 200, array(
        'Content-Type' => 'application/json',
    ));
});

/**
 * Returns a specific document identified by a given id
 */
$app->get('/documents/{id}', function ($id) use ($app) {
    $document = DocumentQuery::create()
        ->findPk($id);

    if ($document instanceof Document) {
        return new Response($document->exportTo($app['json_parser']), 200, array (
            'Content-Type' => 'application/json',
        ));
    } else {
        return new Response('', 404);
    }
});

/**
 * Create a new Document
 */
$app->post('/documents', function (Request $request) use ($app) {
    $document = new Document();
    $document->fromArray($request->request);
    $document->save();

    return new Response($document->exportTo($app['json_parser']), 201, array (
        'Content-Type' => 'application/json',
    ));
});

/**
 * Update a Document identified by a given id
 */
$app->put('/documents/{id}', function ($id, Request $request) use ($app) {
    $document = DocumentQuery::create()
        ->findPk($id);

    if ($document instanceof Document) {
        $document->fromArray($request->request);
        $document->save();

        return new Response($document->exportTo($app['json_parser']), 200, array (
            'Content-Type' => 'application/json',
        ));
    } else {
        return new Response('', 404);
    }
});

$app->run();

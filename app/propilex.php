<?php

use Propilex\Model\Document;
use Propilex\Model\DocumentQuery;
use Propilex\Model\Repository\PropelDocumentRepository;
use Propilex\View\Error;
use Propilex\View\FormErrors;
use Propilex\View\ViewHandler;
use Propilex\Hateoas\VndErrorRepresentation;

$app = require __DIR__ . '/config/config.php';

// Error
$app->error(function (\Exception $e, $code) use ($app) {
    return $app['view_handler']->handle(
        new VndErrorRepresentation($e->getMessage()),
        $code
    );
});

// Validator
$app['document_validator'] = $app->protect(function (Document $document) use ($app) {
    $errors = $app['validator']->validate($document);

    if (0 < count($errors)) {
        return new FormErrors($errors);
    }

    return true;
});

// Model
$app['document_repository'] = $app->share(function () {
    return new PropelDocumentRepository(DocumentQuery::create());
});

// View
$app['view_handler'] = $app->share(function () use ($app) {
    return new ViewHandler($app['serializer'], $app['request']);
});

/**
 * Entry point
 */
$app->get('/', function () {
    return file_get_contents(__DIR__ . '/../web/index.html');
});

/**
 * Documents
 */
$app
    ->get('/documents', 'Propilex\Controller\DocumentController::listAction')
    ->bind('document_list');

$app
    ->get('/documents/{id}', 'Propilex\Controller\DocumentController::getAction')
    ->assert('id', '\d+')
    ->bind('document_get');

$app
    ->post('/documents', 'Propilex\Controller\DocumentController::postAction')
    ->bind('document_post');

$app
    ->put('/documents/{id}', 'Propilex\Controller\DocumentController::putAction')
    ->assert('id', '\d+')
    ->bind('document_put');

$app
    ->delete('/documents/{id}', 'Propilex\Controller\DocumentController::deleteAction')
    ->assert('id', '\d+')
    ->bind('document_delete');

/**
 *  Curies
 */
$app['curies_prefix']     = 'p';
$app['curies_route_name'] = 'curies_get';

$app->get('/rels/{rel}', function ($rel) use ($app) {
    if (is_file($file = sprintf(__DIR__ . '/../src/Propilex/Resources/%s.md', $rel))) {
        return $app['markdown']->transformMarkdown(file_get_contents($file));
    }

    $app->abort(404);
})->bind($app['curies_route_name']);

return $app;

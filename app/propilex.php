<?php

$app = require_once __DIR__ . '/config/config.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Mapping\ClassMetadataFactory;
use Symfony\Component\Validator\Mapping\Loader\YamlFileLoader;
use Hateoas\Hateoas;
use Hateoas\Builder\RouteAwareLinkBuilder;
use Hateoas\Builder\ResourceBuilder;
use Hateoas\Factory\Config\YamlConfig;
use Hateoas\Factory\RouteAwareFactory;

// Configure the validator service
$app['validator.mapping.class_metadata_factory'] = new ClassMetadataFactory(
    new YamlFileLoader(__DIR__ . '/validation.yml')
);

// Configure Hateoas services
$app['hateoas_serializer'] = Hateoas::getSerializer(array(
    ''           => __DIR__ . '/serializer',
    'Propilex'   => __DIR__ . '/serializer',
), $app['debug']);
$app['hateoas_builder']    = new ResourceBuilder(
    new RouteAwareFactory(new YamlConfig(__DIR__ . '/hateoas.yml')),
    new RouteAwareLinkBuilder($app['url_generator'])
);

/**
 * @see http://silex.sensiolabs.org/doc/cookbook/json_request_body.html
 */
$app->before(function (Request $request) {
    if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
        $data = json_decode($request->getContent(), true);

        // filter values
        foreach ($data as $k => $v) {
            if (!in_array($k, array('id', 'title', 'body'))) {
                unset($data[$k]);
            }
        }

        $request->request->replace(is_array($data) ? $data : array());
    }
});

/**
 * Entry point
 */
$app->get('/', function() {
    return file_get_contents(__DIR__ . '/../web/index.html');
});

/**
 * Register a REST controller to manage documents
 */
$app->mount('/documents', new Propilex\Provider\RestController(
    'document', 'Propilex\Model\Document', 'getUpdatedAt'
));

return $app;

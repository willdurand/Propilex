<?php

require_once __DIR__.'/../vendor/autoload.php';
Doctrine\Common\Annotations\AnnotationRegistry::registerLoader('class_exists');

use Hateoas\Serializer\XmlHalSerializer;
use Hateoas\Representation\Factory\PagerfantaFactory;
use Hateoas\UrlGenerator\SymfonyUrlGenerator;
use Hautelook\TemplatedUriRouter\Routing\Generator\Rfc6570Generator;
use Propilex\Model\Document;
use Propilex\View\Error;
use Propilex\View\FormErrors;
use Propilex\View\ViewHandler;
use Propilex\Hateoas\CuriesConfigurationExtension;
use Propilex\Hateoas\VndErrorRepresentation;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;
use Symfony\Component\Validator\Mapping\ClassMetadataFactory;
use Symfony\Component\Validator\Mapping\Loader\YamlFileLoader;

$app = new Silex\Application();

// Providers
$app->register(new Silex\Provider\ValidatorServiceProvider());
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());
$app->register(new Propel\Silex\PropelServiceProvider(), array(
    'propel.config_file' => __DIR__ . '/config/propel/propilex.php',
    'propel.model_path'  => __DIR__ . '/../src/Propilex/Model',
));

// Configure the validator service
$app['validator.mapping.class_metadata_factory'] = new ClassMetadataFactory(
    new YamlFileLoader(__DIR__ . '/config/validation.yml')
);

// Configure Hateoas serializer
$app['serializer'] = $app->share(function () use ($app) {
    $jmsSerializerBuilder = JMS\Serializer\SerializerBuilder::create()
        ->setMetadataDirs(array(
            ''           => __DIR__ . '/config/serializer',
            'Propilex'   => __DIR__ . '/config/serializer',
        ))
        ->setDebug($app['debug'])
        ->setCacheDir(__DIR__ . '/cache/serializer')
    ;

    return Hateoas\HateoasBuilder::create($jmsSerializerBuilder)
        ->setMetadataDirs(array(
            ''           => __DIR__ . '/config/serializer',
            'Propilex'   => __DIR__ . '/config/serializer',
        ))
        ->setDebug($app['debug'])
        ->setCacheDir(__DIR__ . '/cache/hateoas')
        ->setUrlGenerator(null, new SymfonyUrlGenerator($app['url_generator']))
        ->setUrlGenerator('templated', new SymfonyUrlGenerator($app['templated_uri_generator']))
        ->setXmlSerializer(new XmlHalSerializer())
        ->addConfigurationExtension(new CuriesConfigurationExtension(
            $app['curies_route_name'],
            'templated'
        ))
        ->setExpressionContextVariable('curies_prefix', $app['curies_prefix'])
        ->build();
});

$app['hateoas.pagerfanta_factory'] = $app->share(function () use ($app) {
    return new PagerfantaFactory();
});

// Translation
$app->register(new Silex\Provider\TranslationServiceProvider());

$app->before(function (Request $request) use ($app) {
    $validatorFile = __DIR__ . '/../vendor/symfony/validator/Symfony/Component/Validator/Resources/translations/validators.%s.xlf';
    $locale        = $request->attributes->get('_language', 'en');

    $app['translator']->setLocale($locale);

    $app['translator']->addLoader('xlf', new Symfony\Component\Translation\Loader\XliffFileLoader());
    $app['translator']->addResource('xlf', sprintf($validatorFile, $locale), $locale, 'validators');

    $messagesLocale = $locale;
    if (!is_file($messagesFile = __DIR__ . '/config/messages.' . $messagesLocale . '.yml')) {
        $messagesFile   = sprintf(__DIR__ . '/config/messages.%s.yml', $app['translation.fallback']);
        $messagesLocale = $app['translation.fallback'];
    }

    $app['translator']->addLoader('yml', new Symfony\Component\Translation\Loader\YamlFileLoader());
    $app['translator']->addResource('yml', $messagesFile, $messagesLocale);
});

$app['templated_uri_generator'] = $app->share(function () use ($app) {
    return new Rfc6570Generator($app['routes'], $app['request_context']);
});

// Markdown
$app->register(new Nicl\Silex\MarkdownServiceProvider());

// Negotiation
$app->register(new KPhoen\Provider\NegotiationServiceProvider([
    'json' => [ 'application/hal+json', 'application/json' ],
    'xml'  => [ 'application/hal+xml', 'application/xml' ],
]));

// Document validator
$app['document_validator'] = $app->protect(function (Document $document) use ($app) {
    $errors = $app['validator']->validate($document);

    if (0 < count($errors)) {
        return new FormErrors($errors);
    }

    return true;
});

// View
$app['view_handler'] = $app->share(function () use ($app) {
    return new ViewHandler($app['serializer'], $app['request'], $app['acceptable_mime_types']);
});

// Error handler
$app->error(function (\Exception $e, $code) use ($app) {
    if (406 === $code) {
        return new Response($e->getMessage(), 406, [
            'Content-Type' => 'text/plain'
        ]);
    }

    return $app['view_handler']->handle(
        new VndErrorRepresentation($e->getMessage()),
        $code
    );
});

return $app;

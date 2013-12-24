<?php

require_once __DIR__.'/../../vendor/autoload.php';
Doctrine\Common\Annotations\AnnotationRegistry::registerLoader('class_exists');

use Hateoas\Serializer\XmlHalSerializer;
use Hateoas\Representation\Factory\PagerfantaFactory;
use Hateoas\UrlGenerator\SymfonyUrlGenerator;
use Hautelook\TemplatedUriRouter\Routing\Generator\Rfc6570Generator;
use Propilex\Hateoas\CuriesConfigurationExtension;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Mapping\ClassMetadataFactory;
use Symfony\Component\Validator\Mapping\Loader\YamlFileLoader;

$app = new Silex\Application();

// Debug?
$app['debug'] = 'dev' === getenv('APPLICATION_ENV');

// Providers
$app->register(new Silex\Provider\ValidatorServiceProvider());
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());
$app->register(new Propel\Silex\PropelServiceProvider(), array(
    'propel.config_file' => __DIR__ . '/propel/conf/Propilex-conf.php',
    'propel.model_path'  => __DIR__ . '/../../src/Propilex/Model',
));

// Configure the validator service
$app['validator.mapping.class_metadata_factory'] = new ClassMetadataFactory(
    new YamlFileLoader(__DIR__ . '/validation.yml')
);

// Configure Hateoas serializer
$app['serializer'] = $app->share(function () use ($app) {
    $jmsSerializerBuilder = JMS\Serializer\SerializerBuilder::create()
        ->setMetadataDirs(array(
            ''           => __DIR__ . '/serializer',
            'Propilex'   => __DIR__ . '/serializer',
        ))
        ->setDebug($app['debug'])
        ->setCacheDir(__DIR__ . '/../cache/serializer')
    ;

    return Hateoas\HateoasBuilder::create($jmsSerializerBuilder)
        ->setMetadataDirs(array(
            ''           => __DIR__ . '/serializer',
            'Propilex'   => __DIR__ . '/serializer',
        ))
        ->setDebug($app['debug'])
        ->setCacheDir(__DIR__ . '/../cache/hateoas')
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
    $validatorFile = __DIR__ . '/../../vendor/symfony/validator/Symfony/Component/Validator/Resources/translations/validators.%s.xlf';
    $locale        = $request->attributes->get('_language', 'en');

    $app['translator']->setLocale($locale);

    $app['translator']->addLoader('xlf', new Symfony\Component\Translation\Loader\XliffFileLoader());
    $app['translator']->addResource('xlf', sprintf($validatorFile, $locale), $locale, 'validators');

    $messagesLocale = $locale;
    if (!is_file($messagesFile = __DIR__ . '/messages.' . $messagesLocale . '.yml')) {
        $messagesFile   = __DIR__ . '/messages.en.yml';
        $messagesLocale = 'en';
    }

    $app['translator']->addLoader('yml', new Symfony\Component\Translation\Loader\YamlFileLoader());
    $app['translator']->addResource('yml', $messagesFile, $messagesLocale);
});

$app['templated_uri_generator'] = $app->share(function () use ($app) {
    return new Rfc6570Generator($app['routes'], $app['request_context']);
});

// Markdown
$app->register(new Nicl\Silex\MarkdownServiceProvider());

return $app;

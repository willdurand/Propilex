<?php

require_once __DIR__.'/../../vendor/autoload.php';

$app = new Silex\Application();
$app->register(new Propel\Silex\PropelServiceProvider(), array(
    'propel.config_file'	=> __DIR__ . '/conf/Propilex-conf.php',
    'propel.model_path'		=> __DIR__ . '/../../src/Propilex/Model',
));

$app->register(new Silex\Provider\TwigServiceProvider(), array(
	'twig.path'	=> __DIR__ . '/../../views',
));
/*
$app->register(new SilexExtension\AsseticExtension(), array(
    'assetic.options' => array(
        'debug' => $app['debug']
    ), 
    'assetic.filters' => $app->protect(function ( $fm ) use($app )
    {
        $fm->set('yui_css', new Assetic\Filter\Yui\CssCompressorFilter($app['assetic.filter.yui_compressor.path']));
        $fm->set('yui_js', new Assetic\Filter\Yui\JsCompressorFilter($app['assetic.filter.yui_compressor.path']));
    }), 
    'assetic.assets' => $app->protect(function ( $am, $fm ) use($app )
    {
        $am->set('styles', new Assetic\Asset\AssetCache(new Assetic\Asset\GlobAsset($app['assetic.input.path_to_css'], 
        // Yui compressor is disabled by default.
        // If you need it, and you have installed it, uncomment the
        // next line, and delete "array()"
        // array($fm->get('yui_css'))
        array()), new Assetic\Cache\FilesystemCache($app['assetic.path_to_cache'])));
        $am->get('styles')->setTargetPath($app['assetic.output.path_to_css']);
        
        $am->set('scripts', new Assetic\Asset\AssetCache(new Assetic\Asset\GlobAsset($app['assetic.input.path_to_js'], 
        // Yui compressor is disabled by default.
        // If you need it, and you have installed it, uncomment the
        // next line, and delete "array()"
        // array($fm->get('yui_js'))
        array()), new Assetic\Cache\FilesystemCache($app['assetic.path_to_cache'])));
        $am->get('scripts')->setTargetPath($app['assetic.output.path_to_js']);
    })
));
*/

// Parser that removes "root" on JSON objects
$app['json_parser'] = new Propilex\Parser\JsonParser();

// Debug?
$app['debug'] = true;

return $app;

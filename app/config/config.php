<?php

require_once __DIR__.'/../../vendor/autoload.php';

$app = new Silex\Application();

// Debug?
$app['debug'] = true;

// @see http://silex.sensiolabs.org/doc/providers/security.html
//$app->register(new Silex\Provider\SecurityServiceProvider() );

$app->register(new Propel\Silex\PropelServiceProvider(), array(
    'propel.config_file'	=> __DIR__ . '/conf/Propilex-conf.php',
    'propel.model_path'		=> __DIR__ . '/../../src/Propilex/Model',
));

$app->register(new Silex\Provider\TwigServiceProvider(), array(
	'twig.path'	=> __DIR__ . '/../../views',
));

$app->register(new SilexExtension\AsseticExtension(), array(
    'assetic.class_path' => __DIR__.'/../../vendor/assetic/src',
    'assetic.path_to_web' => __DIR__ . '/../../web/assets',
	'assetic.filter.yui_compressor.path' => __DIR__ . '/../../bin/yuicompressor/build/yuicompressor-2.4.7.jar',
	//'assetic.output.path_to_css' => __DIR__ . '/../../assets/styles.css',
	//'assetic.input.path_to_css' => __DIR__ . '/../../web/css/*.css',
	//'assetic.path_to_cache' => __DIR__ . '/../../assets/cache',
    'assetic.options' => array(
        'debug' => $app['debug'],
        //'twig_support' => true
    ), 
    'assetic.filters' => $app->protect(function ( $fm ) use($app )
    {
        $fm->set('less', new Assetic\Filter\LessphpFilter());
        $fm->set('yui_css', new Assetic\Filter\Yui\CssCompressorFilter($app['assetic.filter.yui_compressor.path']));
    }), 
    /*'assetic.assets' => $app->protect(function ( $am, $fm ) use($app )
    {
        $am->set('styles', 
        		new Assetic\Asset\AssetCache(
        			new Assetic\Asset\GlobAsset(
        				$app['assetic.input.path_to_css'], 
        				array($fm->get('yui_css'))
        			), 
        			new Assetic\Cache\FilesystemCache($app['assetic.path_to_cache'])
        		)
        );
        $am->get('styles')->setTargetPath($app['assetic.output.path_to_css']);
    })*/
));

// Parser that removes "root" on JSON objects
$app['json_parser'] = new Propilex\Parser\JsonParser();

return $app;

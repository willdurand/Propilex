<?php

namespace Propilex\Controller;

use Propilex\View\Endpoint;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class HomeController
{
    public function indexAction(Request $request, Application $app)
    {
        if ('html' === $request->attributes->get('_format')) {
            return file_get_contents(__DIR__ . '/../../../web/index.html');
        }

        return $app['view_handler']->handle(
            new Endpoint($app['translator'])
        );
    }
}

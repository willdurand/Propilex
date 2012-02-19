<?php

namespace Propilex\Provider;

use Silex\Application;
use Silex\ControllerCollection;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @author William Durand <william.durand1@gmail.com>
 */
class RestController implements ControllerProviderInterface
{
    private $modelName;

    private $modelClass;

    public function __construct($modelName, $modelClass = null)
    {
        $this->modelName  = $modelName;
        $this->modelClass = $modelClass;
    }

    /**
     * {@inheritdoc}
     */
    public function connect(Application $app)
    {
        $modelName = $this->modelName;
        $prefix    = sprintf('rest_controller.%s.', $this->modelName);

        if (null !== $this->modelClass) {
            $app[$prefix.'model_class'] = $this->modelClass;
        }

        if (isset($app[$prefix.'model_class'])) {
            $app[$prefix.'query_class'] = $app[$prefix.'model_class'] . 'Query';
        } else {
            throw new \InvalidArgumentException(sprintf('You have to configure the "%s.model_class" parameter.', $prefix));
        }

        $controllers = new ControllerCollection();

        /**
         * Returns all objects
         */
        $controllers->get('/', function () use ($app, $prefix) {
            $query = new $app[$prefix.'query_class'];

            return new Response($query->find()->exportTo($app['json_parser']), 200, array(
                'Content-Type' => 'application/json',
            ));
        });

        /**
         * Returns a specific object identified by a given id
         */
        $controllers->get('/{id}', function ($id) use ($app, $prefix, $modelName) {
            $query  = new $app[$prefix.'query_class'];
            $object = $query->findPk($id);

            if (!$object instanceof $app[$prefix.'model_class']) {
                throw new NotFoundHttpException(
                    sprintf('%s with id "%d" does not exist.', ucfirst($modelName), $id)
                );
            }

            return new Response($object->exportTo($app['json_parser']), 200, array (
                'Content-Type' => 'application/json',
            ));
        });

        /**
         * Create a new object
         */
        $controllers->post('/', function (Request $request) use ($app, $prefix) {
            $object = new $app[$prefix.'model_class'];
            $object->fromArray($request->request->all());
            $object->save();

            return new Response($object->exportTo($app['json_parser']), 201, array (
                'Content-Type' => 'application/json',
            ));
        });

        /**
         * Update a object identified by a given id
         */
        $controllers->put('/{id}', function ($id, Request $request) use ($app, $prefix, $modelName) {
            $query  = new $app[$prefix.'query_class'];
            $object = $query->findPk($id);

            if (!$object instanceof $app[$prefix.'model_class']) {
                throw new NotFoundHttpException(
                    sprintf('%s with id "%d" does not exist.', ucfirst($modelName), $id)
                );
            }

            $object->fromArray($request->request->all());
            $object->save();

            return new Response($object->exportTo($app['json_parser']), 200, array (
                'Content-Type' => 'application/json',
            ));
        });

        /**
         * Delete a object identified by a given id
         */
        $controllers->delete('/{id}', function ($id) use ($app, $prefix) {
            $query = new $app[$prefix.'query_class'];
            $query->filterByPrimaryKey($id)->delete();

            return new Response('', 204, array (
                'Content-Type' => 'application/json',
            ));
        });

        return $controllers;
    }
}

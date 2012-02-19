<?php

namespace Propilex\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @author William Durand <william.durand1@gmail.com>
 */
class RestControllerProvider implements ServiceProviderInterface
{
    private $modelName;

    public function register(Application $app)
    {
        if (isset($app['rest_controller.model_name'])) {
            $this->modelName = $app['rest_controller.model_name'];
        } else {
            throw new \InvalidArgumentException('You have to configure the "rest_controller.model_name" parameter.');
        }

        if (isset($app['rest_controller.model_class'])) {
            $app['rest_controller.query_class'] = $app['rest_controller.model_class'] . 'Query';
        } else {
            throw new \InvalidArgumentException('You have to configure the "rest_controller.model_class" parameter.');
        }

        /**
         * Returns all objects
         */
        $app->get(sprintf('/%s', $this->modelName), function () use ($app) {
            $query = new $app['rest_controller.query_class'];

            return new Response($query->find()->exportTo($app['json_parser']), 200, array(
                'Content-Type' => 'application/json',
            ));
        });

        /**
         * Returns a specific object identified by a given id
         */
        $app->get(sprintf('/%s/{id}', $this->modelName), function ($id) use ($app) {
            $query  = new $app['rest_controller.query_class'];
            $object = $query->findPk($id);

            if (!$object instanceof $app['rest_controller.model_class']) {
                throw new NotFoundHttpException(
                    sprintf('%s with id "%d" does not exist.', ucfirst($app['rest_controller.model_name']), $id)
                );
            }

            return new Response($object->exportTo($app['json_parser']), 200, array (
                'Content-Type' => 'application/json',
            ));
        });

        /**
         * Create a new object
         */
        $app->post(sprintf('/%s', $this->modelName), function (Request $request) use ($app) {
            $object = new $app['rest_controller.model_class'];
            $object->fromArray($request->request->all());
            $object->save();

            return new Response($object->exportTo($app['json_parser']), 201, array (
                'Content-Type' => 'application/json',
            ));
        });

        /**
         * Update a object identified by a given id
         */
        $app->put(sprintf('/%s/{id}', $this->modelName), function ($id, Request $request) use ($app) {
            $query  = new $app['rest_controller.query_class'];
            $object = $query->findPk($id);

            if (!$object instanceof $app['rest_controller.model_class']) {
                throw new NotFoundHttpException(
                    sprintf('%s with id "%d" does not exist.', ucfirst($app['rest_controller.model_name']), $id)
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
        $app->delete(sprintf('/%s/{id}', $this->modelName), function ($id) use ($app) {
            $query = new $app['rest_controller.query_class'];
            $query->filterByPrimaryKey($id)->delete();

            return new Response('', 204, array (
                'Content-Type' => 'application/json',
            ));
        });
    }
}

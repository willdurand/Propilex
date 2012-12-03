<?php

namespace Propilex\Provider;

use Silex\Application;
use Silex\ControllerCollection;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @author William Durand <william.durand1@gmail.com>
 */
class RestController implements ControllerProviderInterface
{
    private $modelName;

    private $modelClass;

    private $lastModifiedGetter;

    public function __construct($modelName, $modelClass = null, $lastModifiedGetter = null)
    {
        $this->modelName          = $modelName;
        $this->modelClass         = $modelClass;
        $this->lastModifiedGetter = $lastModifiedGetter;
    }

    /**
     * {@inheritdoc}
     */
    public function connect(Application $app)
    {
        $modelName = $this->modelName;
        $prefix    = sprintf('rest_controller.%s.', $this->modelName);

        $pluralizer = new \StandardEnglishPluralizer();
        $modelNamePlural = $pluralizer->getPluralForm($modelName);

        if (null !== $this->modelClass) {
            $app[$prefix.'model_class'] = $this->modelClass;
        }

        if (null !== $this->lastModifiedGetter) {
            $app[$prefix.'last_modified_getter'] = $this->lastModifiedGetter;
        }

        if (isset($app[$prefix.'model_class'])) {
            $app[$prefix.'query_class'] = $app[$prefix.'model_class'] . 'Query';
        } else {
            throw new \InvalidArgumentException(sprintf('You have to configure the "%s.model_class" parameter.', $prefix));
        }

        $controllers = new ControllerCollection($app['route_factory'] );

        /**
         * Returns all objects
         */
        $controllers->get('/', function () use ($app, $prefix, $modelNamePlural) {
            $query = new $app[$prefix.'query_class'];

            return new JsonResponse(array(
                $modelNamePlural => $query->find()->toArray(null, false, \BasePeer::TYPE_FIELDNAME),
            ));
        })->bind($prefix . '_all');

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

            $response = new JsonResponse(array(
                $modelName => $object->toArray(),
            ));

            if (isset($app[$prefix.'last_modified_getter'])) {
                $response->setLastModified($object->$app[$prefix.'last_modified_getter']());
            }

            return $response;
        })->bind($prefix . '_get');

        /**
         * Create a new object
         */
        $controllers->post('/', function (Request $request) use ($app, $prefix, $modelName) {
            $object = new $app[$prefix.'model_class'];
            $object->fromArray($request->request->all(), \BasePeer::TYPE_FIELDNAME);
            $object->save();

            return new JsonResponse(array(
                $modelName => $object->toArray(),
            ));
        })->bind($prefix . '_new');

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

            $object->fromArray($request->request->all(), \BasePeer::TYPE_FIELDNAME);
            $object->save();

            return new JsonResponse(array(
                $modelName => $object->toArray(),
            ));
        })->bind($prefix . '_edit');

        /**
         * Delete a object identified by a given id
         */
        $controllers->delete('/{id}', function ($id) use ($app, $prefix) {
            $query = new $app[$prefix.'query_class'];
            $query->filterByPrimaryKey($id)->delete();

            return new Response(null, 204);
        })->bind($prefix . '_delete');

        return $controllers;
    }
}

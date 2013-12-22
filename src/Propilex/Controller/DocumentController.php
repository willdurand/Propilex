<?php

namespace Propilex\Controller;

use Propilex\Model\Document;
use Propilex\Model\DocumentCollection;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DocumentController
{
    public function listAction(Request $request, Application $app)
    {
        $page  = (int) $request->query->get('page', 1);
        $limit = (int) $request->query->get('limit', 10);

        $pager = $app['document_repository']->paginate($page, $limit);

        $documents = $app['hateoas.pagerfanta_factory']->create(
            $pager,
            'document_list',
            [],
            new DocumentCollection($pager->getCurrentPageResults()),
            true
        );

        return $app['view_handler']->handle($request, $documents);
    }

    public function getAction(Request $request, Application $app, $id)
    {
        $document = $this->findDocument($app, $id);
        $response = $app['view_handler']->handle($request, $document);
        $response->setLastModified($document->getUpdatedAt());

        return $response;
    }

    public function postAction(Request $request, Application $app)
    {
        $document = new Document();
        $document->fromArray($request->request->all(), \BasePeer::TYPE_FIELDNAME);

        if (true !== $errors = $app['document_validator']($document)) {
            return $app['view_handler']->handle($app['request'], $errors, 400);
        }

        $app['document_repository']->add($document);

        return $app['view_handler']->handle($request, $document, 201, [
            'Location' => $app['serializer']->getLinkHref($document, 'self', true),
        ]);
    }

    public function putAction(Request $request, Application $app, $id)
    {
        $document = $this->findDocument($app, $id);
        $document->fromArray($request->request->all(), \BasePeer::TYPE_FIELDNAME);

        if (true !== $errors = $app['document_validator']($document)) {
            return $app['view_handler']->handle($app['request'], $errors, 400);
        }

        $app['document_repository']->add($document);

        return $app['view_handler']->handle($request, $document);
    }

    public function deleteAction(Request $request, Application $app, $id)
    {
        $document = $this->findDocument($app, $id);

        $app['document_repository']->remove($document);

        return new Response(null, 204);
    }

    private function findDocument(Application $app, $id)
    {
        if (null === $document = $app['document_repository']->find($id)) {
            throw new NotFoundHttpException(
                sprintf('Document with id = %d does not exist.', $id)
            );
        }

        return $document;
    }
}

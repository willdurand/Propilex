<?php

namespace Propilex\Controller;

use Propilex\Model\Document;
use Propilex\Model\DocumentCollection;
use Propilex\Response\NoContentResponse;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DocumentController
{
    public function listAction(Request $request, Application $app)
    {
        $pager = $app['document_repository']->paginate(
            (int) $request->query->get('page', 1),
            (int) $request->query->get('limit', 10)
        );

        $documents = $app['hateoas.pagerfanta_factory']->create(
            $pager,
            'document_list',
            [],
            new DocumentCollection($pager->getCurrentPageResults()),
            true
        );

        return $app['view_handler']->handle($documents);
    }

    public function getAction(Application $app, $id)
    {
        $document = $this->findDocument($app, $id);
        $response = $app['view_handler']->handle($document);
        $response->setLastModified($document->getUpdatedAt());

        return $response;
    }

    public function postAction(Request $request, Application $app)
    {
        $document = new Document();
        $document->fromArray($request->request->all(), \BasePeer::TYPE_FIELDNAME);

        if (true !== $errors = $app['document_validator']($document)) {
            return $app['view_handler']->handle($errors, 400);
        }

        $app['document_repository']->add($document);

        return $app['view_handler']->handle($document, 201, [
            'Location' => $app['serializer']->getLinkHref($document, 'self', true),
        ]);
    }

    public function putAction(Request $request, Application $app, $id)
    {
        $document = $this->findDocument($app, $id);
        $document->fromArray($request->request->all(), \BasePeer::TYPE_FIELDNAME);

        if (true !== $errors = $app['document_validator']($document)) {
            return $app['view_handler']->handle($errors, 400);
        }

        $app['document_repository']->add($document);

        return $app['view_handler']->handle($document);
    }

    public function deleteAction(Application $app, $id)
    {
        $document = $this->findDocument($app, $id);

        $app['document_repository']->remove($document);

        return new NoContentResponse();
    }

    private function findDocument(Application $app, $id)
    {
        if (null === $document = $app['document_repository']->find($id)) {
            throw new NotFoundHttpException($app['translator']->trans(
                'document_not_found',
                [ '%id%' => $id, ]
            ));
        }

        return $document;
    }
}
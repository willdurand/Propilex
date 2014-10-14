<?php

namespace Propilex\Controller;

use Hateoas\Configuration\Relation;
use Hateoas\Configuration\Route;
use Hateoas\Representation\CollectionRepresentation;
use Propilex\Model\Document;
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

        $response = new Response();
        $results  = (array) $pager->getCurrentPageResults();

        $response->setPublic();
        $response->setETag($this->computeETag($request, $results));

        if ($response->isNotModified($request)) {
            return $response;
        }

        $documents = $app['hateoas.pagerfanta_factory']->createRepresentation(
            $pager,
            new Route('document_list', [], true),
            new CollectionRepresentation(
                $results,
                'documents',
                null,
                null,
                null,
                [
                    new Relation(
                        "expr(curies_prefix ~ ':documents')",
                        new Route("document_list", [], true)
                    )
                ]
            ),
            true
        );

        return $app['view_handler']->handle($documents, 200, [], $response);
    }

    public function getAction(Request $request, Application $app, $id)
    {
        return $app['view_handler']->handle($this->findDocument($app, $id), 200);
    }

    public function postAction(Request $request, Application $app)
    {
        $document = new Document();
        $values   = $this->filterValues($request);

        $document->fromArray($request->request->all(), \BasePeer::TYPE_FIELDNAME);

        if (true !== $errors = $app['document_validator']($document)) {
            return $app['view_handler']->handle($errors, 400);
        }

        $app['document_repository']->add($document);

        return $app['view_handler']->handle($document, 201, [
            'Location' => $app['serializer']->getLinkHelper()->getLinkHref($document, 'self', true),
        ]);
    }

    public function putAction(Request $request, Application $app, $id)
    {
        $document = $this->findDocument($app, $id);
        $values   = $this->filterValues($request);

        $document->fromArray($values, \BasePeer::TYPE_FIELDNAME);

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

    private function filterValues(Request $request)
    {
        return array_intersect_key(
            $request->request->all(),
            array_flip([ 'title', 'body' ])
        );
    }

    private function computeETag(Request $request, $documents)
    {
        return md5($request->attributes->get('_format') . implode('|', array_map(function ($document) {
            return (string) $document;
        }, $documents)));
    }
}

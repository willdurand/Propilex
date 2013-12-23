<?php

namespace Propilex\Model\Repository;

use Pagerfanta\Adapter\PropelAdapter;
use Pagerfanta\Pagerfanta;
use Propilex\Model\Document;

/**
 * @author William Durand <william.durand1@gmail.com>
 */
class PropelDocumentRepository implements DocumentRepositoryInterface
{
    private $query;

    public function __construct($query)
    {
        $this->query = $query;
    }

    /**
     * {@inheritDoc}
     */
    public function find($id)
    {
        return $this->query->findPk($id);
    }

    /**
     * {@inheritDoc}
     */
    public function findAll()
    {
        return $this->query->find()->getData();
    }

    /**
     * {@inheritDoc}
     */
    public function add(Document $document)
    {
        $document->save();
    }

    /**
     * {@inheritDoc}
     */
    public function remove(Document $document)
    {
        $document->delete();
    }

    /**
     * {@inheritDoc}
     */
    public function paginate($page, $limit)
    {
        $pager = new Pagerfanta(
            new PropelAdapter($this->query)
        );

        return $pager
            ->setMaxPerPage($limit)
            ->setCurrentPage($page);
    }
}

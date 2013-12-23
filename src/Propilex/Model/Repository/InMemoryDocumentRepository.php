<?php

namespace Propilex\Model\Repository;

use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\ArrayAdapter;
use Propilex\Model\Document;

class InMemoryDocumentRepository implements DocumentRepositoryInterface
{
    private $documents;

    public function __construct(array $documents)
    {
        $this->documents = $documents;
    }

    /**
     * {@inheritDoc}
     */
    public function find($id)
    {
        foreach ($this->documents as $document) {
            if ($id == $document->getId()) {
                return $document;
            }
        }

        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function findAll()
    {
        return $this->documents;
    }

    /**
     * {@inheritDoc}
     */
    public function add(Document $document)
    {
        if (null === $id = $document->getId()) {
            $document->setId($id = mt_rand() % 100);
            $document->setCreatedAt(new \DateTime());
        }

        $document->setUpdatedAt(new \DateTime());

        $this->documents[] = $document;
    }

    /**
     * {@inheritDoc}
     */
    public function remove(Document $document)
    {
        foreach ($this->documents as $id => $aDocument) {
            if ($document->isEqualTo($aDocument)) {
                unset($this->documents[$id]);

                break;
            }
        }
    }

    /**
     * {@inheritDoc}
     */
    public function paginate($page, $limit)
    {
        $pager = new Pagerfanta(
            new ArrayAdapter($this->findAll())
        );

        return $pager
            ->setMaxPerPage($limit)
            ->setCurrentPage($page);
    }
}

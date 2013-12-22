<?php

namespace Propilex\Model;

interface DocumentRepositoryInterface
{
    /**
     * @return Document
     */
    public function find($id);

    /**
     * @return Document[]
     */
    public function findAll();

    /**
     * @param Document
     */
    public function add(Document $document);

    /**
     * @param Document $document
     */
    public function remove(Document $document);

    /**
     * @return \Pagerfanta\Pagerfanta
     */
    public function paginate($page, $limit);
}

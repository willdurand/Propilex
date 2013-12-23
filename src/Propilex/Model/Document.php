<?php

namespace Propilex\Model;

use Propilex\Model\om\BaseDocument;

/**
 * @author William Durand <william.durand1@gmail.com>
 */
class Document extends BaseDocument
{
    /**
     * @return boolean
     */
    public function isEqualTo(Document $document)
    {
        return $this->getId() === $document->getId();
    }
}

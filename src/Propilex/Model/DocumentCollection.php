<?php

namespace Propilex\Model;

class DocumentCollection
{
    private $documents;

    public function __construct($documents)
    {
        $this->documents = $documents;
    }
}

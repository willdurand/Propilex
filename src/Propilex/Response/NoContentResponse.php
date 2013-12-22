<?php

namespace Propilex\Response;

use Symfony\Component\HttpFoundation\Response;

class NoContentResponse extends Response
{
    public function __construct()
    {
        parent::__construct('', 204);
    }
}

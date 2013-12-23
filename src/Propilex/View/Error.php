<?php

namespace Propilex\View;

class Error
{
    private $message;

    public function __construct($message)
    {
        $this->message = $message;
    }
}

<?php

namespace Propilex\View;

final class Error
{
    private $message;

    public function __construct($message)
    {
        $this->message = $message;
    }
}

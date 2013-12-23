<?php

namespace Propilex\View;

class FieldError extends Error
{
    private $field;

    public function __construct($field, $message)
    {
        parent::__construct($message);

        $this->field = $field;
    }
}

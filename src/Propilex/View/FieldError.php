<?php

namespace Propilex\View;

final class FieldError
{
    private $field;

    private $message;

    public function __construct($field, $message)
    {
        $this->field   = $field;
        $this->message = $message;
    }
}

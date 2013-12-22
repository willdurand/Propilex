<?php

namespace Propilex\View;

use Symfony\Component\Validator\ConstraintViolationList;

final class FormErrors
{
    private $errors = [];

    public function __construct(ConstraintViolationList $violations)
    {
        foreach ($violations as $violation) {
            $this->errors[] = new FieldError(
                $violation->getPropertyPath(),
                $violation->getMessage()
            );
        }
    }
}

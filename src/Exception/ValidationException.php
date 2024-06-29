<?php

namespace App\Exception;

use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Exception\ValidatorException;

class ValidationException extends ValidatorException
{
    private $violations;

    public function __construct(ConstraintViolationListInterface $violations)
    {
        parent::__construct("Validation failed.");
        $this->violations = $violations;
    }

    public function getViolations(): ConstraintViolationListInterface
    {
        return $this->violations;
    }
}
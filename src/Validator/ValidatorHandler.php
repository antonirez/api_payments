<?php

namespace App\Validator;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;

class ValidatorHandler
{
    private ValidatorInterface $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * Validates an object and throws ValidationFailedException on errors.
     * @param object $object
     * @return array
     */
    public function validate(object $object): array
    {
        $errors = $this->validator->validate($object);
        $result = [];

        foreach ($errors as $violation) {
            $result[$violation->getPropertyPath()] = $violation->getMessage();
        }

        return $result;
    }
}
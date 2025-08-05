<?php

namespace App\Validator\Exception;

/**
 * ValidationException for validator system.
 */
class ValidationException extends \Exception
{
    public function __construct(string $message, int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function getValidationErrors(): ?array
    {
        return unserialize($this->message);
    }
}

<?php

declare(strict_types=1);

namespace App\Exceptions\Validation;

use Exception;

class ValidatorException extends Exception
{
    private readonly array $errors;

    public function __construct(array $errors)
    {
        $this->errors = $errors;
        parent::__construct($this->createMessage());
    }

    private function createMessage(): string
    {
        $message = PHP_EOL. 'Validation errors:'.PHP_EOL;

        foreach ($this->getErrors() as $error) {
            $message .= sprintf("[%s] %s\n", $error['property'], $error['message']);
        }

        return $message;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}

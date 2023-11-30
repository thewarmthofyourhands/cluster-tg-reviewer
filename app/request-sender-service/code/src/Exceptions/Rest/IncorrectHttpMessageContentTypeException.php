<?php

declare(strict_types=1);

namespace App\Exceptions\Rest;

use Throwable;

class IncorrectHttpMessageContentTypeException extends \Exception
{
    public function __construct(string $message = "Incorrect content type header", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

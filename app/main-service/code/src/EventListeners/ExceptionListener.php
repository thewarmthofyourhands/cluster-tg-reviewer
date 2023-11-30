<?php

declare(strict_types=1);

namespace App\EventListeners;

use Eva\Console\Events\ExceptionEvent;
use Psr\Log\LoggerInterface;

readonly class ExceptionListener
{
    public function __construct(private LoggerInterface $logger) {}

    public function __invoke(ExceptionEvent $exceptionEvent): void
    {
        $throwable = $exceptionEvent->getThrowable();
        $message = sprintf(
            '[%s] error code: %s, message: %s, file: %s, line: %s, trace: %s',
            date('Y-m-d H:i:s'),
            $throwable->getCode(),
            $throwable->getMessage(),
            $throwable->getFile(),
            $throwable->getLine(),
            $throwable->getTraceAsString(),
        );
        $this->logger->error($message);
    }
}

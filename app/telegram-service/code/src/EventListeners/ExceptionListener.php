<?php

declare(strict_types=1);

namespace App\EventListeners;

use App\Exceptions\Application\ApplicationException;
use App\Exceptions\TelegramBotException;
use Eva\Console\Events\ExceptionEvent;
use Psr\Log\LoggerInterface;

readonly class ExceptionListener
{
    public function __construct(private LoggerInterface $logger) {}

    public function __invoke(ExceptionEvent $exceptionEvent): void
    {
        $throwable = $exceptionEvent->getThrowable();
        $requestData = '';

        if ($throwable instanceof TelegramBotException) {
            $requestData = $throwable->getTgUpdate();
            $throwable = $throwable->getPrevious();
        }

        $message = sprintf(
            '[%s] error code: %s, message: %s, file: %s, line: %s, trace: %s, requestData: %s',
            date('Y-m-d H:i:s'),
            $throwable->getCode(),
            $throwable->getMessage(),
            $throwable->getFile(),
            $throwable->getLine(),
            $throwable->getTraceAsString(),
            $requestData,
        );
        $this->logger->error($message);
    }
}

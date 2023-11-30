<?php

declare(strict_types=1);

namespace App\Logger;

use App\Services\RequestServices\FluentdRequestService;
use Psr\Log\LoggerInterface;
use Psr\Log\LoggerTrait;

readonly class FluentdLogger implements LoggerInterface
{
    use LoggerTrait;

    public function __construct(
        private FluentdRequestService $fluentdRequestService,
    ) {}

    protected function format(string $level, string $message, array $context = []): string
    {
        return \sprintf('[php %s] %s %s', $level, $message, $this->formatContext($context));
    }

    protected function formatContext(array $context): string
    {
        try {
            return \json_encode($context, \JSON_THROW_ON_ERROR);
        } catch (\JsonException) {
            return \print_r($context, true);
        }
    }

    protected function write(string $message): void
    {
        $this->fluentdRequestService->sendMessage($message);
    }

    public function log($level, \Stringable|string $message, array $context = []): void
    {
        $this->write($this->format((string) $level, $message, $context));
    }
}

<?php

declare(strict_types=1);

namespace App\UseCase\Fluentd;

use App\Services\RequestServices\FluentdRequestService;

readonly class SendMessageHandler
{
    public function __construct(
        private FluentdRequestService $fluentdRequestService
    ) {}

    public function handle(string $message, string $tag): void
    {
        $this->fluentdRequestService->sendMessage($message, $tag);
    }
}

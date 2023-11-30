<?php

declare(strict_types=1);

namespace App\UseCase\Telegram;

use App\Services\RequestServices\TelegramRequestService;

readonly class GetUpdateListHandler
{
    public function __construct(
        private TelegramRequestService $telegramRequestService,
    ) {}

    public function handle(string $token, null|int $ts = null): array
    {
        return $this->telegramRequestService->getUpdates($token, $ts);
    }
}

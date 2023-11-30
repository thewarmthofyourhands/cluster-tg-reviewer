<?php

declare(strict_types=1);

namespace App\UseCase;

use App\Services\Telegram\TelegramBotService;

readonly class GetUpdateListHandler
{
    public function __construct(
        private TelegramBotService $telegramBotService,
    ) {}

    public function handle(): array
    {
        return $this->telegramBotService->fetchUpdateList() ?? [];
    }
}

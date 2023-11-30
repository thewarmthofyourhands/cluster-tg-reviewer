<?php

declare(strict_types=1);

namespace App\UseCase;

use App\Dto\Services\RequestServices\TelegramRequestService\SendMessageDto;
use App\Services\Telegram\TelegramBotService;

readonly class SendMessageToChatHandler
{
    public function __construct(
        private TelegramBotService $telegramBotService,
    ) {}

    public function handle(int $chatId, string $message): void
    {
        $this->telegramBotService->sendMessage(new SendMessageDto(
            $chatId,
            $message,
            null,
            null,
        ));
    }
}

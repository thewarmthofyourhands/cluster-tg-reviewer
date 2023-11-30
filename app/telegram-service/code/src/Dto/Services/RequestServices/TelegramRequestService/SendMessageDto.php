<?php

declare(strict_types=1);

namespace App\Dto\Services\RequestServices\TelegramRequestService;

final readonly class SendMessageDto
{
    public function __construct(
        private int $chatId,
        private string $text,
        private null|array $keyboard,
        private null|array $inlineKeyboard,
    ) {}

    public function getChatId(): int
    {
        return $this->chatId;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function getKeyboard(): null|array
    {
        return $this->keyboard;
    }

    public function getInlineKeyboard(): null|array
    {
        return $this->inlineKeyboard;
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}

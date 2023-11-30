<?php

declare(strict_types=1);

namespace App\Dto\Services\RequestServices\Telegram;

final readonly class SendMessageDto
{
    public function __construct(
        private int $chat_id,
        private string $text,
        private array $reply_markup,
    ) {}

    public function getChatId(): int
    {
        return $this->chat_id;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function getReplyMarkup(): array
    {
        return $this->reply_markup;
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}

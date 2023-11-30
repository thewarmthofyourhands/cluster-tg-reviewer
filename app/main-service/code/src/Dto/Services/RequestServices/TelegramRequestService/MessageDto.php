<?php

declare(strict_types=1);

namespace App\Dto\Services\RequestServices\TelegramRequestService;

final readonly class MessageDto
{
    public function __construct(
        private int $message_id,
        private int $date,
        private array $from,
        private array $chat,
        private string $text,
    ) {}

    public function getMessageId(): int
    {
        return $this->message_id;
    }

    public function getDate(): int
    {
        return $this->date;
    }

    public function getFrom(): array
    {
        return $this->from;
    }

    public function getChat(): array
    {
        return $this->chat;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}

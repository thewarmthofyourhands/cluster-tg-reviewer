<?php

declare(strict_types=1);

namespace App\Dto\Infrastructure;

final readonly class InputMessageTelegramDto
{
    public function __construct(
        private int $chatId,
        private string $chatType,
        private \App\Dto\UseCase\TelegramUserDto $telegramUser,
        private null|string $text,
        private \App\Dto\Infrastructure\TelegramActionDataDto $data,
    ) {}

    public function getChatId(): int
    {
        return $this->chatId;
    }

    public function getChatType(): string
    {
        return $this->chatType;
    }

    public function getTelegramUser(): \App\Dto\UseCase\TelegramUserDto
    {
        return $this->telegramUser;
    }

    public function getText(): null|string
    {
        return $this->text;
    }

    public function getData(): \App\Dto\Infrastructure\TelegramActionDataDto
    {
        return $this->data;
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}

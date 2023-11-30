<?php

declare(strict_types=1);

namespace App\Dto\Services\Telegram\TelegramUserService;

final readonly class TelegramUserDto
{
    public function __construct(
        private int $id,
        private int $telegramId,
        private string $username,
        private string $data,
    ) {}

    public function getId(): int
    {
        return $this->id;
    }

    public function getTelegramId(): int
    {
        return $this->telegramId;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getData(): string
    {
        return $this->data;
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}

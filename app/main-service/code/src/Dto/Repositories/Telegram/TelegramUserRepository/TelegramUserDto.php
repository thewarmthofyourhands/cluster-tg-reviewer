<?php

declare(strict_types=1);

namespace App\Dto\Repositories\Telegram\TelegramUserRepository;

final readonly class TelegramUserDto
{
    public function __construct(
        private int $id,
        private int $tgId,
        private string $nickname,
        private string $data,
    ) {}

    public function getId(): int
    {
        return $this->id;
    }

    public function getTgId(): int
    {
        return $this->tgId;
    }

    public function getNickname(): string
    {
        return $this->nickname;
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

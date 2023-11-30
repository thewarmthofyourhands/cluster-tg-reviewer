<?php

declare(strict_types=1);

namespace App\Dto\Repositories\AdminRepository;

final readonly class AddAdminDto
{
    public function __construct(
        private string $nickname,
        private int $messengerId,
        private \App\Enums\MessengerTypeEnum $messengerType,
    ) {}

    public function getNickname(): string
    {
        return $this->nickname;
    }

    public function getMessengerId(): int
    {
        return $this->messengerId;
    }

    public function getMessengerType(): \App\Enums\MessengerTypeEnum
    {
        return $this->messengerType;
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}

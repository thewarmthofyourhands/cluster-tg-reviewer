<?php

declare(strict_types=1);

namespace App\Dto\UseCase;

final readonly class AdminDto
{
    public function __construct(
        private int $id,
        private string $nickname,
        private int $messengerId,
        private \App\Enums\MessengerTypeEnum $messengerType,
    ) {}

    public function getId(): int
    {
        return $this->id;
    }

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

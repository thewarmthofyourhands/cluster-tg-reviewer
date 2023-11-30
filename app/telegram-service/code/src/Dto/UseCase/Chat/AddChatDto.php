<?php

declare(strict_types=1);

namespace App\Dto\UseCase\Chat;

final readonly class AddChatDto
{
    public function __construct(
        private int $telegramUserId,
        private int $projectId,
        private int $messengerId,
    ) {}

    public function getTelegramUserId(): int
    {
        return $this->telegramUserId;
    }

    public function getProjectId(): int
    {
        return $this->projectId;
    }

    public function getMessengerId(): int
    {
        return $this->messengerId;
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}

<?php

declare(strict_types=1);

namespace App\Dto\UseCase\Credentials;

final readonly class AddCredentialDto
{
    public function __construct(
        private int $telegramUserId,
        private int $projectId,
        private string $token,
        private string $dateExpired,
    ) {}

    public function getTelegramUserId(): int
    {
        return $this->telegramUserId;
    }

    public function getProjectId(): int
    {
        return $this->projectId;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getDateExpired(): string
    {
        return $this->dateExpired;
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}

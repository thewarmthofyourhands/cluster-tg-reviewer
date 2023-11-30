<?php

declare(strict_types=1);

namespace App\Dto\Services\CredentialService;

final readonly class CredentialDto
{
    public function __construct(
        private int $id,
        private int $projectId,
        private string $token,
        private \Datetime $dateExpired,
        private bool $isRequestWorkable,
    ) {}

    public function getId(): int
    {
        return $this->id;
    }

    public function getProjectId(): int
    {
        return $this->projectId;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getDateExpired(): \Datetime
    {
        return $this->dateExpired;
    }

    public function getIsRequestWorkable(): bool
    {
        return $this->isRequestWorkable;
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}

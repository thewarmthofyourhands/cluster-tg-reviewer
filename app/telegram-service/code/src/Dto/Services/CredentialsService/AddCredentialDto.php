<?php

declare(strict_types=1);

namespace App\Dto\Services\CredentialsService;

final readonly class AddCredentialDto
{
    public function __construct(
        private int $adminId,
        private int $projectId,
        private string $token,
        private string $dateExpired,
    ) {}

    public function getAdminId(): int
    {
        return $this->adminId;
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

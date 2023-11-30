<?php

declare(strict_types=1);

namespace App\Dto\Repositories\DeveloperRepository;

final readonly class AddDeveloperDto
{
    public function __construct(
        private int $projectId,
        private string $nickname,
        private bool $isAdmin,
        private \App\Enums\DeveloperStatusEnum $status,
    ) {}

    public function getProjectId(): int
    {
        return $this->projectId;
    }

    public function getNickname(): string
    {
        return $this->nickname;
    }

    public function getIsAdmin(): bool
    {
        return $this->isAdmin;
    }

    public function getStatus(): \App\Enums\DeveloperStatusEnum
    {
        return $this->status;
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}

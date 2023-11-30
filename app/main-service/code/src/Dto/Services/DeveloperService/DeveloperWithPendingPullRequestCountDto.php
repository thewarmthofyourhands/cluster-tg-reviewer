<?php

declare(strict_types=1);

namespace App\Dto\Services\DeveloperService;

final readonly class DeveloperWithPendingPullRequestCountDto
{
    public function __construct(
        private int $id,
        private int $projectId,
        private string $nickname,
        private bool $isAdmin,
        private \App\Enums\DeveloperStatusEnum $status,
        private int $pullRequestCount,
    ) {}

    public function getId(): int
    {
        return $this->id;
    }

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

    public function getPullRequestCount(): int
    {
        return $this->pullRequestCount;
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}

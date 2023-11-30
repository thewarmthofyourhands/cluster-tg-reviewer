<?php

declare(strict_types=1);

namespace App\Dto\Repositories\PullRequestRepository;

final readonly class ChangeToPendingStatusPullRequestDto
{
    public function __construct(
        private int $id,
        private int $projectId,
        private \App\Enums\PullRequestStatusEnum $status,
        private string $lastPendingDate,
    ) {}

    public function getId(): int
    {
        return $this->id;
    }

    public function getProjectId(): int
    {
        return $this->projectId;
    }

    public function getStatus(): \App\Enums\PullRequestStatusEnum
    {
        return $this->status;
    }

    public function getLastPendingDate(): string
    {
        return $this->lastPendingDate;
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}

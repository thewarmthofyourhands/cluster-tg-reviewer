<?php

declare(strict_types=1);

namespace App\Dto\UseCase\PullRequests;

final readonly class PullRequestDto
{
    public function __construct(
        private int $id,
        private int $projectId,
        private null|int $developerId,
        private int $pullRequestNumber,
        private string $title,
        private string $branch,
        private \App\Enums\UseCase\PullRequests\PullRequestStatusEnum $status,
        private null|string $lastPendingDate,
    ) {}

    public function getId(): int
    {
        return $this->id;
    }

    public function getProjectId(): int
    {
        return $this->projectId;
    }

    public function getDeveloperId(): null|int
    {
        return $this->developerId;
    }

    public function getPullRequestNumber(): int
    {
        return $this->pullRequestNumber;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getBranch(): string
    {
        return $this->branch;
    }

    public function getStatus(): \App\Enums\UseCase\PullRequests\PullRequestStatusEnum
    {
        return $this->status;
    }

    public function getLastPendingDate(): null|string
    {
        return $this->lastPendingDate;
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}

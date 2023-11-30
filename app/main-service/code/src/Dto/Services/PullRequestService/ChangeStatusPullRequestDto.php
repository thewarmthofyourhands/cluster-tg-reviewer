<?php

declare(strict_types=1);

namespace App\Dto\Services\PullRequestService;

final readonly class ChangeStatusPullRequestDto
{
    public function __construct(
        private int $id,
        private int $projectId,
        private \App\Enums\PullRequestStatusEnum $status,
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

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}

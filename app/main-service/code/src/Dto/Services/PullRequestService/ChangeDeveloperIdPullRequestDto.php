<?php

declare(strict_types=1);

namespace App\Dto\Services\PullRequestService;

final readonly class ChangeDeveloperIdPullRequestDto
{
    public function __construct(
        private int $id,
        private int $projectId,
        private null|int $developerId,
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

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}

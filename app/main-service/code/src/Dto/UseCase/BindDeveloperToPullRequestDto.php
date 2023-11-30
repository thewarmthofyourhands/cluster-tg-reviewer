<?php

declare(strict_types=1);

namespace App\Dto\UseCase;

final readonly class BindDeveloperToPullRequestDto
{
    public function __construct(
        private int $adminId,
        private int $projectId,
        private int $developerId,
        private int $id,
    ) {}

    public function getAdminId(): int
    {
        return $this->adminId;
    }

    public function getProjectId(): int
    {
        return $this->projectId;
    }

    public function getDeveloperId(): int
    {
        return $this->developerId;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}

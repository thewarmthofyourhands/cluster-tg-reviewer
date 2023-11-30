<?php

declare(strict_types=1);

namespace App\Dto\Services\DeveloperService;

final readonly class ChangeDeveloperStatusDto
{
    public function __construct(
        private int $adminId,
        private int $projectId,
        private int $id,
        private \App\Enums\Services\DeveloperService\DeveloperStatusEnum $status,
    ) {}

    public function getAdminId(): int
    {
        return $this->adminId;
    }

    public function getProjectId(): int
    {
        return $this->projectId;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getStatus(): \App\Enums\Services\DeveloperService\DeveloperStatusEnum
    {
        return $this->status;
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}

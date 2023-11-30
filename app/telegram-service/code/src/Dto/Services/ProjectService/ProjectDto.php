<?php

declare(strict_types=1);

namespace App\Dto\Services\ProjectService;

final readonly class ProjectDto
{
    public function __construct(
        private int $adminId,
        private int $id,
        private string $name,
        private \App\Enums\Services\ProjectService\ProjectStatusEnum $projectStatus,
        private string $gitRepositoryUrl,
        private \App\Enums\Services\ProjectService\GitServiceTypeEnum $gitType,
        private \App\Enums\Services\ProjectService\ReviewTypeEnum $reviewType,
    ) {}

    public function getAdminId(): int
    {
        return $this->adminId;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getProjectStatus(): \App\Enums\Services\ProjectService\ProjectStatusEnum
    {
        return $this->projectStatus;
    }

    public function getGitRepositoryUrl(): string
    {
        return $this->gitRepositoryUrl;
    }

    public function getGitType(): \App\Enums\Services\ProjectService\GitServiceTypeEnum
    {
        return $this->gitType;
    }

    public function getReviewType(): \App\Enums\Services\ProjectService\ReviewTypeEnum
    {
        return $this->reviewType;
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}

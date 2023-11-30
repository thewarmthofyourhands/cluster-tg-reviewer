<?php

declare(strict_types=1);

namespace App\Dto\UseCase;

final readonly class ProjectDto
{
    public function __construct(
        private int $id,
        private int $adminId,
        private string $name,
        private \App\Enums\ProjectStatusEnum $projectStatus,
        private string $gitRepositoryUrl,
        private \App\Enums\GitServiceTypeEnum $gitType,
        private \App\Enums\ReviewTypeEnum $reviewType,
    ) {}

    public function getId(): int
    {
        return $this->id;
    }

    public function getAdminId(): int
    {
        return $this->adminId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getProjectStatus(): \App\Enums\ProjectStatusEnum
    {
        return $this->projectStatus;
    }

    public function getGitRepositoryUrl(): string
    {
        return $this->gitRepositoryUrl;
    }

    public function getGitType(): \App\Enums\GitServiceTypeEnum
    {
        return $this->gitType;
    }

    public function getReviewType(): \App\Enums\ReviewTypeEnum
    {
        return $this->reviewType;
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}

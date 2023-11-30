<?php

declare(strict_types=1);

namespace App\Dto\UseCase\Projects;

final readonly class ProjectDto
{
    public function __construct(
        private int $adminId,
        private int $id,
        private string $name,
        private \App\Enums\UseCase\Projects\ProjectStatusEnum $projectStatus,
        private string $gitRepositoryUrl,
        private \App\Enums\UseCase\Projects\GitServiceTypeEnum $gitType,
        private \App\Enums\UseCase\Projects\ReviewTypeEnum $reviewType,
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

    public function getProjectStatus(): \App\Enums\UseCase\Projects\ProjectStatusEnum
    {
        return $this->projectStatus;
    }

    public function getGitRepositoryUrl(): string
    {
        return $this->gitRepositoryUrl;
    }

    public function getGitType(): \App\Enums\UseCase\Projects\GitServiceTypeEnum
    {
        return $this->gitType;
    }

    public function getReviewType(): \App\Enums\UseCase\Projects\ReviewTypeEnum
    {
        return $this->reviewType;
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}

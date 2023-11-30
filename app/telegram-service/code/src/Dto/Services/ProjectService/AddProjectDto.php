<?php

declare(strict_types=1);

namespace App\Dto\Services\ProjectService;

final readonly class AddProjectDto
{
    public function __construct(
        private int $adminId,
        private string $name,
        private string $gitRepositoryUrl,
        private \App\Enums\Services\ProjectService\GitServiceTypeEnum $gitType,
        private \App\Enums\Services\ProjectService\ReviewTypeEnum $reviewType,
    ) {}

    public function getAdminId(): int
    {
        return $this->adminId;
    }

    public function getName(): string
    {
        return $this->name;
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

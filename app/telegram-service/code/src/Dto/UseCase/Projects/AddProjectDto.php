<?php

declare(strict_types=1);

namespace App\Dto\UseCase\Projects;

final readonly class AddProjectDto
{
    public function __construct(
        private int $telegramUserId,
        private string $name,
        private string $gitRepositoryUrl,
        private \App\Enums\UseCase\Projects\GitServiceTypeEnum $gitType,
        private \App\Enums\UseCase\Projects\ReviewTypeEnum $reviewType,
    ) {}

    public function getTelegramUserId(): int
    {
        return $this->telegramUserId;
    }

    public function getName(): string
    {
        return $this->name;
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

<?php

declare(strict_types=1);

namespace App\Dto\UseCase\GitHub;

final readonly class PullRequestWithStatusDto
{
    public function __construct(
        private string $branch,
        private string $title,
        private int $number,
        private string $repositoryName,
        private string $repositoryFullName,
        private \App\Enums\GitHub\PullRequestStatusEnum $status,
    ) {}

    public function getBranch(): string
    {
        return $this->branch;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getNumber(): int
    {
        return $this->number;
    }

    public function getRepositoryName(): string
    {
        return $this->repositoryName;
    }

    public function getRepositoryFullName(): string
    {
        return $this->repositoryFullName;
    }

    public function getStatus(): \App\Enums\GitHub\PullRequestStatusEnum
    {
        return $this->status;
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}

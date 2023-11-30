<?php

declare(strict_types=1);

namespace App\Dto\Services\GitService;

final readonly class RemotePullRequestDto
{
    public function __construct(
        private string $branch,
        private string $title,
        private int $id,
        private string $repositoryName,
        private string $repositoryFullName,
        private \App\Enums\PullRequestStatusEnum $status,
    ) {}

    public function getBranch(): string
    {
        return $this->branch;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getRepositoryName(): string
    {
        return $this->repositoryName;
    }

    public function getRepositoryFullName(): string
    {
        return $this->repositoryFullName;
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

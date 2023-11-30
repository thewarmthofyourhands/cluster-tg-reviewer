<?php

declare(strict_types=1);

namespace App\Dto\Repositories\ProjectRepository;

final readonly class GetProjectByGitRepositoryUrlDto
{
    public function __construct(
        private int $adminId,
        private string $gitRepositoryUrl,
    ) {}

    public function getAdminId(): int
    {
        return $this->adminId;
    }

    public function getGitRepositoryUrl(): string
    {
        return $this->gitRepositoryUrl;
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}

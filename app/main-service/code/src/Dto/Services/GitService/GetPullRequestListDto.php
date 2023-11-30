<?php

declare(strict_types=1);

namespace App\Dto\Services\GitService;

final readonly class GetPullRequestListDto
{
    public function __construct(
        private string $gitRepositoryUrl,
        private string $token,
    ) {}

    public function getGitRepositoryUrl(): string
    {
        return $this->gitRepositoryUrl;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}

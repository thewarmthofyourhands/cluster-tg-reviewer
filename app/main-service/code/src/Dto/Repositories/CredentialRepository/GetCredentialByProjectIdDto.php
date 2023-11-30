<?php

declare(strict_types=1);

namespace App\Dto\Repositories\CredentialRepository;

final readonly class GetCredentialByProjectIdDto
{
    public function __construct(
        private int $projectId,
    ) {}

    public function getProjectId(): int
    {
        return $this->projectId;
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}

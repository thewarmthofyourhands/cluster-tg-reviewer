<?php

declare(strict_types=1);

namespace App\Dto\UseCase;

final readonly class GetChatByProjectIdDto
{
    public function __construct(
        private int $adminId,
        private int $projectId,
    ) {}

    public function getAdminId(): int
    {
        return $this->adminId;
    }

    public function getProjectId(): int
    {
        return $this->projectId;
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}

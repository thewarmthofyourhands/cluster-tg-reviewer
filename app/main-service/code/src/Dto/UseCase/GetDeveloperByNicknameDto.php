<?php

declare(strict_types=1);

namespace App\Dto\UseCase;

final readonly class GetDeveloperByNicknameDto
{
    public function __construct(
        private int $adminId,
        private int $projectId,
        private string $nickname,
    ) {}

    public function getAdminId(): int
    {
        return $this->adminId;
    }

    public function getProjectId(): int
    {
        return $this->projectId;
    }

    public function getNickname(): string
    {
        return $this->nickname;
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}

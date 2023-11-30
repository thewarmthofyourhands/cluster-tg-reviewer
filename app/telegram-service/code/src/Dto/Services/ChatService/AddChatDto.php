<?php

declare(strict_types=1);

namespace App\Dto\Services\ChatService;

final readonly class AddChatDto
{
    public function __construct(
        private int $adminId,
        private int $projectId,
        private int $messengerId,
    ) {}

    public function getAdminId(): int
    {
        return $this->adminId;
    }

    public function getProjectId(): int
    {
        return $this->projectId;
    }

    public function getMessengerId(): int
    {
        return $this->messengerId;
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}

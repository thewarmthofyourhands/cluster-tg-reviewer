<?php

declare(strict_types=1);

namespace App\Dto\UseCase\Developers;

final readonly class ChangeDeveloperStatusDto
{
    public function __construct(
        private int $telegramUserId,
        private int $projectId,
        private int $id,
        private \App\Enums\UseCase\Developers\DeveloperStatusEnum $status,
    ) {}

    public function getTelegramUserId(): int
    {
        return $this->telegramUserId;
    }

    public function getProjectId(): int
    {
        return $this->projectId;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getStatus(): \App\Enums\UseCase\Developers\DeveloperStatusEnum
    {
        return $this->status;
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}

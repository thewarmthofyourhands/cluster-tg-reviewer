<?php

declare(strict_types=1);

namespace App\Dto\Services\DeveloperService;

final readonly class GetDeveloperByIdDto
{
    public function __construct(
        private int $projectId,
        private int $id,
    ) {}

    public function getProjectId(): int
    {
        return $this->projectId;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}

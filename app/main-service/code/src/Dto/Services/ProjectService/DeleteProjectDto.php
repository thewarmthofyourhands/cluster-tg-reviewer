<?php

declare(strict_types=1);

namespace App\Dto\Services\ProjectService;

final readonly class DeleteProjectDto
{
    public function __construct(
        private int $adminId,
        private int $id,
    ) {}

    public function getAdminId(): int
    {
        return $this->adminId;
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

<?php

declare(strict_types=1);

namespace App\Dto\UseCase;

final readonly class GetProjectByNameDto
{
    public function __construct(
        private int $adminId,
        private string $name,
    ) {}

    public function getAdminId(): int
    {
        return $this->adminId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}

<?php

declare(strict_types=1);

namespace App\Dto\UseCase;

final readonly class GetProjectListDto
{
    public function __construct(
        private int $adminId,
    ) {}

    public function getAdminId(): int
    {
        return $this->adminId;
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}

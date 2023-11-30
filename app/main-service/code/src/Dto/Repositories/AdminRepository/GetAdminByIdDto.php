<?php

declare(strict_types=1);

namespace App\Dto\Repositories\AdminRepository;

final readonly class GetAdminByIdDto
{
    public function __construct(
        private int $id,
    ) {}

    public function getId(): int
    {
        return $this->id;
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}

<?php

declare(strict_types=1);

namespace App\Dto\Services\AdminService;

final readonly class AddAdminDto
{
    public function __construct(
        private string $nickname,
        private int $messengerId,
    ) {}

    public function getNickname(): string
    {
        return $this->nickname;
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

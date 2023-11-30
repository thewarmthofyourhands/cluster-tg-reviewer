<?php

declare(strict_types=1);

namespace App\Dto\UseCase\GitHub;

final readonly class CheckTokenDto
{
    public function __construct(
        private string $token,
        private string $repositoryFullName,
    ) {}

    public function getToken(): string
    {
        return $this->token;
    }

    public function getRepositoryFullName(): string
    {
        return $this->repositoryFullName;
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}

<?php

declare(strict_types=1);

namespace App\UseCase;

use App\Dto\UseCase\AddDeveloperDto;
use App\Interfaces\Services\AdminServiceInterface;
use App\Interfaces\Services\DeveloperServiceInterface;

class AddDeveloperHandler
{
    public function __construct(
        private readonly DeveloperServiceInterface $developerService,
        private readonly AdminServiceInterface $adminService,
    ) {}

    public function handle(AddDeveloperDto $dto): void
    {
        $adminDto = $this->adminService->auth($dto->getAdminId());
        $this->developerService->addDeveloper(new \App\Dto\Services\DeveloperService\AddDeveloperDto(
            $dto->getProjectId(),
            $dto->getNickname(),
            $dto->getIsAdmin(),
            $dto->getStatus(),
        ));
    }
}

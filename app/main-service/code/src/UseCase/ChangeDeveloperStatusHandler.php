<?php

declare(strict_types=1);

namespace App\UseCase;

use App\Dto\UseCase\ChangeDeveloperStatusDto;
use App\Interfaces\Services\AdminServiceInterface;
use App\Interfaces\Services\DeveloperServiceInterface;

class ChangeDeveloperStatusHandler
{
    public function __construct(
        private readonly DeveloperServiceInterface $developerService,
        private readonly AdminServiceInterface $adminService,
    ) {}

    public function handle(ChangeDeveloperStatusDto $dto): void
    {
        $adminDto = $this->adminService->auth($dto->getAdminId());
        $this->developerService->changeDeveloperStatus(new \App\Dto\Services\DeveloperService\ChangeDeveloperStatusDto(
            $dto->getProjectId(),
            $dto->getId(),
            $dto->getStatus(),
        ));
    }
}

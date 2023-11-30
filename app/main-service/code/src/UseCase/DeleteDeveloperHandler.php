<?php

declare(strict_types=1);

namespace App\UseCase;

use App\Dto\UseCase\DeleteDeveloperDto;
use App\Interfaces\Services\AdminServiceInterface;
use App\Interfaces\Services\DeveloperServiceInterface;

readonly class DeleteDeveloperHandler
{
    public function __construct(
        private DeveloperServiceInterface $developerService,
        private AdminServiceInterface $adminService,
    ) {}

    public function handle(DeleteDeveloperDto $dto): void
    {
        $this->adminService->auth($dto->getAdminId());
        $this->developerService->deleteDeveloper(new \App\Dto\Services\DeveloperService\DeleteDeveloperDto(
            $dto->getProjectId(),
            $dto->getId(),
        ));
    }
}

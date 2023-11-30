<?php

declare(strict_types=1);

namespace App\UseCase;

use App\Dto\UseCase\DeleteProjectDto;
use App\Interfaces\Services\AdminServiceInterface;
use App\Interfaces\Services\ProjectServiceInterface;

readonly class DeleteProjectHandler
{
    public function __construct(
        private AdminServiceInterface $adminService,
        private ProjectServiceInterface $projectService,
    ) {}

    public function handle(DeleteProjectDto $dto): void
    {
        $this->adminService->auth($dto->getAdminId());
        $this->projectService->deleteProject(new \App\Dto\Services\ProjectService\DeleteProjectDto(
            $dto->getAdminId(),
            $dto->getId(),
        ));
    }
}

<?php

declare(strict_types=1);

namespace App\UseCase;

use App\Dto\UseCase\AddProjectDto;
use App\Interfaces\Services\AdminServiceInterface;
use App\Interfaces\Services\ProjectServiceInterface;

readonly class AddProjectHandler
{
    public function __construct(
        private AdminServiceInterface $adminService,
        private ProjectServiceInterface $projectService,
    ) {}

    public function handle(AddProjectDto $dto): void
    {
        $adminDto = $this->adminService->auth($dto->getAdminId());
        $this->projectService->addProject(new \App\Dto\Services\ProjectService\AddProjectDto(
            $dto->getAdminId(),
            $dto->getName(),
            $dto->getGitRepositoryUrl(),
            $dto->getGitType(),
            $dto->getReviewType(),
        ), $adminDto);
    }
}

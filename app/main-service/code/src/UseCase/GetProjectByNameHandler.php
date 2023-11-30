<?php

declare(strict_types=1);

namespace App\UseCase;

use App\Dto\UseCase\GetProjectByNameDto;
use App\Dto\UseCase\ProjectDto;
use App\Interfaces\Services\AdminServiceInterface;
use App\Interfaces\Services\ProjectServiceInterface;

readonly class GetProjectByNameHandler
{
    public function __construct(
        private AdminServiceInterface $adminService,
        private ProjectServiceInterface $projectService,
    ) {}

    public function handle(GetProjectByNameDto $dto): null|ProjectDto
    {
        $this->adminService->getAdminById($dto->getAdminId());
        $res = $this->projectService->getProjectByName(new \App\Dto\Services\ProjectService\GetProjectByNameDto(
            $dto->getAdminId(),
            $dto->getName(),
        ));
        if (null === $res) {
            return null;
        }

        return new ProjectDto(
            $res->getId(),
            $res->getAdminId(),
            $res->getName(),
            $res->getProjectStatus(),
            $res->getGitRepositoryUrl(),
            $res->getGitType(),
            $res->getReviewType(),
        );
    }
}

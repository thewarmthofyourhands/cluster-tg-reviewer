<?php

declare(strict_types=1);

namespace App\UseCase;

use App\Dto\UseCase\GetProjectByGitRepositoryUrlDto;
use App\Dto\UseCase\ProjectDto;
use App\Interfaces\Services\AdminServiceInterface;
use App\Interfaces\Services\ProjectServiceInterface;

readonly class GetProjectByGitRepositoryUrlHandler
{
    public function __construct(
        private AdminServiceInterface $adminService,
        private ProjectServiceInterface $projectService,
    ) {}

    public function handle(GetProjectByGitRepositoryUrlDto $dto): null|ProjectDto
    {
        $this->adminService->getAdminById($dto->getAdminId());
        $res = $this->projectService->getProjectByGitRepositoryUrl(new \App\Dto\Services\ProjectService\GetProjectByGitRepositoryUrlDto(
            $dto->getAdminId(),
            $dto->getGitRepositoryUrl(),
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

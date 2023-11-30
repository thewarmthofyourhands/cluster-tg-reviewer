<?php

declare(strict_types=1);

namespace App\UseCase;

use App\Dto\UseCase\GetProjectListDto;
use App\Dto\UseCase\ProjectDto;
use App\Interfaces\Services\AdminServiceInterface;
use App\Interfaces\Services\ProjectServiceInterface;

readonly class GetProjectListHandler
{
    public function __construct(
        private AdminServiceInterface $adminService,
        private ProjectServiceInterface $projectService,
    ) {}

    public function handle(GetProjectListDto $dto): array
    {
        $this->adminService->auth($dto->getAdminId());
        $res = $this->projectService->getProjectList(new \App\Dto\Services\ProjectService\GetProjectListDto(
            $dto->getAdminId(),
        ));

        return array_map(static fn(\App\Dto\Services\ProjectService\ProjectDto $dto) => new ProjectDto(
            $dto->getId(),
            $dto->getAdminId(),
            $dto->getName(),
            $dto->getProjectStatus(),
            $dto->getGitRepositoryUrl(),
            $dto->getGitType(),
            $dto->getReviewType(),
        ), $res);
    }
}

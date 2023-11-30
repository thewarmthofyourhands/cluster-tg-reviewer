<?php

declare(strict_types=1);

namespace App\UseCase;

use App\Dto\UseCase\GetProjectDto;
use App\Dto\UseCase\ProjectDto;
use App\Exceptions\Application\ApplicationErrorCodeEnum;
use App\Exceptions\Application\ApplicationException;
use App\Interfaces\Services\AdminServiceInterface;
use App\Interfaces\Services\ProjectServiceInterface;

readonly class GetProjectHandler
{
    public function __construct(
        private AdminServiceInterface $adminService,
        private ProjectServiceInterface $projectService,
    ) {}

    /**
     * @throws ApplicationException
     */
    public function handle(GetProjectDto $dto): ProjectDto
    {
        $this->adminService->auth($dto->getAdminId());
        $res = $this->projectService->getProject(new \App\Dto\Services\ProjectService\GetProjectDto(
            $dto->getId(),
        ));

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

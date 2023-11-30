<?php

declare(strict_types=1);

namespace App\UseCase\Projects;

use App\Dto\UseCase\Projects\AddProjectDto;
use App\Mappers\Dto\Services\ProjectService\AddProjectDtoMapper;
use App\Services\AdminService;
use App\Services\ProjectService;

readonly class AddProjectHandler
{
    public function __construct(
        private AdminService $adminService,
        private ProjectService $projectService,
    ) {}

    public function handle(AddProjectDto $dto): void
    {
        $adminDto = $this->adminService->login($dto->getTelegramUserId());
        $this->projectService->store(new \App\Dto\Services\ProjectService\AddProjectDto(
            $adminDto->getId(),
            $dto->getName(),
            $dto->getGitRepositoryUrl(),
            AddProjectDtoMapper::getGitTypeFromMessage($dto->getGitType()),
            AddProjectDtoMapper::getReviewTypeFromMessage($dto->getReviewType()),
        ));
    }
}

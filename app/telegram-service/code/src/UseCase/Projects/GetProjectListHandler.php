<?php

declare(strict_types=1);

namespace App\UseCase\Projects;

use App\Dto\UseCase\Projects\ProjectDto;
use App\Enums\Services\ProjectService\ProjectStatusEnum;
use App\Mappers\Dto\UseCase\Projects\ProjectDtoMapper;
use App\Services\AdminService;
use App\Services\ProjectService;

readonly class GetProjectListHandler
{
    public function __construct(
        private ProjectService $projectService,
        private AdminService $adminService,
    ) {}

    public function handle(int $telegramUserId): array
    {
        $adminDto = $this->adminService->login($telegramUserId);
        $projectDtoList = $this->projectService->index($adminDto->getId());

        return ProjectDtoMapper::convertServiceDtoListToUseCaseDtoList($projectDtoList);
    }
}

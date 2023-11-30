<?php

declare(strict_types=1);

namespace App\UseCase\Projects;

use App\Dto\UseCase\Projects\ProjectDto;
use App\Mappers\Dto\UseCase\Projects\ProjectDtoMapper;
use App\Services\AdminService;
use App\Services\ProjectService;

readonly class GetProjectByIdHandler
{
    public function __construct(
        private ProjectService $projectService,
        private AdminService $adminService,
    ) {}

    public function handle(int $id, int $telegramUserId): ProjectDto
    {
        $adminDto = $this->adminService->login($telegramUserId);
        $projectDto = $this->projectService->show($id, $adminDto->getId());

        return ProjectDtoMapper::convertServiceDtoToUseCaseDto($projectDto);
    }
}

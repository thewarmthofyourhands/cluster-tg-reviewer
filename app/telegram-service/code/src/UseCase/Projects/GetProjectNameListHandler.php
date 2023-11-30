<?php

declare(strict_types=1);

namespace App\UseCase\Projects;

use App\Services\AdminService;
use App\Services\ProjectService;

readonly class GetProjectNameListHandler
{
    public function __construct(
        private ProjectService $projectService,
        private AdminService $adminService,
    ) {}

    public function handle(int $telegramUserId): array
    {
        $adminDto = $this->adminService->login($telegramUserId);
        $projectDtoList = $this->projectService->index($adminDto->getId());

        return array_map(static fn(
            \App\Dto\Services\ProjectService\ProjectDto $dto,
        ) => $dto->getName(), $projectDtoList);
    }
}

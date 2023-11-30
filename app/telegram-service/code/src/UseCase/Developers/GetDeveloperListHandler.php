<?php

declare(strict_types=1);

namespace App\UseCase\Developers;

use App\Mappers\Dto\UseCase\Developers\DeveloperDtoMapper;
use App\Services\AdminService;
use App\Services\DeveloperService;

readonly class GetDeveloperListHandler
{
    public function __construct(
        private DeveloperService $developerService,
        private AdminService $adminService,
    ) {}

    public function handle(int $projectId, int $telegramUserId): array
    {
        $adminDto = $this->adminService->login($telegramUserId);
        $dtoList = $this->developerService->index($projectId, $adminDto->getId());

        return DeveloperDtoMapper::convertServiceDtoListToUseCaseDtoList($dtoList);
    }
}

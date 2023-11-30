<?php

declare(strict_types=1);

namespace App\UseCase\Developers;

use App\Dto\UseCase\Developers\DeveloperDto;
use App\Mappers\Dto\UseCase\Developers\DeveloperDtoMapper;
use App\Services\AdminService;
use App\Services\DeveloperService;

readonly class GetDeveloperNameListForDeletingHandler
{
    public function __construct(
        private DeveloperService $developerService,
        private AdminService $adminService,
    ) {}

    public function handle(int $projectId, int $telegramUserId): array
    {
        $adminDto = $this->adminService->login($telegramUserId);
        $dtoList = $this->developerService->index($projectId, $adminDto->getId());
        $dtoList = DeveloperDtoMapper::convertServiceDtoListToUseCaseDtoList($dtoList);
        $dtoList = array_filter($dtoList, static fn (DeveloperDto $dto) => false === $dto->getIsAdmin());

        return array_map(
            static fn(DeveloperDto $dto) => $dto->getNickname(),
            $dtoList,
        );
    }
}

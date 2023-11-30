<?php

declare(strict_types=1);

namespace App\UseCase\Developers;

use App\Dto\UseCase\Developers\ChangeDeveloperStatusDto;
use App\Mappers\Dto\UseCase\Developers\ChangeDeveloperStatusDtoMapper;
use App\Services\AdminService;
use App\Services\DeveloperService;

readonly class ChangeDeveloperStatusHandler
{
    public function __construct(
        private DeveloperService $developerService,
        private AdminService $adminService,
    ) {}

    public function handle(ChangeDeveloperStatusDto $dto): void
    {
        $adminDto = $this->adminService->login($dto->getTelegramUserId());
        $this->developerService->editStatus(
            new \App\Dto\Services\DeveloperService\ChangeDeveloperStatusDto(
                $adminDto->getId(),
                $dto->getProjectId(),
                $dto->getId(),
                ChangeDeveloperStatusDtoMapper::convertUseCaseStatusToServiceStatus($dto->getStatus())
            )
        );
    }
}

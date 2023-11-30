<?php

declare(strict_types=1);

namespace App\UseCase\Developers;

use App\Dto\UseCase\Developers\AddDeveloperDto;
use App\Mappers\Dto\UseCase\Developers\AddDeveloperDtoMapper;
use App\Services\AdminService;
use App\Services\DeveloperService;

readonly class AddDeveloperHandler
{
    public function __construct(
        private DeveloperService $developerService,
        private AdminService $adminService,
    ) {}

    public function handle(AddDeveloperDto $dto): void
    {
        $adminDto = $this->adminService->login($dto->getTelegramUserId());
        $this->developerService->store(new \App\Dto\Services\DeveloperService\AddDeveloperDto(
            $adminDto->getId(),
            $dto->getProjectId(),
            $dto->getNickname(),
            false,
            AddDeveloperDtoMapper::convertUseCaseStatusToServiceStatus($dto->getStatus()),
        ));
    }
}

<?php

declare(strict_types=1);

namespace App\UseCase\Developers;

use App\Exceptions\Application\ApplicationErrorCodeEnum;
use App\Exceptions\Application\ApplicationException;
use App\Mappers\Dto\UseCase\Developers\DeveloperDtoMapper;
use App\Services\AdminService;
use App\Services\DeveloperService;

readonly class DeleteDeveloperByNameHandler
{
    public function __construct(
        private DeveloperService $developerService,
        private AdminService $adminService,
    ) {}

    public function handle(int $projectId, string $nickname, int $telegramUserId): void
    {
        $adminDto = $this->adminService->login($telegramUserId);
        $dtoList = $this->developerService->index($projectId, $adminDto->getId());
        $dtoFilteredList = array_filter(
            $dtoList,
            static fn(\App\Dto\Services\DeveloperService\DeveloperDto $dto) => $dto->getNickname() === $nickname,
        );

        if ([] === $dtoFilteredList) {
            throw new ApplicationException(ApplicationErrorCodeEnum::ENTITY_NOT_FOUND);
        }

        $dto = current($dtoFilteredList);
        $dto = DeveloperDtoMapper::convertServiceDtoToUseCaseDto($dto);
        $this->developerService->delete($projectId, $dto->getId(), $adminDto->getId());
    }
}

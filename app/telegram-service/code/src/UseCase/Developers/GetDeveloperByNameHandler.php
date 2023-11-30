<?php

declare(strict_types=1);

namespace App\UseCase\Developers;

use App\Dto\UseCase\Developers\DeveloperDto;
use App\Exceptions\Application\ApplicationErrorCodeEnum;
use App\Exceptions\Application\ApplicationException;
use App\Mappers\Dto\UseCase\Developers\DeveloperDtoMapper;
use App\Services\AdminService;
use App\Services\DeveloperService;

readonly class GetDeveloperByNameHandler
{
    public function __construct(
        private DeveloperService $developerService,
        private AdminService $adminService,
    ) {}

    public function handle(int $projectId, string $name, int $telegramUserId): DeveloperDto
    {
        $adminDto = $this->adminService->login($telegramUserId);
        $dtoList = $this->developerService->index($projectId, $adminDto->getId());
        $dtoFilteredList = array_filter(
            $dtoList,
            static fn(\App\Dto\Services\DeveloperService\DeveloperDto $dto) => $dto->getNickname() === $name,
        );

        if ([] === $dtoFilteredList) {
            throw new ApplicationException(ApplicationErrorCodeEnum::ENTITY_NOT_FOUND);
        }

        $dto = current($dtoFilteredList);

        return DeveloperDtoMapper::convertServiceDtoToUseCaseDto($dto);
    }
}

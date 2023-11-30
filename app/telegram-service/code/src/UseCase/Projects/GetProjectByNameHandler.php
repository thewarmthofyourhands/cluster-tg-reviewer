<?php

declare(strict_types=1);

namespace App\UseCase\Projects;

use App\Dto\UseCase\Projects\ProjectDto;
use App\Exceptions\Application\ApplicationErrorCodeEnum;
use App\Exceptions\Application\ApplicationException;
use App\Mappers\Dto\UseCase\Projects\ProjectDtoMapper;
use App\Services\AdminService;
use App\Services\ProjectService;

readonly class GetProjectByNameHandler
{
    public function __construct(
        private ProjectService $projectService,
        private AdminService $adminService,
    ) {}

    public function handle(string $name, int $telegramUserId): ProjectDto
    {
        $adminDto = $this->adminService->login($telegramUserId);
        $projectDtoList = $this->projectService->index($adminDto->getId());
        $projectDtoFilteredList = array_filter(
            $projectDtoList,
            static fn(\App\Dto\Services\ProjectService\ProjectDto $dto) => $dto->getName() === $name,
        );

        if ([] === $projectDtoFilteredList) {
            throw new ApplicationException(ApplicationErrorCodeEnum::ENTITY_NOT_FOUND);
        }

        $projectDto = current($projectDtoFilteredList);

        return ProjectDtoMapper::convertServiceDtoToUseCaseDto($projectDto);
    }
}

<?php

declare(strict_types=1);

namespace App\UseCase\Projects;

use App\Exceptions\Application\ApplicationErrorCodeEnum;
use App\Exceptions\Application\ApplicationException;
use App\Services\AdminService;
use App\Services\ProjectService;

readonly class DeleteProjectByNameHandler
{
    public function __construct(
        private AdminService $adminService,
        private ProjectService $projectService,
    ) {}

    public function handle(string $name, int $telegramUserId): void
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
        assert($projectDto instanceof \App\Dto\Services\ProjectService\ProjectDto);
        $this->projectService->delete($projectDto->getId(), $adminDto->getId());
    }
}

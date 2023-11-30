<?php

declare(strict_types=1);

namespace App\UseCase;

use App\Dto\UseCase\DeveloperDto;
use App\Dto\UseCase\GetDeveloperListDto;
use App\Interfaces\Services\AdminServiceInterface;
use App\Interfaces\Services\DeveloperServiceInterface;

readonly class GetDeveloperListHandler
{
    public function __construct(
        private DeveloperServiceInterface $developerService,
        private AdminServiceInterface $adminService,
    ) {}

    public function handle(GetDeveloperListDto $dto): array
    {
        $this->adminService->auth($dto->getAdminId());
        $res = $this->developerService->getDeveloperList(new \App\Dto\Services\DeveloperService\GetDeveloperListDto(
            $dto->getProjectId(),
        ));

        return array_map(static fn(\App\Dto\Services\DeveloperService\DeveloperDto $dto) => new DeveloperDto(
            $dto->getId(),
            $dto->getProjectId(),
            $dto->getNickname(),
            $dto->getIsAdmin(),
            $dto->getStatus(),
        ), $res);
    }
}

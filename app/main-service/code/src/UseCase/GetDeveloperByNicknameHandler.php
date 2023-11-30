<?php

declare(strict_types=1);

namespace App\UseCase;

use App\Dto\UseCase\DeveloperDto;
use App\Dto\UseCase\GetDeveloperByNicknameDto;
use App\Interfaces\Services\AdminServiceInterface;
use App\Interfaces\Services\DeveloperServiceInterface;

class GetDeveloperByNicknameHandler
{
    public function __construct(
        private readonly DeveloperServiceInterface $developerService,
        private readonly AdminServiceInterface $adminService,
    ) {}

    public function handle(GetDeveloperByNicknameDto $dto): null|DeveloperDto
    {
        $this->adminService->getAdminById($dto->getAdminId());
        $res = $this->developerService->getDeveloperByNickname(new \App\Dto\Services\DeveloperService\GetDeveloperByNicknameDto(
            $dto->getProjectId(),
            $dto->getNickname(),
        ));
        if (null === $res) {
            return null;
        }

        return new DeveloperDto(
            $res->getId(),
            $res->getProjectId(),
            $res->getNickname(),
            $res->getIsAdmin(),
            $res->getStatus(),
        );
    }
}

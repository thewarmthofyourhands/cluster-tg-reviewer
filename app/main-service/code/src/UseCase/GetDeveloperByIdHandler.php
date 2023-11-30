<?php

declare(strict_types=1);

namespace App\UseCase;

use App\Dto\UseCase\DeveloperDto;
use App\Dto\UseCase\GetDeveloperByIdDto;
use App\Exceptions\Application\ApplicationErrorCodeEnum;
use App\Exceptions\Application\ApplicationException;
use App\Interfaces\Services\AdminServiceInterface;
use App\Interfaces\Services\DeveloperServiceInterface;

readonly class GetDeveloperByIdHandler
{
    public function __construct(
        private DeveloperServiceInterface $developerService,
        private AdminServiceInterface $adminService,
    ) {}

    /**
     * @throws ApplicationException
     */
    public function handle(GetDeveloperByIdDto $dto): DeveloperDto
    {
        $this->adminService->auth($dto->getAdminId());
        $res = $this->developerService->getDeveloperById(new \App\Dto\Services\DeveloperService\GetDeveloperByIdDto(
            $dto->getProjectId(),
            $dto->getId(),
        ));

        if (null === $res) {
            throw new ApplicationException(ApplicationErrorCodeEnum::ENTITY_NOT_FOUND);
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

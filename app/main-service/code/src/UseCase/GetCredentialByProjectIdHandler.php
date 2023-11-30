<?php

declare(strict_types=1);

namespace App\UseCase;

use App\Dto\UseCase\CredentialDto;
use App\Dto\UseCase\GetCredentialByProjectIdDto;
use App\Interfaces\Services\AdminServiceInterface;
use App\Interfaces\Services\CredentialServiceInterface;

class GetCredentialByProjectIdHandler
{
    public function __construct(
        private readonly CredentialServiceInterface $credentialService,
        private readonly AdminServiceInterface $adminService,
    ) {}

    public function handle(GetCredentialByProjectIdDto $dto): null|CredentialDto
    {
        $adminDto = $this->adminService->auth($dto->getAdminId());
        $res = $this->credentialService->findCredentialByProjectId(new \App\Dto\Services\CredentialService\GetCredentialByProjectIdDto(
            $dto->getProjectId(),
        ));

        return $res === null ? null : new CredentialDto(
            $res->getId(),
            $res->getProjectId(),
            $res->getToken(),
            $res->getDateExpired()->format('Y-m-d'),
            $res->getIsRequestWorkable(),
        );
    }
}

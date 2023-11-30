<?php

declare(strict_types=1);

namespace App\UseCase;

use App\Dto\UseCase\DeleteCredentialDto;
use App\Interfaces\Services\AdminServiceInterface;
use App\Interfaces\Services\CredentialServiceInterface;

class DeleteCredentialHandler
{
    public function __construct(
        private readonly CredentialServiceInterface $credentialService,
        private readonly AdminServiceInterface $adminService,
    ) {}

    public function handle(DeleteCredentialDto $dto): void
    {
        $adminDto = $this->adminService->auth($dto->getAdminId());
        $this->credentialService->deleteCredential(new \App\Dto\Services\CredentialService\DeleteCredentialDto(
            $dto->getProjectId(),
        ));
    }
}

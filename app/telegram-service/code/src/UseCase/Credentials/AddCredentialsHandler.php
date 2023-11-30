<?php

declare(strict_types=1);

namespace App\UseCase\Credentials;

use App\Dto\UseCase\Credentials\AddCredentialDto;
use App\Services\AdminService;
use App\Services\CredentialsService;

readonly class AddCredentialsHandler
{
    public function __construct(
        private CredentialsService $credentialsService,
        private AdminService $adminService,
    ) {}

    public function handle(AddCredentialDto $dto): void
    {
        $adminDto = $this->adminService->login($dto->getTelegramUserId());
        $this->credentialsService->store(new \App\Dto\Services\CredentialsService\AddCredentialDto(
            $adminDto->getId(),
            $dto->getProjectId(),
            $dto->getToken(),
            $dto->getDateExpired()
        ));
    }
}

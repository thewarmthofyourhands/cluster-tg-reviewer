<?php

declare(strict_types=1);

namespace App\UseCase\Credentials;

use App\Dto\UseCase\Credentials\CredentialDto;
use App\Mappers\Dto\UseCase\Credentials\CredentialDtoMapper;
use App\Services\AdminService;
use App\Services\CredentialsService;

readonly class GetCredentialsHandler
{
    public function __construct(
        private CredentialsService $credentialsService,
        private AdminService $adminService,
    ) {}

    public function handle(int $projectId, int $telegramUserId): null|CredentialDto
    {
        $adminDto = $this->adminService->login($telegramUserId);
        $dto = $this->credentialsService->show($projectId, $adminDto->getId());

        if (null === $dto) {
            return null;
        }

        return CredentialDtoMapper::convertServiceDtoToUseCaseDto($dto);
    }
}

<?php

declare(strict_types=1);

namespace App\UseCase;

use App\Dto\Services\CredentialService\GetCredentialByProjectIdDto;
use App\Dto\Services\ProjectService\GetProjectDto;
use App\Dto\UseCase\AddCredentialDto;
use App\Interfaces\Services\AdminServiceInterface;
use App\Interfaces\Services\CredentialServiceInterface;
use App\Interfaces\Services\GitServiceInterface;
use App\Interfaces\Services\ProjectServiceInterface;
use DateTime;

class AddCredentialHandler
{
    public function __construct(
        private readonly CredentialServiceInterface $credentialService,
        private readonly AdminServiceInterface $adminService,
        private readonly GitServiceInterface $gitService,
        private readonly ProjectServiceInterface $projectService,
    ) {}

    public function handle(AddCredentialDto $dto): void
    {
        $adminDto = $this->adminService->auth($dto->getAdminId());
        $projectDto = $this->projectService->getProject(new GetProjectDto(
            $dto->getProjectId(),
        ));
        $this->credentialService->addCredential(new \App\Dto\Services\CredentialService\AddCredentialDto(
            $dto->getProjectId(),
            $dto->getToken(),
            new DateTime($dto->getDateExpired()),
        ));
        $credentialDto = $this->credentialService->getCredentialByProjectId(new GetCredentialByProjectIdDto(
            $dto->getProjectId(),
        ));
        $isCorrect = $this->gitService->credentialTest($credentialDto, $projectDto);
        $this->credentialService->changeIsRequestWorkable($credentialDto->getId(), $isCorrect);
    }
}

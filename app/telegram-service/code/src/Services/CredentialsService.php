<?php

declare(strict_types=1);

namespace App\Services;

use App\Dto\Services\CredentialsService\AddCredentialDto;
use App\Dto\Services\CredentialsService\CredentialDto;
use App\Services\RequestServices\MainServiceRequestService;

readonly class CredentialsService
{
    public function __construct(
        private MainServiceRequestService $mainServiceRequest,
    ) {}

    public function store(AddCredentialDto $dto): void
    {
        $data = $dto->toArray();
        unset($data['projectId']);
        unset($data['adminId']);
        $this->mainServiceRequest->addCredentials($dto->getProjectId(), $data, $dto->getAdminId());
    }

    public function show(int $projectId, int $adminId): null|CredentialDto
    {
        $data = $this->mainServiceRequest->getCredentials($projectId, $adminId);

        return null === $data ? null : new CredentialDto(...$data);
    }

    public function delete(int $projectId, int $adminId): void
    {
        $this->mainServiceRequest->deleteCredentials($projectId, $adminId);
    }
}

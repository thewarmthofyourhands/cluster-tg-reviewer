<?php

declare(strict_types=1);

namespace App\Services;

use App\Dto\Services\CredentialService\AddCredentialDto;
use App\Dto\Services\CredentialService\CredentialDto;
use App\Dto\Services\CredentialService\DeleteCredentialDto;
use App\Dto\Services\CredentialService\GetCredentialByProjectIdDto;
use App\Dto\Services\ProjectService\GetProjectDto;
use App\Interfaces\Repositories\CredentialRepositoryInterface;
use App\Interfaces\Services\CredentialServiceInterface;
use App\Interfaces\Services\ProjectServiceInterface;
use DateTime;

readonly class CredentialService implements CredentialServiceInterface
{
    public function __construct(
        private CredentialRepositoryInterface $credentialRepository,
    ) {}

    public function addCredential(AddCredentialDto $dto): void
    {
        $this->credentialRepository->addCredential(new \App\Dto\Repositories\CredentialRepository\AddCredentialDto(
            $dto->getProjectId(),
            $dto->getToken(),
            $dto->getDateExpired()->format('Y-m-d'),
        ));
    }

    public function deleteCredential(DeleteCredentialDto $dto): void
    {
        $this->credentialRepository->deleteCredential(new \App\Dto\Repositories\CredentialRepository\DeleteCredentialDto(
            $dto->getProjectId(),
        ));
    }

    public function getCredentialByProjectId(GetCredentialByProjectIdDto $dto): CredentialDto
    {
        $res = $this->credentialRepository->getCredentialByProjectId(new \App\Dto\Repositories\CredentialRepository\GetCredentialByProjectIdDto(
            $dto->getProjectId(),
        ));

        return new CredentialDto(
            $res->getId(),
            $res->getProjectId(),
            $res->getToken(),
            new DateTime($res->getDateExpired()),
            $res->getIsRequestWorkable(),
        );
    }

    public function findCredentialByProjectId(GetCredentialByProjectIdDto $dto): null|CredentialDto
    {
        $res = $this->credentialRepository->findCredentialByProjectId(new \App\Dto\Repositories\CredentialRepository\GetCredentialByProjectIdDto(
            $dto->getProjectId(),
        ));

        return null === $res ? $res : new CredentialDto(
            $res->getId(),
            $res->getProjectId(),
            $res->getToken(),
            new DateTime($res->getDateExpired()),
            $res->getIsRequestWorkable(),
        );
    }

    public function changeIsRequestWorkable(int $id, bool $isRequestWorkable): void
    {
        $this->credentialRepository->changeIsRequestWorkable($id, $isRequestWorkable);
    }
}

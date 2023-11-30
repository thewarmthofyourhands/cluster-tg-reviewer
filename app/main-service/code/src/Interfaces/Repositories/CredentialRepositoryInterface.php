<?php

declare(strict_types=1);

namespace App\Interfaces\Repositories;

use App\Dto\Repositories\CredentialRepository\AddCredentialDto;
use App\Dto\Repositories\CredentialRepository\CredentialDto;
use App\Dto\Repositories\CredentialRepository\DeleteCredentialDto;
use App\Dto\Repositories\CredentialRepository\GetCredentialByProjectIdDto;

interface CredentialRepositoryInterface
{
    public function addCredential(AddCredentialDto $dto): void;
    public function deleteCredential(DeleteCredentialDto $dto): void;
    public function getCredentialByProjectId(GetCredentialByProjectIdDto $dto): CredentialDto;
    public function findCredentialByProjectId(GetCredentialByProjectIdDto $dto): null|CredentialDto;
    public function changeIsRequestWorkable(int $id, bool $isRequestWorkable): void;
}

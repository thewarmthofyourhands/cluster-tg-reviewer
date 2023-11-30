<?php

declare(strict_types=1);

namespace App\Interfaces\Services;

use App\Dto\Services\CredentialService\AddCredentialDto;
use App\Dto\Services\CredentialService\CredentialDto;
use App\Dto\Services\CredentialService\DeleteCredentialDto;
use App\Dto\Services\CredentialService\GetCredentialByProjectIdDto;

interface CredentialServiceInterface
{
    public function addCredential(AddCredentialDto $dto): void;
    public function deleteCredential(DeleteCredentialDto $dto): void;
    public function changeIsRequestWorkable(int $id, bool $isRequestWorkable): void;
    public function getCredentialByProjectId(GetCredentialByProjectIdDto $dto): CredentialDto;
    public function findCredentialByProjectId(GetCredentialByProjectIdDto $dto): null|CredentialDto;
}

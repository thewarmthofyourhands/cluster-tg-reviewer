<?php

declare(strict_types=1);

namespace App\Mappers\Dto\UseCase\Credentials;

use App\Dto\UseCase\Credentials\CredentialDto;

readonly class CredentialDtoMapper
{
    public static function convertServiceDtoToUseCaseDto(\App\Dto\Services\CredentialsService\CredentialDto $dto): CredentialDto
    {
        $data = $dto->toArray();

        return new CredentialDto(...$data);
    }
}

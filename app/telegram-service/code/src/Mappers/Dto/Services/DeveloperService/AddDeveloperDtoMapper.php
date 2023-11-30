<?php

declare(strict_types=1);

namespace App\Mappers\Dto\Services\DeveloperService;

use App\Dto\Services\DeveloperService\AddDeveloperDto;

readonly class AddDeveloperDtoMapper
{
    public static function convertDtoToArray(AddDeveloperDto $dto): array
    {
        $data = $dto->toArray();
        $data['status'] = $dto->getStatus()->value;

        return $data;
    }
}

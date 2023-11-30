<?php

declare(strict_types=1);

namespace App\Mappers\Dto\Services\DeveloperService;

use App\Dto\Services\DeveloperService\DeveloperDto;
use App\Enums\Services\DeveloperService\DeveloperStatusEnum;

readonly class DeveloperDtoMapper
{
    public static function convertArrayToDto(array $data): DeveloperDto
    {
        $data['status'] = DeveloperStatusEnum::from($data['status']);

        return new DeveloperDto(...$data);
    }

    public static function convertArrayListToDtoList(array $list): array
    {
        return array_map(static fn(array $data) => self::convertArrayToDto($data), $list);
    }
}

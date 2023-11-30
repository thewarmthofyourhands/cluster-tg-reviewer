<?php

declare(strict_types=1);

namespace App\Mappers\Dto\UseCase\Developers;

use App\Dto\UseCase\Developers\DeveloperDto;
use App\Enums\UseCase\Developers\DeveloperStatusEnum;

readonly class DeveloperDtoMapper
{
    private static function getStatus(\App\Enums\Services\DeveloperService\DeveloperStatusEnum $statusEnum): DeveloperStatusEnum
    {
        return match ($statusEnum) {
            \App\Enums\Services\DeveloperService\DeveloperStatusEnum::READY => DeveloperStatusEnum::READY,
            \App\Enums\Services\DeveloperService\DeveloperStatusEnum::STOP => DeveloperStatusEnum::STOP,
        };
    }

    public static function convertServiceDtoToUseCaseDto(\App\Dto\Services\DeveloperService\DeveloperDto $dto): DeveloperDto
    {
        $data = $dto->toArray();
        $data['status'] = self::getStatus($dto->getStatus());

        return new DeveloperDto(...$data);
    }

    public static function convertServiceDtoListToUseCaseDtoList(array $list): array
    {
        return array_map(static fn(\App\Dto\Services\DeveloperService\DeveloperDto $data) => self::convertServiceDtoToUseCaseDto($data), $list);
    }
}

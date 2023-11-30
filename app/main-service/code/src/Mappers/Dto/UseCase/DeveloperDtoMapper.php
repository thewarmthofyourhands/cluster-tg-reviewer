<?php

declare(strict_types=1);

namespace App\Mappers\Dto\UseCase;


use App\Dto\UseCase\AddDeveloperDto;
use App\Dto\UseCase\ChangeDeveloperStatusDto;
use App\Dto\UseCase\DeveloperDto;
use App\Enums\DeveloperStatusEnum;

readonly class DeveloperDtoMapper
{
    public static function convertDataToAddDeveloperDto(array $data): AddDeveloperDto
    {
        $data['status'] = DeveloperStatusEnum::from($data['status']);

        return new AddDeveloperDto(...$data);
    }
    public static function convertDataToChangeDeveloperStatusDto(array $data): ChangeDeveloperStatusDto
    {
        $data['status'] = DeveloperStatusEnum::from($data['status']);

        return new ChangeDeveloperStatusDto(...$data);
    }

    public static function convertDtoToArray(DeveloperDto $dto): array
    {
        $data = $dto->toArray();
        $data['status'] = $dto->getStatus()->value;

        return $data;
    }

    public static function convertDtoListToArray(array $dtoList): array
    {
        return array_map(
            static fn(DeveloperDto $dto) => self::convertDtoToArray($dto),
            $dtoList,
        );
    }
}

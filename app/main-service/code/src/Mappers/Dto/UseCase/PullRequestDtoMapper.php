<?php

declare(strict_types=1);

namespace App\Mappers\Dto\UseCase;

use App\Dto\UseCase\PullRequestDto;

readonly class PullRequestDtoMapper
{
    public static function convertDtoToArray(PullRequestDto $dto): array
    {
        $data = $dto->toArray();
        $data['status'] = $dto->getStatus()->value;

        return $data;
    }

    public static function convertDtoListToArray(array $dtoList): array
    {
        return array_map(
            static fn(PullRequestDto $dto) => self::convertDtoToArray($dto),
            $dtoList,
        );
    }
}

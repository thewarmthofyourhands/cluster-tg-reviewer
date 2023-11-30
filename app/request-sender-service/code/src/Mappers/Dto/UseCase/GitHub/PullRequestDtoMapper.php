<?php

declare(strict_types=1);

namespace App\Mappers\Dto\UseCase\GitHub;

use App\Dto\UseCase\GitHub\PullRequestWithStatusDto;

readonly class PullRequestDtoMapper
{
    public static function convertDtoToArray(PullRequestWithStatusDto $dto): array
    {
        $data = $dto->toArray();
        $data['status'] = $dto->getStatus()->value;

        return $data;
    }

    public static function convertDtoListToArray(array $dtoList): array
    {
        return array_map(static fn(PullRequestWithStatusDto $dto) => self::convertDtoToArray($dto), $dtoList);
    }
}

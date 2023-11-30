<?php

declare(strict_types=1);

namespace App\Mappers\Dto\Services\PullRequestService;

use App\Dto\Services\PullRequestService\PullRequestDto;
use App\Enums\Services\PullRequestService\PullRequestStatusEnum;

readonly class PullRequestDtoMapper
{
    public static function convertArrayToDto(array $data): PullRequestDto
    {
        $data['status'] = PullRequestStatusEnum::from($data['status']);

        return new PullRequestDto(...$data);
    }

    public static function convertArrayListToDtoList(array $list): array
    {
        return array_map(static fn(array $data) => self::convertArrayToDto($data), $list);
    }
}

<?php

declare(strict_types=1);

namespace App\Mappers\Dto\UseCase\PullRequests;

use App\Dto\UseCase\PullRequests\PullRequestDto;
use App\Enums\UseCase\PullRequests\PullRequestStatusEnum;

readonly class PullRequestDtoMapper
{
    private static function getStatus(
        \App\Enums\Services\PullRequestService\PullRequestStatusEnum $pullRequestStatusEnum
    ): PullRequestStatusEnum {
        return match ($pullRequestStatusEnum) {
            \App\Enums\Services\PullRequestService\PullRequestStatusEnum::OPEN => PullRequestStatusEnum::OPEN,
            \App\Enums\Services\PullRequestService\PullRequestStatusEnum::CLOSED => PullRequestStatusEnum::CLOSED,
            \App\Enums\Services\PullRequestService\PullRequestStatusEnum::APPROVED => PullRequestStatusEnum::APPROVED,
            \App\Enums\Services\PullRequestService\PullRequestStatusEnum::PENDING => PullRequestStatusEnum::PENDING,
            \App\Enums\Services\PullRequestService\PullRequestStatusEnum::REVIEWING => PullRequestStatusEnum::REVIEWING,
        };
    }

    public static function convertServiceDtoToUseCaseDto(
        \App\Dto\Services\PullRequestService\PullRequestDto $dto
    ): PullRequestDto {
        $data = $dto->toArray();
        $data['status'] = self::getStatus($dto->getStatus());

        return new PullRequestDto(...$data);
    }


    public static function convertServiceDtoListToUseCaseDtoList(array $list): array
    {
        return array_map(static fn(array $data) => self::convertServiceDtoToUseCaseDto($data), $list);
    }
}

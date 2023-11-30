<?php

declare(strict_types=1);

namespace App\Mappers\Dto\UseCase\Developers;

use App\Enums\UseCase\Developers\DeveloperStatusEnum;

readonly class AddDeveloperDtoMapper
{
    public static function convertUseCaseStatusToServiceStatus(DeveloperStatusEnum $developerStatusEnum): \App\Enums\Services\DeveloperService\DeveloperStatusEnum
    {
        return match ($developerStatusEnum) {
            DeveloperStatusEnum::READY => \App\Enums\Services\DeveloperService\DeveloperStatusEnum::READY,
            DeveloperStatusEnum::STOP => \App\Enums\Services\DeveloperService\DeveloperStatusEnum::STOP,
        };
    }
}

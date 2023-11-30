<?php

declare(strict_types=1);

namespace App\Mappers\Dto\Services\ProjectService;

use App\Dto\Services\ProjectService\ProjectDto;
use App\Enums\Services\ProjectService\GitServiceTypeEnum;
use App\Enums\Services\ProjectService\ProjectStatusEnum;
use App\Enums\Services\ProjectService\ReviewTypeEnum;

readonly class ProjectDtoMapper
{
    public static function convertArrayToDto(array $data): ProjectDto
    {
        $data['projectStatus'] = ProjectStatusEnum::from($data['projectStatus']);
        $data['gitType'] = GitServiceTypeEnum::from($data['gitType']);
        $data['reviewType'] = ReviewTypeEnum::from($data['reviewType']);

        return new ProjectDto(...$data);
    }

    public static function convertListToDtoList(array $list): array
    {
        return array_map(static fn(array $data) => self::convertArrayToDto($data), $list);
    }
}

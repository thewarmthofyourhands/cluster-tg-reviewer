<?php

declare(strict_types=1);

namespace App\Mappers\Dto\UseCase\Projects;

use App\Dto\UseCase\Projects\AddProjectDto;
use App\Enums\UseCase\Projects\GitServiceTypeEnum;
use App\Enums\UseCase\Projects\ReviewTypeEnum;

readonly class AddProjectDtoMapper
{
    public static function convertArrayToDto(array $data): AddProjectDto
    {
        $data['gitType'] = GitServiceTypeEnum::from($data['gitType']);
        $data['reviewType'] = ReviewTypeEnum::from($data['reviewType']);

        return new AddProjectDto(...$data);
    }
}

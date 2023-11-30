<?php

declare(strict_types=1);

namespace App\Mappers\Dto\UseCase;

use App\Dto\UseCase\AddProjectDto;
use App\Dto\UseCase\ChangeProjectReviewTypeDto;
use App\Dto\UseCase\ProjectDto;
use App\Enums\GitServiceTypeEnum;
use App\Enums\ReviewTypeEnum;

readonly class ProjectDtoMapper
{
    public static function convertDataToAddProjectDto(array $data): AddProjectDto
    {
        $data['gitType'] = GitServiceTypeEnum::from($data['gitType']);
        $data['reviewType'] = ReviewTypeEnum::from($data['reviewType']);

        return new AddProjectDto(...$data);
    }

    public static function convertDataToChangeProjectReviewTypeDto(array $data): ChangeProjectReviewTypeDto
    {
        $data['reviewType'] = ReviewTypeEnum::from($data['reviewType']);

        return new ChangeProjectReviewTypeDto(...$data);
    }

    public static function convertDtoToArray(ProjectDto $projectDto): array
    {
        $data = $projectDto->toArray();
        $data['projectStatus'] = $projectDto->getProjectStatus()->value;
        $data['gitType'] = $projectDto->getGitType()->value;
        $data['reviewType'] = $projectDto->getReviewType()->value;

        return $data;
    }

    public static function convertDtoListToArray(array $dtoList): array
    {
        return array_map(static fn(ProjectDto $dto) => self::convertDtoToArray($dto), $dtoList);
    }
}

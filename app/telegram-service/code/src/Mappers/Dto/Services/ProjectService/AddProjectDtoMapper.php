<?php

declare(strict_types=1);

namespace App\Mappers\Dto\Services\ProjectService;

use App\Dto\Services\ProjectService\AddProjectDto;
use App\Enums\UseCase\Projects\GitServiceTypeEnum;
use App\Enums\UseCase\Projects\ReviewTypeEnum;

readonly class AddProjectDtoMapper
{
    public static function getGitTypeFromMessage(GitServiceTypeEnum $gitServiceTypeEnum): \App\Enums\Services\ProjectService\GitServiceTypeEnum
    {
        return match ($gitServiceTypeEnum) {
            GitServiceTypeEnum::GIT_HUB => \App\Enums\Services\ProjectService\GitServiceTypeEnum::GIT_HUB,
            GitServiceTypeEnum::GIT_LAB => \App\Enums\Services\ProjectService\GitServiceTypeEnum::GIT_LAB,
            GitServiceTypeEnum::BITBUCKET => \App\Enums\Services\ProjectService\GitServiceTypeEnum::BITBUCKET,
        };
    }

    public static function getReviewTypeFromMessage(ReviewTypeEnum $reviewTypeEnum): \App\Enums\Services\ProjectService\ReviewTypeEnum
    {
        return match ($reviewTypeEnum) {
            ReviewTypeEnum::TEAM_LEAD_REVIEW => \App\Enums\Services\ProjectService\ReviewTypeEnum::TEAM_LEAD_REVIEW,
            ReviewTypeEnum::CROSS_REVIEW => \App\Enums\Services\ProjectService\ReviewTypeEnum::CROSS_REVIEW,
            ReviewTypeEnum::CROSS_REVIEW_WITHOUT_TEAM_LEAD => \App\Enums\Services\ProjectService\ReviewTypeEnum::CROSS_REVIEW_WITHOUT_TEAM_LEAD,
        };
    }

    public static function convertDtoToArray(AddProjectDto $dto): array
    {
        $data = $dto->toArray();
        $data['gitType'] = $dto->getGitType()->value;
        $data['reviewType'] = $dto->getReviewType()->value;

        return $data;
    }

    public static function convertUseCaseDtoToServiceDto(\App\Dto\UseCase\Projects\AddProjectDto $dto): AddProjectDto
    {
        $data = $dto->toArray();
        $data['gitType'] = self::getGitTypeFromMessage($dto->getGitType());
        $data['reviewType'] = self::getReviewTypeFromMessage($dto->getReviewType());

        return new AddProjectDto(...$data);
    }
}

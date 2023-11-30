<?php

declare(strict_types=1);

namespace App\Mappers\Dto\UseCase\Projects;

use App\Dto\Services\ProjectService\ProjectDto as ServiceProjectDto;
use App\Dto\UseCase\Projects\ProjectDto;
use App\Enums\UseCase\Projects\GitServiceTypeEnum;
use App\Enums\UseCase\Projects\ProjectStatusEnum;
use App\Enums\UseCase\Projects\ReviewTypeEnum;

readonly class ProjectDtoMapper
{
    private static function getProjectStatusMessage(\App\Enums\Services\ProjectService\ProjectStatusEnum $projectStatusEnum): ProjectStatusEnum
    {
        return match ($projectStatusEnum) {
            \App\Enums\Services\ProjectService\ProjectStatusEnum::READY => ProjectStatusEnum::READY,
            \App\Enums\Services\ProjectService\ProjectStatusEnum::INVALID_CREDENTIAL => ProjectStatusEnum::INVALID_CREDENTIAL,
            \App\Enums\Services\ProjectService\ProjectStatusEnum::EXPIRED_CREDENTIAL => ProjectStatusEnum::EXPIRED_CREDENTIAL,
            \App\Enums\Services\ProjectService\ProjectStatusEnum::WITHOUT_CREDENTIAL => ProjectStatusEnum::WITHOUT_CREDENTIAL,
            \App\Enums\Services\ProjectService\ProjectStatusEnum::WITHOUT_CHAT => ProjectStatusEnum::WITHOUT_CHAT,
            \App\Enums\Services\ProjectService\ProjectStatusEnum::INVALID_CHAT => ProjectStatusEnum::INVALID_CHAT,
            \App\Enums\Services\ProjectService\ProjectStatusEnum::WITHOUT_DEVELOPERS => ProjectStatusEnum::WITHOUT_DEVELOPERS,
        };
    }

    private static function getGitTypeMessage(\App\Enums\Services\ProjectService\GitServiceTypeEnum $gitServiceTypeEnum): GitServiceTypeEnum
    {
        return match ($gitServiceTypeEnum) {
            \App\Enums\Services\ProjectService\GitServiceTypeEnum::GIT_HUB => GitServiceTypeEnum::GIT_HUB,
            \App\Enums\Services\ProjectService\GitServiceTypeEnum::GIT_LAB => GitServiceTypeEnum::GIT_LAB,
            \App\Enums\Services\ProjectService\GitServiceTypeEnum::BITBUCKET => GitServiceTypeEnum::BITBUCKET,
        };
    }

    private static function getReviewTypeMessage(\App\Enums\Services\ProjectService\ReviewTypeEnum $reviewTypeEnum): ReviewTypeEnum
    {
        return match ($reviewTypeEnum) {
            \App\Enums\Services\ProjectService\ReviewTypeEnum::TEAM_LEAD_REVIEW => ReviewTypeEnum::TEAM_LEAD_REVIEW,
            \App\Enums\Services\ProjectService\ReviewTypeEnum::CROSS_REVIEW => ReviewTypeEnum::CROSS_REVIEW,
            \App\Enums\Services\ProjectService\ReviewTypeEnum::CROSS_REVIEW_WITHOUT_TEAM_LEAD => ReviewTypeEnum::CROSS_REVIEW_WITHOUT_TEAM_LEAD,
        };
    }

    public static function convertServiceDtoToUseCaseDto(ServiceProjectDto $dto): ProjectDto
    {
        $data = $dto->toArray();
        $data['projectStatus'] = self::getProjectStatusMessage($dto->getProjectStatus());
        $data['gitType'] = self::getGitTypeMessage($dto->getGitType());
        $data['reviewType'] = self::getReviewTypeMessage($dto->getReviewType());

        return new ProjectDto(...$data);
    }

    public static function convertServiceDtoListToUseCaseDtoList(array $list): array
    {
        return array_map(static fn(ServiceProjectDto $dto) => self::convertServiceDtoToUseCaseDto($dto), $list);
    }
}

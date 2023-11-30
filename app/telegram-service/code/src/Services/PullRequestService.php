<?php

declare(strict_types=1);

namespace App\Services;

use App\Mappers\Dto\Services\PullRequestService\PullRequestDtoMapper;
use App\Services\RequestServices\MainServiceRequestService;

readonly class PullRequestService
{
    public function __construct(
        private MainServiceRequestService $mainServiceRequest,
    ) {}

    public function index(int $projectId, int $telegramUserId): array
    {
        $list = $this->mainServiceRequest->getPullRequestList($projectId, $telegramUserId);

        return PullRequestDtoMapper::convertArrayListToDtoList($list);
    }

    public function bindDeveloperToPullRequest(int $projectId, int $pullRequestId, int $developerId, int $telegramUserId): void
    {
        $this->mainServiceRequest->bindDeveloperToPullRequest(
            $projectId,
            $pullRequestId,
            $developerId,
            $telegramUserId
        );
    }
}

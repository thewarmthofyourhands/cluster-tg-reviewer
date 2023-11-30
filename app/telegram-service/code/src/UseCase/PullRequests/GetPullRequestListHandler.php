<?php

declare(strict_types=1);

namespace App\UseCase\PullRequests;

use App\Mappers\Dto\UseCase\PullRequests\PullRequestDtoMapper;
use App\Services\PullRequestService;

readonly class GetPullRequestListHandler
{
    public function __construct(
        private PullRequestService $pullRequestService,
    ) {}

    public function handle(int $projectId, int $telegramUserId): array
    {
        $pullRequestDtoList = $this->pullRequestService->index($projectId, $telegramUserId);

        return PullRequestDtoMapper::convertServiceDtoListToUseCaseDtoList($pullRequestDtoList);
    }
}

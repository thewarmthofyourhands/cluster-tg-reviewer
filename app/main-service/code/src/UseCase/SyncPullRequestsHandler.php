<?php

declare(strict_types=1);

namespace App\UseCase;

use App\Dto\Services\ProjectService\GetProjectDto;
use App\Dto\Services\ProjectService\ProjectDto;
use App\Enums\ProjectStatusEnum;
use App\Interfaces\Services\ProjectServiceInterface;
use App\Interfaces\Services\PullRequestServiceInterface;

readonly class SyncPullRequestsHandler
{
    public function __construct(
        private PullRequestServiceInterface $pullRequestService,
        private ProjectServiceInterface $projectService,
    ) {}

    public function handle(array $projectIdList): void
    {
        foreach ($projectIdList as $projectId) {
            $projectDto = $this->projectService->getProject(new GetProjectDto($projectId));

            if ($projectDto->getProjectStatus() !== ProjectStatusEnum::READY) {
                continue;
            }

            $this->pullRequestService->syncPullRequests(new \App\Dto\Services\PullRequestService\SyncPullRequestsDto(
                $projectDto->getId(),
            ));
        }
    }
}

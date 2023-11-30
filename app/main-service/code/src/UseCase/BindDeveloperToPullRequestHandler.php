<?php

declare(strict_types=1);

namespace App\UseCase;

use App\Dto\Services\PullRequestService\ChangeDeveloperIdPullRequestDto;
use App\Dto\UseCase\BindDeveloperToPullRequestDto;
use App\Interfaces\Services\AdminServiceInterface;
use App\Interfaces\Services\PullRequestServiceInterface;

readonly class BindDeveloperToPullRequestHandler
{
    public function __construct(
        private AdminServiceInterface $adminService,
        private PullRequestServiceInterface $pullRequestService,
    ) {}

    public function handle(BindDeveloperToPullRequestDto $dto): void
    {
        $this->adminService->auth($dto->getAdminId());
        $this->pullRequestService->changeDeveloperIdPullRequest(new ChangeDeveloperIdPullRequestDto(
            $dto->getId(),
            $dto->getProjectId(),
            $dto->getDeveloperId(),
        ));
    }
}

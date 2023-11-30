<?php

declare(strict_types=1);

namespace App\UseCase;

use App\Dto\UseCase\GetAllPullRequestListDto;
use App\Dto\UseCase\PullRequestDto;
use App\Interfaces\Services\AdminServiceInterface;
use App\Interfaces\Services\PullRequestServiceInterface;

readonly class GetAllPullRequestListHandler
{
    public function __construct(
        private PullRequestServiceInterface $pullRequestService,
        private AdminServiceInterface $adminService,
    ) {}

    public function handle(GetAllPullRequestListDto $dto): array
    {
        $this->adminService->auth($dto->getAdminId());
        $list = $this->pullRequestService->getAllPullRequestList($dto->getProjectId());

        return array_map(static fn(\App\Dto\Services\PullRequestService\PullRequestDto $dto) => new PullRequestDto(
            $dto->getId(),
            $dto->getProjectId(),
            $dto->getDeveloperId(),
            $dto->getPullRequestNumber(),
            $dto->getTitle(),
            $dto->getBranch(),
            $dto->getStatus(),
            $dto->getLastPendingDate(),
        ), $list);
    }
}

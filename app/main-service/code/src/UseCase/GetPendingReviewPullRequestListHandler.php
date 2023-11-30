<?php

declare(strict_types=1);

namespace App\UseCase;

use App\Dto\UseCase\GetPendingReviewPullRequestListDto;
use App\Dto\UseCase\PullRequestDto;
use App\Interfaces\Services\PullRequestServiceInterface;

readonly class GetPendingReviewPullRequestListHandler
{
    public function __construct(
        private PullRequestServiceInterface $pullRequestService,
    ) {}

    public function handle(GetPendingReviewPullRequestListDto $dto): array
    {
        $list = $this->pullRequestService->getPendingReviewPullRequestList(new \App\Dto\Services\PullRequestService\GetPendingReviewPullRequestListDto(
            $dto->getProjectId(),
        ));

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

<?php

declare(strict_types=1);

namespace App\UseCase;

use App\Dto\UseCase\GetOpenReviewPullRequestListDto;
use App\Dto\UseCase\PullRequestDto;
use App\Interfaces\Services\PullRequestServiceInterface;

readonly class GetOpenReviewPullRequestListHandler
{
    public function __construct(
        private PullRequestServiceInterface $pullRequestService,
    ) {}

    public function handle(GetOpenReviewPullRequestListDto $dto): array
    {
        $list = $this->pullRequestService->getOpenReviewPullRequestList(new \App\Dto\Services\PullRequestService\GetOpenReviewPullRequestListDto(
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

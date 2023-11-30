<?php

declare(strict_types=1);

namespace App\Interfaces\Repositories;

use App\Dto\Repositories\PullRequestRepository\AddPullRequestDto;
use App\Dto\Repositories\PullRequestRepository\ChangeDeveloperIdPullRequestDto;
use App\Dto\Repositories\PullRequestRepository\ChangeStatusPullRequestDto;
use App\Dto\Repositories\PullRequestRepository\ChangeToPendingStatusPullRequestDto;
use App\Dto\Repositories\PullRequestRepository\GetNotClosedPullRequestListDto;
use App\Dto\Repositories\PullRequestRepository\GetOpenReviewPullRequestListDto;
use App\Dto\Repositories\PullRequestRepository\GetOpenReviewWithoutDeveloperPullRequestListDto;
use App\Dto\Repositories\PullRequestRepository\GetPendingReviewPullRequestListDto;

interface PullRequestRepositoryInterface
{
    public function addPullRequest(AddPullRequestDto $dto): void;
    public function changeDeveloperIdPullRequest(ChangeDeveloperIdPullRequestDto $dto): void;
    public function changeStatusPullRequest(ChangeStatusPullRequestDto $dto): void;
    public function updateLastPendingDate(int $id, string $newLastPendingDate): void;
    public function changeToPendingStatusPullRequest(ChangeToPendingStatusPullRequestDto $dto): void;
    public function getOpenReviewPullRequestList(GetOpenReviewPullRequestListDto $dto): array;
    public function getOpenReviewWithoutDeveloperPullRequestList(GetOpenReviewWithoutDeveloperPullRequestListDto $dto): array;
    public function getPendingReviewPullRequestList(GetPendingReviewPullRequestListDto $dto): array;
    public function getNotClosedReviewPullRequestList(GetNotClosedPullRequestListDto $dto): array;
    public function getAllPullRequestList(int $projectId): array;
}

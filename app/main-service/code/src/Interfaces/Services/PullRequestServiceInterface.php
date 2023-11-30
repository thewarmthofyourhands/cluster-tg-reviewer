<?php

declare(strict_types=1);

namespace App\Interfaces\Services;

use App\Dto\Services\PullRequestService\AddPullRequestDto;
use App\Dto\Services\PullRequestService\CaptureOpenPullRequestsDto;
use App\Dto\Services\PullRequestService\ChangeDeveloperIdPullRequestDto;
use App\Dto\Services\PullRequestService\ChangeStatusPullRequestDto;
use App\Dto\Services\PullRequestService\ChangeToPendingStatusPullRequestDto;
use App\Dto\Services\PullRequestService\GetNotClosedPullRequestListDto;
use App\Dto\Services\PullRequestService\GetOpenReviewPullRequestListDto;
use App\Dto\Services\PullRequestService\GetOpenReviewWithoutDeveloperPullRequestListDto;
use App\Dto\Services\PullRequestService\GetPendingReviewPullRequestListDto;
use App\Dto\Services\PullRequestService\SyncPullRequestsDto;

interface PullRequestServiceInterface
{
    public function addPullRequest(AddPullRequestDto $dto): void;
    public function changeDeveloperIdPullRequest(ChangeDeveloperIdPullRequestDto $dto): void;
    public function changeStatusPullRequest(ChangeStatusPullRequestDto $dto): void;
    public function updateLastPendingDate(int $id): void;
    public function changeToPendingStatusPullRequest(ChangeToPendingStatusPullRequestDto $dto): void;
    public function syncPullRequests(SyncPullRequestsDto $dto): void;
    public function getOpenReviewPullRequestList(GetOpenReviewPullRequestListDto $dto): array;
    public function getOpenReviewWithoutDeveloperPullRequestList(GetOpenReviewWithoutDeveloperPullRequestListDto $dto): array;
    public function getPendingReviewPullRequestList(GetPendingReviewPullRequestListDto $dto): array;
    public function getNotClosedReviewPullRequestList(GetNotClosedPullRequestListDto $dto): array;
    public function getAllPullRequestList(int $projectId): array;
}

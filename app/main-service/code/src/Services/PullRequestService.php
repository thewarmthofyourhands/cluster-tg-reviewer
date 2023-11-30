<?php

declare(strict_types=1);

namespace App\Services;

use App\Dto\Services\CredentialService\GetCredentialByProjectIdDto;
use App\Dto\Services\DeveloperService\DeveloperWithPendingPullRequestCountDto;
use App\Dto\Services\GitService\GetPullRequestListDto;
use App\Dto\Services\GitService\RemotePullRequestDto;
use App\Dto\Services\ProjectService\GetProjectDto;
use App\Dto\Services\PullRequestService\AddPullRequestDto;
use App\Dto\Services\PullRequestService\ChangeDeveloperIdPullRequestDto;
use App\Dto\Services\PullRequestService\ChangeStatusPullRequestDto;
use App\Dto\Services\PullRequestService\ChangeToPendingStatusPullRequestDto;
use App\Dto\Services\PullRequestService\GetNotClosedPullRequestListDto;
use App\Dto\Services\PullRequestService\GetOpenReviewPullRequestListDto;
use App\Dto\Services\PullRequestService\GetOpenReviewWithoutDeveloperPullRequestListDto;
use App\Dto\Services\PullRequestService\GetPendingReviewPullRequestListDto;
use App\Dto\Services\PullRequestService\PullRequestDto;
use App\Dto\Services\PullRequestService\SyncPullRequestsDto;
use App\Enums\DeveloperStatusEnum;
use App\Enums\PullRequestStatusEnum;
use App\Enums\ReviewTypeEnum;
use App\Interfaces\Repositories\PullRequestRepositoryInterface;
use App\Interfaces\Services\CredentialServiceInterface;
use App\Interfaces\Services\GitServiceInterface;
use App\Interfaces\Services\ProjectServiceInterface;
use App\Interfaces\Services\PullRequestServiceInterface;

readonly class PullRequestService implements PullRequestServiceInterface
{
    public function __construct(
        private PullRequestRepositoryInterface $pullRequestRepository,
        private CredentialServiceInterface $credentialService,
        private ProjectServiceInterface $projectService,
        private GitServiceInterface $gitService,
    ) {}

    public function addPullRequest(AddPullRequestDto $dto): void
    {
        $this->pullRequestRepository->addPullRequest(new \App\Dto\Repositories\PullRequestRepository\AddPullRequestDto(
            $dto->getProjectId(),
            $dto->getDeveloperId(),
            $dto->getPullRequestNumber(),
            $dto->getTitle(),
            $dto->getBranch(),
            $dto->getStatus(),
            $dto->getLastPendingDate(),
        ));
    }

    public function changeDeveloperIdPullRequest(ChangeDeveloperIdPullRequestDto $dto): void
    {
        $this->pullRequestRepository->changeDeveloperIdPullRequest(new \App\Dto\Repositories\PullRequestRepository\ChangeDeveloperIdPullRequestDto(
            $dto->getId(),
            $dto->getProjectId(),
            $dto->getDeveloperId(),
        ));
    }

    public function changeStatusPullRequest(ChangeStatusPullRequestDto $dto): void
    {
        $this->pullRequestRepository->changeStatusPullRequest(new \App\Dto\Repositories\PullRequestRepository\ChangeStatusPullRequestDto(
            $dto->getId(),
            $dto->getProjectId(),
            $dto->getStatus(),
        ));

        if ($dto->getStatus() === PullRequestStatusEnum::PENDING) {
            $this->updateLastPendingDate($dto->getId());
        }
    }

    public function updateLastPendingDate(int $id): void
    {
        $newLastPendingDate = (new \DateTime())->format('Y-m-d H:i:s');
        $this->pullRequestRepository->updateLastPendingDate($id, $newLastPendingDate);
    }

    public function changeToPendingStatusPullRequest(ChangeToPendingStatusPullRequestDto $dto): void
    {
        assert($dto->getStatus() === PullRequestStatusEnum::PENDING);
        $this->pullRequestRepository->changeToPendingStatusPullRequest(new \App\Dto\Repositories\PullRequestRepository\ChangeToPendingStatusPullRequestDto(
            $dto->getId(),
            $dto->getProjectId(),
            $dto->getStatus(),
            $dto->getLastPendingDate()
        ));
    }

    public function findMostlyFreeDeveloper(
        array $developerWithPullRequestCountList,
        ReviewTypeEnum $reviewTypeEnum,
    ): null|DeveloperWithPendingPullRequestCountDto {
        $current = null;

        foreach ($developerWithPullRequestCountList as $developerWithPullRequestCount) {
            assert($developerWithPullRequestCount instanceof DeveloperWithPendingPullRequestCountDto);
            if ($developerWithPullRequestCount->getStatus() === DeveloperStatusEnum::STOP) {
                continue;
            }

            if (
                ReviewTypeEnum::TEAM_LEAD_REVIEW === $reviewTypeEnum
                && false === $developerWithPullRequestCount->getIsAdmin()
            ) {
                continue;
            }

            if (
                ReviewTypeEnum::CROSS_REVIEW_WITHOUT_TEAM_LEAD === $reviewTypeEnum
                && true === $developerWithPullRequestCount->getIsAdmin()
            ) {
                continue;
            }

            if (null === $current) {
                $current = $developerWithPullRequestCount;
                continue;
            }

            if ($current->getPullRequestCount() > $developerWithPullRequestCount->getPullRequestCount()) {
                $current = $developerWithPullRequestCount;
            }
        }

        return $current;
    }

    public function syncPullRequests(SyncPullRequestsDto $dto): void
    {
        $projectDto = $this->projectService->getProject(new GetProjectDto($dto->getProjectId()));
        $credentialDto = $this->credentialService->getCredentialByProjectId(new GetCredentialByProjectIdDto(
            $projectDto->getId(),
        ));
        $currentPullRequestList = $this->getAllPullRequestList($projectDto->getId());
        $list = $this->gitService->getPullRequestList(new GetPullRequestListDto(
            $projectDto->getGitRepositoryUrl(),
            $credentialDto->getToken(),
        ));

        foreach ($list as $remotePullRequestDto) {
            assert($remotePullRequestDto instanceof RemotePullRequestDto);
            $alreadyExistedPullRequestList = array_filter($currentPullRequestList, static fn(PullRequestDto $dto) => $dto->getPullRequestNumber() === $remotePullRequestDto->getId());

            if ($alreadyExistedPullRequestList !== []) {
                $alreadyExistedPullRequest = current($alreadyExistedPullRequestList);
                assert($alreadyExistedPullRequest instanceof PullRequestDto);

                if ($alreadyExistedPullRequest->getStatus() !== $remotePullRequestDto->getStatus()) {
                    $this->changeStatusPullRequest(new ChangeStatusPullRequestDto(
                        $alreadyExistedPullRequest->getId(),
                        $projectDto->getId(),
                        $remotePullRequestDto->getStatus(),
                    ));
                }
            } else {
                if (in_array(
                    $remotePullRequestDto->getStatus(),
                    [PullRequestStatusEnum::PENDING, PullRequestStatusEnum::APPROVED, PullRequestStatusEnum::CLOSED],
                    true,
                )){
                    continue;
                }

                $this->addPullRequest(new AddPullRequestDto(
                    $projectDto->getId(),
                    null,
                    $remotePullRequestDto->getId(),
                    $remotePullRequestDto->getTitle(),
                    $remotePullRequestDto->getBranch(),
                    $remotePullRequestDto->getStatus(),
                    null,
                ));
            }
        }
    }

    public function getOpenReviewPullRequestList(GetOpenReviewPullRequestListDto $dto): array
    {
        $list = $this->pullRequestRepository->getOpenReviewPullRequestList(new \App\Dto\Repositories\PullRequestRepository\GetOpenReviewPullRequestListDto(
            $dto->getProjectId(),
        ));

        return array_map(static fn(\App\Dto\Repositories\PullRequestRepository\PullRequestDto $dto) => new PullRequestDto(
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

    public function getOpenReviewWithoutDeveloperPullRequestList(GetOpenReviewWithoutDeveloperPullRequestListDto $dto): array
    {
        $list = $this->pullRequestRepository->getOpenReviewWithoutDeveloperPullRequestList(new \App\Dto\Repositories\PullRequestRepository\GetOpenReviewWithoutDeveloperPullRequestListDto(
            $dto->getProjectId(),
        ));

        return array_map(static fn(\App\Dto\Repositories\PullRequestRepository\PullRequestDto $dto) => new PullRequestDto(
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

    public function getPendingReviewPullRequestList(GetPendingReviewPullRequestListDto $dto): array
    {
        $list = $this->pullRequestRepository->getPendingReviewPullRequestList(new \App\Dto\Repositories\PullRequestRepository\GetPendingReviewPullRequestListDto(
            $dto->getProjectId(),
        ));

        return array_map(static fn(\App\Dto\Repositories\PullRequestRepository\PullRequestDto $dto) => new PullRequestDto(
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

    public function getNotClosedReviewPullRequestList(GetNotClosedPullRequestListDto $dto): array
    {
        $list = $this->pullRequestRepository->getNotClosedReviewPullRequestList(new \App\Dto\Repositories\PullRequestRepository\GetNotClosedPullRequestListDto(
            $dto->getProjectId(),
        ));

        return array_map(static fn(\App\Dto\Repositories\PullRequestRepository\PullRequestDto $dto) => new PullRequestDto(
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

    public function getAllPullRequestList(int $projectId): array
    {
        $list = $this->pullRequestRepository->getAllPullRequestList($projectId);

        return array_map(static fn(\App\Dto\Repositories\PullRequestRepository\PullRequestDto $dto) => new PullRequestDto(
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

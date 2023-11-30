<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Dto\Repositories\PullRequestRepository\AddPullRequestDto;
use App\Dto\Repositories\PullRequestRepository\ChangeDeveloperIdPullRequestDto;
use App\Dto\Repositories\PullRequestRepository\ChangeStatusPullRequestDto;
use App\Dto\Repositories\PullRequestRepository\ChangeToPendingStatusPullRequestDto;
use App\Dto\Repositories\PullRequestRepository\GetNotClosedPullRequestListDto;
use App\Dto\Repositories\PullRequestRepository\GetOpenReviewPullRequestListDto;
use App\Dto\Repositories\PullRequestRepository\GetOpenReviewWithoutDeveloperPullRequestListDto;
use App\Dto\Repositories\PullRequestRepository\GetPendingReviewPullRequestListDto;
use App\Dto\Repositories\PullRequestRepository\PullRequestDto;
use App\Enums\PullRequestStatusEnum;
use App\Interfaces\Repositories\PullRequestRepositoryInterface;
use Eva\Database\ConnectionInterface;
use Eva\Database\ConnectionStoreInterface;

readonly class PullRequestRepository implements PullRequestRepositoryInterface
{
    private ConnectionInterface $connection;

    public function __construct(ConnectionStoreInterface $connectionStore)
    {
        $this->connection = $connectionStore->get();
    }

    public function addPullRequest(AddPullRequestDto $dto): void
    {
        $stmt = $this->connection->prepare('
            insert into pullRequests (projectId, developerId, pullRequestNumber, title, branch, status, lastPendingDate)
            values (:projectId, :developerId, :pullRequestNumber, :title, :branch, :status, :lastPendingDate)
        ', [
            'projectId' => $dto->getProjectId(),
            'developerId' => $dto->getDeveloperId(),
            'pullRequestNumber' => $dto->getPullRequestNumber(),
            'title' => $dto->getTitle(),
            'branch' => $dto->getBranch(),
            'status' => $dto->getStatus()->value,
            'lastPendingDate' => $dto->getLastPendingDate(),
        ]);

        $stmt->execute();
        $stmt->closeCursor();
    }

    public function changeDeveloperIdPullRequest(ChangeDeveloperIdPullRequestDto $dto): void
    {
        $stmt = $this->connection->prepare('
            update pullRequests
            set developerId = :developerId
            where projectId = :projectId and id = :id
        ', [
            'developerId' => $dto->getDeveloperId(),
            'projectId' => $dto->getProjectId(),
            'id' => $dto->getId(),
        ]);
        $stmt->execute();
        $stmt->closeCursor();
    }

    public function changeStatusPullRequest(ChangeStatusPullRequestDto $dto): void
    {
        $stmt = $this->connection->prepare('
            update pullRequests
            set status = :status
            where projectId = :projectId and id = :id
        ', [
            'status' => $dto->getStatus()->value,
            'projectId' => $dto->getProjectId(),
            'id' => $dto->getId(),
        ]);
        $stmt->execute();
        $stmt->closeCursor();
    }

    public function changeToPendingStatusPullRequest(ChangeToPendingStatusPullRequestDto $dto): void
    {
        $stmt = $this->connection->prepare('
            update pullRequests
            set status = :status
            where projectId = :projectId and id = :id
        ', [
            'status' => $dto->getStatus()->value,
            'projectId' => $dto->getProjectId(),
            'id' => $dto->getId(),
        ]);
        $stmt->execute();
        $stmt->closeCursor();
    }

    public function getOpenReviewPullRequestList(GetOpenReviewPullRequestListDto $dto): array
    {
        $stmt = $this->connection->prepare('
            select *
            from pullRequests
            where projectId = :projectId and status = :status
        ',
            [
                'projectId' => $dto->getProjectId(),
                'status' => PullRequestStatusEnum::OPEN->value,
            ],
        );
        $list = [];
        $stmt->execute();

        while($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $row['status'] = PullRequestStatusEnum::from($row['status']);
            $list[] = new PullRequestDto(...$row);
        }

        $stmt->closeCursor();

        return $list;
    }

    public function getOpenReviewWithoutDeveloperPullRequestList(GetOpenReviewWithoutDeveloperPullRequestListDto $dto): array
    {
        $stmt = $this->connection->prepare('
            select *
            from pullRequests
            where projectId = :projectId
              and (developerId is null or developerId not in (select developers.id from developers)) 
              and (status = :openStatus or status = :pendingStatus)
        ',
            [
                'projectId' => $dto->getProjectId(),
                'openStatus' => PullRequestStatusEnum::OPEN->value,
                'pendingStatus' => PullRequestStatusEnum::PENDING->value,
            ],
        );
        $list = [];
        $stmt->execute();

        while($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $row['status'] = PullRequestStatusEnum::from($row['status']);
            $list[] = new PullRequestDto(...$row);
        }

        $stmt->closeCursor();

        return $list;
    }

    public function getPendingReviewPullRequestList(GetPendingReviewPullRequestListDto $dto): array
    {
        $stmt = $this->connection->prepare('
            select *
            from pullRequests
            where projectId = :projectId and status = :status
        ',
            [
                'projectId' => $dto->getProjectId(),
                'status' => PullRequestStatusEnum::PENDING->value,
            ],
        );
        $list = [];
        $stmt->execute();

        while($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $row['status'] = PullRequestStatusEnum::from($row['status']);
            $list[] = new PullRequestDto(...$row);
        }

        $stmt->closeCursor();

        return $list;
    }

    public function getAllPullRequestList(int $projectId): array
    {
       $stmt = $this->connection->prepare('
            select *
            from pullRequests
            where projectId = :projectId
        ',
           [
               'projectId' => $projectId,
           ]
        );
        $list = [];
        $stmt->execute();

        while($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $row['status'] = PullRequestStatusEnum::from($row['status']);
            $list[] = new PullRequestDto(...$row);
        }

        $stmt->closeCursor();

        return $list;
    }

    public function getNotClosedReviewPullRequestList(GetNotClosedPullRequestListDto $dto): array
    {
        $stmt = $this->connection->prepare('
            select *
            from pullRequests
            where projectId = :projectId and status = :closed
        ',
            [
                'projectId' => $dto->getProjectId(),
                'closed' => PullRequestStatusEnum::CLOSED->value,
//                'approved' => PullRequestStatusEnum::APPROVED->value,
            ],
        );
        $list = [];
        $stmt->execute();

        while($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $row['status'] = PullRequestStatusEnum::from($row['status']);
            $list[] = new PullRequestDto(...$row);
        }

        $stmt->closeCursor();

        return $list;
    }

    public function updateLastPendingDate(int $id, string $newLastPendingDate): void
    {
        $stmt = $this->connection->prepare('
            update pullRequests
            set lastPendingDate = :lastPendingDate
            where id = :id
        ', [
            'lastPendingDate' => $newLastPendingDate,
            'id' => $id,
        ]);
        $stmt->execute();
        $stmt->closeCursor();
    }
}

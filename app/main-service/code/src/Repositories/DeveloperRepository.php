<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Dto\Repositories\DeveloperRepository\AddDeveloperDto;
use App\Dto\Repositories\DeveloperRepository\ChangeDeveloperStatusDto;
use App\Dto\Repositories\DeveloperRepository\DeleteDeveloperDto;
use App\Dto\Repositories\DeveloperRepository\DeveloperDto;
use App\Dto\Repositories\DeveloperRepository\DeveloperWithPendingPullRequestCountDto;
use App\Dto\Repositories\DeveloperRepository\DeveloperWithPullRequestCountDto;
use App\Dto\Repositories\DeveloperRepository\GetDeveloperByIdDto;
use App\Dto\Repositories\DeveloperRepository\GetDeveloperByNicknameDto;
use App\Dto\Repositories\DeveloperRepository\GetDeveloperListDto;
use App\Dto\Repositories\DeveloperRepository\GetDeveloperWithPendingPullRequestCountListDto;
use App\Enums\DeveloperStatusEnum;
use App\Interfaces\Repositories\DeveloperRepositoryInterface;
use Eva\Database\ConnectionInterface;
use Eva\Database\ConnectionStoreInterface;

class DeveloperRepository implements DeveloperRepositoryInterface
{
    private readonly ConnectionInterface $connection;

    public function __construct(ConnectionStoreInterface $connectionStore)
    {
        $this->connection = $connectionStore->get();
    }

    public function addDeveloper(AddDeveloperDto $dto): void
    {
        $stmt = $this->connection->prepare('
            insert into developers (projectId, nickname,isAdmin, status) values (:projectId, :nickname, :isAdmin, :status)
        ', [
            'projectId' => $dto->getProjectId(),
            'nickname' => $dto->getNickname(),
            'isAdmin' => (int) $dto->getIsAdmin(),
            'status' => $dto->getStatus()->value,
        ]);

        $stmt->execute();
        $stmt->closeCursor();
    }

    public function changeDeveloperStatus(ChangeDeveloperStatusDto $dto): void
    {
        $stmt = $this->connection->prepare('
            update developers set status = :status where projectId = :projectId and id = :id
        ', [
            'projectId' => $dto->getProjectId(),
            'id' => $dto->getId(),
            'status' => $dto->getStatus()->value,
        ]);
        $stmt->execute();
        $stmt->closeCursor();
    }

    public function deleteDeveloper(DeleteDeveloperDto $dto): void
    {
        $stmt = $this->connection->prepare('
            delete from developers where id = :id and projectId = :projectId and isAdmin = :isAdmin
        ', [
            'projectId' => $dto->getProjectId(),
            'id' => $dto->getId(),
            'isAdmin' => false,
        ]);

        $stmt->execute();
        $stmt->closeCursor();
    }

    public function getDeveloperList(GetDeveloperListDto $dto): array
    {
        $stmt = $this->connection->prepare('
            select * from developers where projectId = :projectId
        ',
            [
                'projectId' => $dto->getProjectId(),
            ],
        );
        $list = [];
        $stmt->execute();

        while($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $row['status'] = DeveloperStatusEnum::from($row['status']);
            $row['isAdmin'] = (bool) $row['isAdmin'];
            $list[] = new DeveloperDto(...$row);
        }

        $stmt->closeCursor();

        return $list;
    }

    public function getDeveloperWithPendingPullRequestCountList(GetDeveloperWithPendingPullRequestCountListDto $dto): array
    {
        $stmt = $this->connection->prepare('
            select developers.*, count(pullRequests.id) as pullRequestCount
            from developers
            left join pullRequests on developers.id = pullRequests.developerId
            where developers.projectId = :projectId
            group by developers.id
        ', [
            'projectId' => $dto->getProjectId(),
        ]);
        $list = [];
        $stmt->execute();

        while($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $row['status'] = DeveloperStatusEnum::from($row['status']);
            $row['isAdmin'] = (bool) $row['isAdmin'];
            $list[] = new DeveloperWithPendingPullRequestCountDto(...$row);
        }

        $stmt->closeCursor();

        return $list;
    }

    public function getDeveloperById(GetDeveloperByIdDto $dto): null|DeveloperDto
    {
        $stmt = $this->connection->prepare('
            select * from developers where projectId = :projectId and id = :id
        ',
            [
                'projectId' => $dto->getProjectId(),
                'id' => $dto->getId(),
            ],
        );
        $stmt->execute();
        $row = $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;
        $stmt->closeCursor();
        if (null === $row) {
            return null;
        }

        $row['status'] = DeveloperStatusEnum::from($row['status']);
        $row['isAdmin'] = (bool) $row['isAdmin'];

        return new DeveloperDto(...$row);
    }

    public function getDeveloperByNickname(GetDeveloperByNicknameDto $dto): null|DeveloperDto
    {
        $stmt = $this->connection->prepare('
            select * from developers where projectId = :projectId and nickname = :nickname
        ',
            [
                'projectId' => $dto->getProjectId(),
                'nickname' => $dto->getNickname(),
            ],
        );
        $stmt->execute();
        $row = $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;
        $stmt->closeCursor();
        if (null === $row) {
            return null;
        }

        $row['isAdmin'] = (bool) $row['isAdmin'];
        $row['status'] = DeveloperStatusEnum::from($row['status']);

        return new DeveloperDto(...$row);
    }
}

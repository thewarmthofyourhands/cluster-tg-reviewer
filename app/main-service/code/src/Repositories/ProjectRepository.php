<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Dto\Repositories\ProjectRepository\AddProjectDto;
use App\Dto\Repositories\ProjectRepository\ChangeProjectReviewTypeDto;
use App\Dto\Repositories\ProjectRepository\DeleteProjectDto;
use App\Dto\Repositories\ProjectRepository\GetProjectByGitRepositoryUrlDto;
use App\Dto\Repositories\ProjectRepository\GetProjectByNameDto;
use App\Dto\Repositories\ProjectRepository\GetProjectDto;
use App\Dto\Repositories\ProjectRepository\GetProjectListDto;
use App\Dto\Repositories\ProjectRepository\ProjectDto;
use App\Enums\GitServiceTypeEnum;
use App\Enums\ReviewTypeEnum;
use App\Interfaces\Repositories\ProjectRepositoryInterface;
use Eva\Database\ConnectionInterface;
use Eva\Database\ConnectionStoreInterface;

class ProjectRepository implements ProjectRepositoryInterface
{
    private readonly ConnectionInterface $connection;

    public function __construct(ConnectionStoreInterface $connectionStore)
    {
        $this->connection = $connectionStore->get();
    }


    public function deleteProject(DeleteProjectDto $dto): void
    {
        $stmt = $this->connection->prepare('
            delete from projects where id = :id and adminId = :adminId
        ', [
            'adminId' => $dto->getAdminId(),
            'id' => $dto->getId(),
        ]);

        $stmt->execute();
        $stmt->closeCursor();
    }

    public function addProject(AddProjectDto $dto): int
    {
        $stmt = $this->connection->prepare('
            insert into projects (adminId, name, gitRepositoryUrl, gitType, reviewType)
            values (:adminId, :name, :gitRepositoryUrl, :gitType, :reviewType)
        ', [
            'adminId' => $dto->getAdminId(),
            'name' => $dto->getName(),
            'gitRepositoryUrl' => $dto->getGitRepositoryUrl(),
            'gitType' => $dto->getGitType()->value,
            'reviewType' => $dto->getReviewType()->value,
        ]);

        $stmt->execute();
        $stmt->closeCursor();

        return (int) $this->connection->lastInsertId();
    }

    public function changeProjectReviewType(ChangeProjectReviewTypeDto $dto): void
    {
        $stmt = $this->connection->prepare('
            update projects
            set reviewType = :reviewType
            where adminId = :adminId and id = :id
        ', [
            'adminId' => $dto->getAdminId(),
            'id' => $dto->getId(),
            'reviewType' => $dto->getReviewType()->value,
        ]);
        $stmt->execute();
        $stmt->closeCursor();
    }

    public function getProjectList(GetProjectListDto $dto): array
    {
        $stmt = $this->connection->prepare('
            select *
            from projects
            where adminId = :adminId
        ',
            [
                'adminId' => $dto->getAdminId(),
            ],
        );
        $list = [];
        $stmt->execute();

        while($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $row['gitType'] = GitServiceTypeEnum::from($row['gitType']);
            $row['reviewType'] = ReviewTypeEnum::from($row['reviewType']);
            $list[] = new ProjectDto(...$row);
        }

        $stmt->closeCursor();

        return $list;
    }

    public function getProject(GetProjectDto $dto): null|ProjectDto
    {
        $stmt = $this->connection->prepare('
            select *
            from projects
            where id = :id
        ',
            [
                'id' => $dto->getId(),
            ],
        );
        $stmt->execute();
        $row = $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;
        $stmt->closeCursor();
        if (null === $row) {
            return null;
        }

        $row['gitType'] = GitServiceTypeEnum::from($row['gitType']);
        $row['reviewType'] = ReviewTypeEnum::from($row['reviewType']);

        return new ProjectDto(...$row);
    }

    public function getProjectByName(GetProjectByNameDto $dto): null|ProjectDto
    {
        $stmt = $this->connection->prepare('
            select *
            from projects
            where adminId = :adminId and name = :name
        ',
            [
                'adminId' => $dto->getAdminId(),
                'name' => $dto->getName(),
            ],
        );
        $stmt->execute();
        $row = $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;
        $stmt->closeCursor();
        if (null === $row) {
            return null;
        }

        $row['gitType'] = GitServiceTypeEnum::from($row['gitType']);
        $row['reviewType'] = ReviewTypeEnum::from($row['reviewType']);

        return new ProjectDto(...$row);
    }

    public function getProjectByGitRepositoryUrl(GetProjectByGitRepositoryUrlDto $dto): null|ProjectDto
    {
        $stmt = $this->connection->prepare('
            select *
            from projects
            where adminId = :adminId and gitRepositoryUrl = :gitRepositoryUrl
        ',
            [
                'adminId' => $dto->getAdminId(),
                'gitRepositoryUrl' => $dto->getGitRepositoryUrl(),
            ],
        );
        $stmt->execute();
        $row = $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;
        $stmt->closeCursor();
        if (null === $row) {
            return null;
        }

        $row['gitType'] = GitServiceTypeEnum::from($row['gitType']);
        $row['reviewType'] = ReviewTypeEnum::from($row['reviewType']);

        return new ProjectDto(...$row);
    }

    public function getAllProjectList(): array
    {
        $stmt = $this->connection->prepare('
            select *
            from projects
        ',
        );
        $list = [];
        $stmt->execute();

        while($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $row['gitType'] = GitServiceTypeEnum::from($row['gitType']);
            $row['reviewType'] = ReviewTypeEnum::from($row['reviewType']);
            $list[] = new ProjectDto(...$row);
        }

        $stmt->closeCursor();

        return $list;
    }

    public function getProjectIdList(): array
    {
        $stmt = $this->connection->prepare('
            select id from projects
        ');
        $list = [];
        $stmt->execute();

        while($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $list[] = $row['id'];
        }

        $stmt->closeCursor();

        return $list;
    }
}

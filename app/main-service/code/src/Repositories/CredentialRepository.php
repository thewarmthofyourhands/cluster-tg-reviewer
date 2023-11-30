<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Dto\Repositories\CredentialRepository\AddCredentialDto;
use App\Dto\Repositories\CredentialRepository\CredentialDto;
use App\Dto\Repositories\CredentialRepository\DeleteCredentialDto;
use App\Dto\Repositories\CredentialRepository\GetCredentialByProjectIdDto;
use App\Interfaces\Repositories\CredentialRepositoryInterface;
use Eva\Database\ConnectionInterface;
use Eva\Database\ConnectionStoreInterface;

readonly class CredentialRepository implements CredentialRepositoryInterface
{
    private ConnectionInterface $connection;

    public function __construct(ConnectionStoreInterface $connectionStore)
    {
        $this->connection = $connectionStore->get();
    }

    public function addCredential(AddCredentialDto $dto): void
    {
        $stmt = $this->connection->prepare('
            insert into credentials (projectId, token, dateExpired) values (:projectId, :token, :dateExpired)
        ', [
            'projectId' => $dto->getProjectId(),
            'token' => $dto->getToken(),
            'dateExpired' => $dto->getDateExpired(),
        ]);

        $stmt->execute();
        $stmt->closeCursor();
    }

    public function deleteCredential(DeleteCredentialDto $dto): void
    {
        $stmt = $this->connection->prepare('
            delete from credentials where projectId = :projectId
        ', [
            'projectId' => $dto->getProjectId(),
        ]);

        $stmt->execute();
        $stmt->closeCursor();
    }

    public function getCredentialByProjectId(GetCredentialByProjectIdDto $dto): CredentialDto
    {
        $stmt = $this->connection->prepare('
            select * from credentials where projectId = :projectId
        ',
        [
            'projectId' => $dto->getProjectId(),
        ],
    );
        $stmt->execute();
        $row = $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;
        $stmt->closeCursor();
        $row['isRequestWorkable'] = (bool) $row['isRequestWorkable'];

        return new CredentialDto(...$row);
    }

    public function findCredentialByProjectId(GetCredentialByProjectIdDto $dto): null|CredentialDto
    {
        $stmt = $this->connection->prepare('
            select * from credentials where projectId = :projectId
        ',
            [
                'projectId' => $dto->getProjectId(),
            ],
        );
        $stmt->execute();
        $row = $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;
        $stmt->closeCursor();

        if (null === $row) {
            return null;
        }

        $row['isRequestWorkable'] = (bool) $row['isRequestWorkable'];

        return new CredentialDto(...$row);
    }

    public function changeIsRequestWorkable(int $id, bool $isRequestWorkable): void
    {
        $stmt = $this->connection->prepare('
            update credentials
            set isRequestWorkable = :isRequestWorkable
            where id = :id
        ', [
            'isRequestWorkable' => (int) $isRequestWorkable,
            'id' => $id,
        ]);
        $stmt->execute();
        $stmt->closeCursor();
    }
}

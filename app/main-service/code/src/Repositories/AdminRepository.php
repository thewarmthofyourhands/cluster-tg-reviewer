<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Dto\Repositories\AdminRepository\AddAdminDto;
use App\Dto\Repositories\AdminRepository\AdminDto;
use App\Dto\Repositories\AdminRepository\GetAdminByMessengerIdDto;
use App\Enums\MessengerTypeEnum;
use App\Interfaces\Repositories\AdminRepositoryInterface;
use Eva\Database\ConnectionInterface;
use Eva\Database\ConnectionStoreInterface;

class AdminRepository implements AdminRepositoryInterface
{
    private readonly ConnectionInterface $connection;

    public function __construct(ConnectionStoreInterface $connectionStore)
    {
        $this->connection = $connectionStore->get();
    }

    public function addAdmin(AddAdminDto $dto): void
    {
        $stmt = $this->connection->prepare('
            insert ignore into admins (nickname, messengerId, messengerType) values (:nickname, :messengerId, :messengerType)
        ', [
            'nickname' => $dto->getNickname(),
            'messengerId' => $dto->getMessengerId(),
            'messengerType' => $dto->getMessengerType()->value,
        ]);

        $stmt->execute();
        $stmt->closeCursor();
    }

    public function getAdminByMessengerId(GetAdminByMessengerIdDto $dto): AdminDto
    {
        $stmt = $this->connection->prepare(
            'select * from admins where messengerId = :messengerId and messengerType = :messengerType',
            [
                'messengerId' => $dto->getMessengerId(),
                'messengerType' => $dto->getMessengerType()->value,
            ],
        );
        $stmt->execute();
        $row = $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;
        $stmt->closeCursor();
        $row['messengerType'] = MessengerTypeEnum::from($row['messengerType']);

        return new AdminDto(...$row);
    }

    public function getAdminById(int $id): AdminDto
    {
        $stmt = $this->connection->prepare(
            'select * from admins where id = :id',
            [
                'id' => $id,
            ],
        );
        $stmt->execute();
        $row = $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;
        $stmt->closeCursor();
        $row['messengerType'] = MessengerTypeEnum::from($row['messengerType']);

        return new AdminDto(...$row);
    }

    public function findAdminById(int $id): null|AdminDto
    {
        $stmt = $this->connection->prepare(
            'select * from admins where id = :id',
            [
                'id' => $id,
            ],
        );
        $stmt->execute();
        $row = $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;
        $stmt->closeCursor();

        if (null === $row) {
            return null;
        }

        $row['messengerType'] = MessengerTypeEnum::from($row['messengerType']);

        return new AdminDto(...$row);
    }
}

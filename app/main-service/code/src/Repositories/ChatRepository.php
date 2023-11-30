<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Dto\Repositories\ChatRepository\AddChatDto;
use App\Dto\Repositories\ChatRepository\ChatDto;
use App\Dto\Repositories\ChatRepository\DeleteChatDto;
use App\Dto\Repositories\ChatRepository\GetChatByIdDto;
use App\Dto\Repositories\ChatRepository\GetChatByProjectIdDto;
use App\Enums\ChatStatusEnum;
use App\Enums\MessengerTypeEnum;
use App\Interfaces\Repositories\ChatRepositoryInterface;
use Eva\Database\ConnectionInterface;
use Eva\Database\ConnectionStoreInterface;

class  ChatRepository implements ChatRepositoryInterface
{
    private readonly ConnectionInterface $connection;

    public function __construct(ConnectionStoreInterface $connectionStore)
    {
        $this->connection = $connectionStore->get();
    }

    public function addChat(AddChatDto $dto): void
    {
        $stmt = $this->connection->prepare('
            insert into chats (projectId, messengerId, messengerType, status) values (:projectId, :messengerId, :messengerType, :status)
        ', [
            'projectId' => $dto->getProjectId(),
            'messengerId' => $dto->getMessengerId(),
            'messengerType' => $dto->getMessengerType()->value,
            'status' => $dto->getStatus()->value,
        ]);

        $stmt->execute();
        $stmt->closeCursor();
    }

    public function deleteChat(DeleteChatDto $dto): void
    {
        $stmt = $this->connection->prepare('
            delete from chats where projectId = :projectId
        ', [
            'projectId' => $dto->getProjectId(),
        ]);

        $stmt->execute();
        $stmt->closeCursor();
    }

    public function changeChatStatus(int $projectId, ChatStatusEnum $chatStatusEnum): void
    {
        $stmt = $this->connection->prepare('
            update chats
            set status = :status
            where projectId = :projectId
        ', [
            'projectId' => $projectId,
            'status' => $chatStatusEnum->value,
        ]);

        $stmt->execute();
        $stmt->closeCursor();
    }

    public function getChatById(GetChatByIdDto $dto): ChatDto
    {
        $stmt = $this->connection->prepare(
            'select * from chats where id = :id and projectId = :projectId',
            [
                'id' => $dto->getId(),
                'projectId' => $dto->getProjectId(),
            ],
        );
        $stmt->execute();
        $row = $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;
        $stmt->closeCursor();
        $row['messengerType'] = MessengerTypeEnum::from($row['messengerType']);
        $row['status'] = ChatStatusEnum::from($row['status']);

        return new ChatDto(...$row);
    }

    public function getChatByProjectId(GetChatByProjectIdDto $dto): ChatDto
    {
        $stmt = $this->connection->prepare(
            'select * from chats where projectId = :projectId',
            [
                'projectId' => $dto->getProjectId(),
            ],
        );
        $stmt->execute();
        $row = $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;
        $stmt->closeCursor();
        $row['messengerType'] = MessengerTypeEnum::from($row['messengerType']);
        $row['status'] = ChatStatusEnum::from($row['status']);

        return new ChatDto(...$row);
    }

    public function findChatByProjectId(GetChatByProjectIdDto $dto): null|ChatDto
    {
        $stmt = $this->connection->prepare(
            'select * from chats where projectId = :projectId',
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
        $row['messengerType'] = MessengerTypeEnum::from($row['messengerType']);
        $row['status'] = ChatStatusEnum::from($row['status']);

        return new ChatDto(...$row);
    }
}

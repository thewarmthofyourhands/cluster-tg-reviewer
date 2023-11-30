<?php

declare(strict_types=1);

namespace App\Services;

use App\Dto\Services\ChatService\AddChatDto;
use App\Dto\Services\ChatService\ChatDto;
use App\Dto\Services\ChatService\DeleteChatDto;
use App\Dto\Services\ChatService\GetChatByIdDto;
use App\Dto\Services\ChatService\GetChatByProjectIdDto;
use App\Dto\Services\ProjectService\GetProjectDto;
use App\Enums\ChatStatusEnum;
use App\Interfaces\Repositories\ChatRepositoryInterface;
use App\Interfaces\Services\ChatServiceInterface;
use App\Interfaces\Services\ProjectServiceInterface;

readonly class ChatService implements ChatServiceInterface
{
    public function __construct(
        private ChatRepositoryInterface $chatRepository,
    ) {}

    public function addChat(AddChatDto $dto): void
    {
        $this->chatRepository->addChat(new \App\Dto\Repositories\ChatRepository\AddChatDto(
            $dto->getProjectId(),
            $dto->getMessengerId(),
            $dto->getMessengerType(),
            $dto->getStatus(),
        ));
    }

    public function deleteChat(DeleteChatDto $dto): void
    {
        $this->chatRepository->deleteChat(new \App\Dto\Repositories\ChatRepository\DeleteChatDto(
            $dto->getProjectId(),
        ));
    }

    public function changeChatStatus(int $projectId, ChatStatusEnum $chatStatusEnum): void
    {
        $this->chatRepository->changeChatStatus($projectId, $chatStatusEnum);
    }

    public function getChatById(GetChatByIdDto $dto): ChatDto
    {
        $res = $this->chatRepository->getChatById(new \App\Dto\Repositories\ChatRepository\GetChatByIdDto(
            $dto->getProjectId(),
            $dto->getId(),
        ));

        return new ChatDto(
            $res->getProjectId(),
            $res->getId(),
            $res->getMessengerId(),
            $res->getMessengerType(),
            $res->getStatus(),
        );
    }

    public function getChatByProjectId(GetChatByProjectIdDto $dto): ChatDto
    {
        $res = $this->chatRepository->getChatByProjectId(new \App\Dto\Repositories\ChatRepository\GetChatByProjectIdDto(
            $dto->getProjectId(),
        ));

        return new ChatDto(
            $res->getProjectId(),
            $res->getId(),
            $res->getMessengerId(),
            $res->getMessengerType(),
            $res->getStatus(),
        );
    }

    public function findChatByProjectId(GetChatByProjectIdDto $dto): null|ChatDto
    {
        $res = $this->chatRepository->findChatByProjectId(new \App\Dto\Repositories\ChatRepository\GetChatByProjectIdDto(
            $dto->getProjectId(),
        ));

        return null === $res ? $res : new ChatDto(
            $res->getProjectId(),
            $res->getId(),
            $res->getMessengerId(),
            $res->getMessengerType(),
            $res->getStatus(),
        );
    }
}

<?php

declare(strict_types=1);

namespace App\UseCase;

use App\Dto\UseCase\ChatDto;
use App\Dto\UseCase\GetChatByProjectIdDto;
use App\Interfaces\Services\AdminServiceInterface;
use App\Interfaces\Services\ChatServiceInterface;

class GetChatByProjectIdHandler
{
    public function __construct(
        private readonly ChatServiceInterface $chatService,
        private readonly AdminServiceInterface $adminService,
    ) {}

    public function handle(GetChatByProjectIdDto $dto): null|ChatDto
    {
        $adminDto = $this->adminService->auth($dto->getAdminId());
        $res = $this->chatService->findChatByProjectId(new \App\Dto\Services\ChatService\GetChatByProjectIdDto(
            $dto->getProjectId(),
        ));

        return $res !== null ? new ChatDto(
            $res->getProjectId(),
            $res->getId(),
            $res->getMessengerId(),
            $res->getMessengerType(),
            $res->getStatus(),
        ) : null;
    }
}

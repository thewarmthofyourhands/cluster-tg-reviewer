<?php

declare(strict_types=1);

namespace App\UseCase;

use App\Dto\UseCase\ChatDto;
use App\Dto\UseCase\GetChatByIdDto;
use App\Interfaces\Services\AdminServiceInterface;
use App\Interfaces\Services\ChatServiceInterface;

class GetChatByIdHandler
{
    public function __construct(
        private readonly ChatServiceInterface $chatService,
        private readonly AdminServiceInterface $adminService,
    ) {}

    public function handle(GetChatByIdDto $dto): ChatDto
    {
        $adminDto = $this->adminService->auth($dto->getAdminId());
        $res = $this->chatService->getChatById(new \App\Dto\Services\ChatService\GetChatByIdDto(
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
}

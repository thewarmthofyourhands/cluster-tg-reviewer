<?php

declare(strict_types=1);

namespace App\UseCase;

use App\Dto\UseCase\DeleteChatDto;
use App\Interfaces\Services\AdminServiceInterface;
use App\Interfaces\Services\ChatServiceInterface;

class DeleteChatHandler
{
    public function __construct(
        private readonly ChatServiceInterface $chatService,
        private readonly AdminServiceInterface $adminService,
    ) {}

    public function handle(DeleteChatDto $dto): void
    {
        $adminDto = $this->adminService->auth($dto->getAdminId());
        $this->chatService->deleteChat(new \App\Dto\Services\ChatService\DeleteChatDto(
            $dto->getProjectId(),
        ));
    }
}

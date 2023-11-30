<?php

declare(strict_types=1);

namespace App\UseCase;

use App\Dto\UseCase\AddChatDto;
use App\Enums\ChatStatusEnum;
use App\Interfaces\Services\AdminServiceInterface;
use App\Interfaces\Services\ChatServiceInterface;

readonly class AddChatHandler
{
    public function __construct(
        private ChatServiceInterface $chatService,
        private AdminServiceInterface $adminService,
    ) {}

    public function handle(AddChatDto $dto): void
    {
        $adminDto = $this->adminService->auth($dto->getAdminId());
        $this->chatService->addChat(new \App\Dto\Services\ChatService\AddChatDto(
            $dto->getProjectId(),
            $dto->getMessengerId(),
            $dto->getMessengerType(),
            ChatStatusEnum::READY
        ));
    }
}

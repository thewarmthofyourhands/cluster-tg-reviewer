<?php

declare(strict_types=1);

namespace App\UseCase\Chat;

use App\Dto\UseCase\Chat\AddChatDto;
use App\Enums\Services\MessengerTypeEnum;
use App\Mappers\Dto\UseCase\Chat\AddChatDtoMapper;
use App\Services\AdminService;
use App\Services\ChatService;

readonly class AddChatHandler
{
    public function __construct(
        private ChatService $chatService,
        private AdminService $adminService,
    ) {}

    public function handle(AddChatDto $dto): void
    {
        $adminDto = $this->adminService->login($dto->getTelegramUserId());
        $this->chatService->store(new \App\Dto\Services\ChatService\AddChatDto(
            $adminDto->getId(),
            $dto->getProjectId(),
            $dto->getMessengerId(),
        ));
    }
}

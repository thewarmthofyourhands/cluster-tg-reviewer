<?php

declare(strict_types=1);

namespace App\UseCase\Chat;

use App\Dto\UseCase\Chat\ChatDto;
use App\Mappers\Dto\UseCase\Chat\ChatDtoMapper;
use App\Services\AdminService;
use App\Services\ChatService;

readonly class GetChatHandler
{
    public function __construct(
        private ChatService $chatService,
        private AdminService $adminService,
    ) {}

    public function handle(int $projectId, int $telegramUserId): null|ChatDto
    {
        $adminDto = $this->adminService->login($telegramUserId);
        $dto = $this->chatService->show($projectId, $adminDto->getId());

        if (null === $dto) {
            return null;
        }

        return ChatDtoMapper::convertServiceDtoToUseCaseDto($dto);
    }
}

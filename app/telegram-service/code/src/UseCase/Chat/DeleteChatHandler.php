<?php

declare(strict_types=1);

namespace App\UseCase\Chat;

use App\Services\AdminService;
use App\Services\ChatService;

readonly class DeleteChatHandler
{
    public function __construct(
        private ChatService $chatService,
        private AdminService $adminService,
    ) {}

    public function handle(int $projectId, int $telegramUserId): void
    {
        $adminDto = $this->adminService->login($telegramUserId);
        $this->chatService->delete($projectId, $adminDto->getId());
    }
}

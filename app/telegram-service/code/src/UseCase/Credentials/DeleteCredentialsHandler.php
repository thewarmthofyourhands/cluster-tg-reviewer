<?php

declare(strict_types=1);

namespace App\UseCase\Credentials;

use App\Services\AdminService;
use App\Services\CredentialsService;

readonly class DeleteCredentialsHandler
{
    public function __construct(
        private CredentialsService $credentialsService,
        private AdminService $adminService,
    ) {}

    public function handle(int $projectId, int $telegramUserId): void
    {
        $adminDto = $this->adminService->login($telegramUserId);
        $this->credentialsService->delete($projectId, $adminDto->getId());
    }
}

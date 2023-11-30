<?php

declare(strict_types=1);

namespace App\UseCase\Admins;

use App\Dto\UseCase\Admins\AdminDto;
use App\Services\AdminService;

readonly class LoginHandler
{
    public function __construct(
        private AdminService $adminService,
    ) {}

    public function handle(int $messengerId): AdminDto
    {
        $adminDto = $this->adminService->login($messengerId);

        return new AdminDto(
            $adminDto->getId(),
            $adminDto->getNickname(),
            $adminDto->getMessengerId(),
        );
    }
}

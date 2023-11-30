<?php

declare(strict_types=1);

namespace App\UseCase\Admins;

use App\Dto\UseCase\Admins\AddAdminDto;
use App\Services\AdminService;

readonly class AddAdminHandler
{
    public function __construct(
        private AdminService $adminService,
    ) {}

    public function handle(AddAdminDto $dto): void
    {
        $this->adminService->addAdmin(new \App\Dto\Services\AdminService\AddAdminDto(
            $dto->getNickname(),
            $dto->getMessengerId(),
        ));
    }
}

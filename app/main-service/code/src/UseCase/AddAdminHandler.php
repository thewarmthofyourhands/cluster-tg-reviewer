<?php

declare(strict_types=1);

namespace App\UseCase;

use App\Dto\UseCase\AddAdminDto;
use App\Interfaces\Services\AdminServiceInterface;

readonly class AddAdminHandler
{
    public function __construct(
        private AdminServiceInterface $adminService
    ) {}

    public function handle(AddAdminDto $dto): void
    {
        $this->adminService->addAdmin(new \App\Dto\Services\AdminService\AddAdminDto(
            $dto->getNickname(),
            $dto->getMessengerId(),
            $dto->getMessengerType(),
        ));
    }
}

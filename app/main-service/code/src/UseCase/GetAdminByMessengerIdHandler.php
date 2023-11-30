<?php

declare(strict_types=1);

namespace App\UseCase;

use App\Dto\UseCase\AdminDto;
use App\Dto\UseCase\GetAdminByMessengerIdDto;
use App\Interfaces\Services\AdminServiceInterface;

readonly class GetAdminByMessengerIdHandler
{
    public function __construct(
        private AdminServiceInterface $adminService
    ) {}

    public function handle(GetAdminByMessengerIdDto $dto): AdminDto
    {
        $res = $this->adminService->getAdminByMessengerId(
            new \App\Dto\Services\AdminService\GetAdminByMessengerIdDto(
                $dto->getMessengerId(),
                $dto->getMessengerType(),
            ),
        );

        return new AdminDto(
            $res->getId(),
            $res->getNickname(),
            $res->getMessengerId(),
            $res->getMessengerType(),
        );
    }
}

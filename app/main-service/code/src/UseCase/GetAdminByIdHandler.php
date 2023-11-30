<?php

declare(strict_types=1);

namespace App\UseCase;

use App\Dto\UseCase\AdminDto;
use App\Dto\UseCase\GetAdminByIdDto;
use App\Interfaces\Services\AdminServiceInterface;

readonly class GetAdminByIdHandler
{
    public function __construct(
        private AdminServiceInterface $adminService
    ) {}

    public function handle(GetAdminByIdDto $dto): AdminDto
    {
        $res = $this->adminService->getAdminById($dto->getId());

        return new AdminDto(
            $res->getId(),
            $res->getNickname(),
            $res->getMessengerId(),
            $res->getMessengerType(),
        );
    }
}

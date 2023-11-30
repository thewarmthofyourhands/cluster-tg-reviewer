<?php

declare(strict_types=1);

namespace App\Services;

use App\Dto\Services\AdminService\AddAdminDto;
use App\Dto\Services\AdminService\AdminDto;
use App\Dto\Services\AdminService\GetAdminByMessengerIdDto;
use App\Exceptions\Application\ApplicationErrorCodeEnum;
use App\Exceptions\Application\AuthenticateException;
use App\Interfaces\Repositories\AdminRepositoryInterface;
use App\Interfaces\Services\AdminServiceInterface;

readonly class AdminService implements AdminServiceInterface
{
    public function __construct(
        private AdminRepositoryInterface $adminRepository,
    ) {}

    public function addAdmin(AddAdminDto $dto): void
    {
        $this->adminRepository->addAdmin(
            new \App\Dto\Repositories\AdminRepository\AddAdminDto(
                $dto->getNickname(),
                $dto->getMessengerId(),
                $dto->getMessengerType(),
            ),
        );
    }

    public function getAdminByMessengerId(GetAdminByMessengerIdDto $dto): AdminDto
    {
        $res = $this->adminRepository->getAdminByMessengerId(
            new \App\Dto\Repositories\AdminRepository\GetAdminByMessengerIdDto(
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

    public function getAdminById(int $id): AdminDto
    {
        $res = $this->adminRepository->getAdminById($id);

        return new AdminDto(
            $res->getId(),
            $res->getNickname(),
            $res->getMessengerId(),
            $res->getMessengerType(),
        );
    }

    /**
     * @throws AuthenticateException
     */
    public function auth(int $id): AdminDto
    {
        $res = $this->adminRepository->findAdminById($id);

        if (null === $res) {
            throw new AuthenticateException(ApplicationErrorCodeEnum::ADMIN_NOT_FOUND);
        }

        return new AdminDto(
            $res->getId(),
            $res->getNickname(),
            $res->getMessengerId(),
            $res->getMessengerType(),
        );
    }
}

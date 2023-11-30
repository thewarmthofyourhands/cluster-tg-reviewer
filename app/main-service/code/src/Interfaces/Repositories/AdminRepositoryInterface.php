<?php

declare(strict_types=1);

namespace App\Interfaces\Repositories;

use App\Dto\Repositories\AdminRepository\AddAdminDto;
use App\Dto\Repositories\AdminRepository\AdminDto;
use App\Dto\Repositories\AdminRepository\GetAdminByMessengerIdDto;

interface AdminRepositoryInterface
{
    public function addAdmin(AddAdminDto $dto): void;
    public function getAdminByMessengerId(GetAdminByMessengerIdDto $dto): AdminDto;
    public function getAdminById(int $id): AdminDto;
    public function findAdminById(int $id): null|AdminDto;
}

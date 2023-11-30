<?php

declare(strict_types=1);

namespace App\Interfaces\Services;

use App\Dto\Services\AdminService\AddAdminDto;
use App\Dto\Services\AdminService\AdminDto;
use App\Dto\Services\AdminService\GetAdminByMessengerIdDto;

interface AdminServiceInterface
{
    public function addAdmin(AddAdminDto $dto): void;
    public function getAdminByMessengerId(GetAdminByMessengerIdDto $dto): AdminDto;
    public function getAdminById(int $id): AdminDto;
    public function auth(int $id): AdminDto;
}

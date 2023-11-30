<?php

declare(strict_types=1);

namespace App\Services;

use App\Dto\Services\AdminService\AddAdminDto;
use App\Dto\Services\AdminService\AdminDto;
use App\Enums\Services\MessengerTypeEnum;
use App\Services\RequestServices\MainServiceRequestService;

readonly class AdminService
{
    public function __construct(
        private MainServiceRequestService $mainServiceRequest,
    ) {}

    public function addAdmin(AddAdminDto $dto): void
    {
        $data = $dto->toArray();
        $data['messengerType'] = MessengerTypeEnum::TELEGRAM->value;
        $this->mainServiceRequest->addAdmin($data);
    }

    public function login(int $messengerId): AdminDto
    {
        $data = $this->mainServiceRequest->login($messengerId, MessengerTypeEnum::TELEGRAM->value);
        $data['messengerType'] = MessengerTypeEnum::from($data['messengerType']);

        return new AdminDto(...$data);
    }
}

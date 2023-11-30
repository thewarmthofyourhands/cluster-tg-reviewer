<?php

declare(strict_types=1);

namespace App\Mappers\Dto\UseCase;

use App\Dto\UseCase\AddAdminDto;
use App\Dto\UseCase\AdminDto;
use App\Dto\UseCase\GetAdminByMessengerIdDto;
use App\Enums\MessengerTypeEnum;

readonly class AdminDtoMapper
{
    public static function convertAdminDtoToArray(AdminDto $adminDto): array
    {
        $data = $adminDto->toArray();
        $data['messengerType'] = $adminDto->getMessengerType()->value;

        return $data;
    }

    public static function convertDataToGetAdminByMessengerIdDto(array $data): GetAdminByMessengerIdDto
    {
        $data['messengerType'] = MessengerTypeEnum::from($data['messengerType']);

        return new GetAdminByMessengerIdDto(...$data);
    }

    public static function convertDataToAddAdminDto(array $data): AddAdminDto
    {
        $data['messengerType'] = MessengerTypeEnum::from($data['messengerType']);

        return new AddAdminDto(...$data);
    }
}

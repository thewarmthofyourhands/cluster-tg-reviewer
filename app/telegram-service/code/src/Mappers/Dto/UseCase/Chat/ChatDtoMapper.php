<?php

declare(strict_types=1);

namespace App\Mappers\Dto\UseCase\Chat;

use App\Dto\UseCase\Chat\ChatDto;
use App\Enums\UseCase\Chat\ChatStatusEnum;
use App\Enums\UseCase\MessengerTypeEnum;

readonly class ChatDtoMapper
{
    private static function getMessengerType(\App\Enums\Services\MessengerTypeEnum $messengerTypeEnum): MessengerTypeEnum
    {
        return match ($messengerTypeEnum) {
            \App\Enums\Services\MessengerTypeEnum::TELEGRAM => MessengerTypeEnum::TELEGRAM,
            \App\Enums\Services\MessengerTypeEnum::SLACK => MessengerTypeEnum::SLACK,
        };
    }

    private static function getStatus(\App\Enums\Services\ChatService\ChatStatusEnum $chatStatusEnum): ChatStatusEnum
    {
        return match ($chatStatusEnum) {
            \App\Enums\Services\ChatService\ChatStatusEnum::READY => ChatStatusEnum::READY,
            \App\Enums\Services\ChatService\ChatStatusEnum::NOT_EXIST => ChatStatusEnum::NOT_EXIST,
        };
    }

    public static function convertServiceDtoToUseCaseDto(\App\Dto\Services\ChatService\ChatDto $dto): ChatDto
    {
        $data = $dto->toArray();
        $data['messengerType'] = self::getMessengerType($dto->getMessengerType());
        $data['status'] = self::getStatus($dto->getStatus());

        return new ChatDto(...$data);
    }
}

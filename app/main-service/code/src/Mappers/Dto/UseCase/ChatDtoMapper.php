<?php

declare(strict_types=1);

namespace App\Mappers\Dto\UseCase;

use App\Dto\UseCase\AddChatDto;
use App\Dto\UseCase\ChatDto;
use App\Enums\MessengerTypeEnum;

readonly class ChatDtoMapper
{
    public static function convertDataToAddChatDto(array $data): AddChatDto
    {
        $data['messengerType'] = MessengerTypeEnum::from($data['messengerType']);

        return new AddChatDto(...$data);
    }

    public static function convertDtoToArray(ChatDto $dto): array
    {
        $data = $dto->toArray();
        $data['messengerType'] = $dto->getMessengerType()->value;
        $data['status'] = $dto->getStatus()->value;

        return $data;
    }
}

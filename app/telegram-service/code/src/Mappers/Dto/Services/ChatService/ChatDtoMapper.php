<?php

declare(strict_types=1);

namespace App\Mappers\Dto\Services\ChatService;

use App\Dto\Services\ChatService\ChatDto;
use App\Enums\Services\ChatService\ChatStatusEnum;
use App\Enums\Services\MessengerTypeEnum;

readonly class ChatDtoMapper
{
    public static function convertArrayToDto(array $data): ChatDto
    {
        $data['messengerType'] = MessengerTypeEnum::from($data['messengerType']);
        $data['status'] = ChatStatusEnum::from($data['status']);

        return new ChatDto(...$data);
    }
}

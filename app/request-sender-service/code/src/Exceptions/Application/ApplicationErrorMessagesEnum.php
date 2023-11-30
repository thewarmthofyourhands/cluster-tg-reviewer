<?php

declare(strict_types=1);

namespace App\Exceptions\Application;

use ValueError;

enum ApplicationErrorMessagesEnum: string
{
    case REQUIRED_AUTHORIZATION_HEADER = 'Authorization header is required';
    case UNEXPECTED_REQUEST_RESPONSE_STATUS = 'Unexpected response status from sent request';
    case TELEGRAM_SERVICE_CHAT_NOT_EXIST = 'Chat not found';

    public static function fromName(string $name): self
    {
        foreach (self::cases() as $status) {
            if ($name === $status->name){
                return $status;
            }
        }

        throw new ValueError("$name is not a valid backing value for enum " . self::class );
    }
}

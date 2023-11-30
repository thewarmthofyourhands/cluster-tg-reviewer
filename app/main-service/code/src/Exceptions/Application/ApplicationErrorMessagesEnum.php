<?php

declare(strict_types=1);

namespace App\Exceptions\Application;

use ValueError;

enum ApplicationErrorMessagesEnum: string
{
    case REQUIRED_AUTHORIZATION_HEADER = 'Authorization header is required';
    case ADMIN_NOT_FOUND = 'Admin not found';
    case ENTITY_NOT_FOUND = 'Entity not found';
    case CHAT_DOES_NOT_EXIST = 'Chat doesn\'t exist anymore, please share new chat';

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

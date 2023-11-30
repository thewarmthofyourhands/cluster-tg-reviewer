<?php

declare(strict_types=1);

namespace App\Exceptions\Application;

use ValueError;

enum ApplicationErrorMessagesEnum: string
{
    case ENTITY_NOT_FOUND = 'Entity not found';
    case INVALID_GIR_REPOSITORY_URL = 'Invalid git repository url';
    case PROJECT_NAME_ALREADY_EXIST = 'That project name already exist';
    case INVALID_CREDENTIAL_DATE_EXPIRED = 'Invalid credential date expired';
    case CHAT_DOES_NOT_EXIST = 'Chat doesn\'t exist, please share new chat';

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

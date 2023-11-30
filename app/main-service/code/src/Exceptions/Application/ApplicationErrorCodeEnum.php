<?php

declare(strict_types=1);

namespace App\Exceptions\Application;

enum ApplicationErrorCodeEnum: int
{
    case REQUIRED_AUTHORIZATION_HEADER = 40101;
    case ADMIN_NOT_FOUND = 40102;
    case ENTITY_NOT_FOUND = 40001;
    case CHAT_DOES_NOT_EXIST = 40002;
}

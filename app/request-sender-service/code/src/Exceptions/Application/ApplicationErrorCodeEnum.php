<?php

declare(strict_types=1);

namespace App\Exceptions\Application;

enum ApplicationErrorCodeEnum: int
{
    case REQUIRED_AUTHORIZATION_HEADER = 40101;
    case UNEXPECTED_REQUEST_RESPONSE_STATUS = 40001;
    case TELEGRAM_SERVICE_CHAT_NOT_EXIST = 40010;
}

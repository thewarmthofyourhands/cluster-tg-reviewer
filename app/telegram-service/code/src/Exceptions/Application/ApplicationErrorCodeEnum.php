<?php

declare(strict_types=1);

namespace App\Exceptions\Application;

enum ApplicationErrorCodeEnum: int
{
    case ENTITY_NOT_FOUND = 40001;
    case INVALID_GIR_REPOSITORY_URL = 40002;
    case PROJECT_NAME_ALREADY_EXIST = 40003;
    case INVALID_CREDENTIAL_DATE_EXPIRED = 40004;
    case CHAT_DOES_NOT_EXIST = 40005;
}

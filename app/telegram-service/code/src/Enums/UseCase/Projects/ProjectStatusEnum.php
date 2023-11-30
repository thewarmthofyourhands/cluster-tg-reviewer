<?php

declare(strict_types=1);

namespace App\Enums\UseCase\Projects;

enum ProjectStatusEnum: string
{
    case WITHOUT_CREDENTIAL = 'Not ready, please add credentials';
    case INVALID_CREDENTIAL = 'Invalid credentials, please add correct credentials';
    case EXPIRED_CREDENTIAL = 'Credentials has expired, please generate new token';
    case WITHOUT_CHAT = 'Not ready, please add group chat';
    case INVALID_CHAT = 'Chat not exist, please add correctly chat';
    case WITHOUT_DEVELOPERS = 'Not ready, please add developers';
    case READY = 'Ready';
}

<?php

declare(strict_types=1);

namespace App\Enums\Services\ProjectService;

enum ProjectStatusEnum: string
{
    case WITHOUT_CREDENTIAL = 'WITHOUT_CREDENTIAL';
    case INVALID_CREDENTIAL = 'INVALID_CREDENTIAL';
    case EXPIRED_CREDENTIAL = 'EXPIRED_CREDENTIAL';
    case WITHOUT_CHAT = 'WITHOUT_CHAT';
    case INVALID_CHAT = 'INVALID_CHAT';
    case WITHOUT_DEVELOPERS = 'WITHOUT_DEVELOPERS';
    case READY = 'READY';
}

<?php

declare(strict_types=1);

namespace App\Enums\Services\ChatService;

enum ChatStatusEnum: string
{
    case READY = 'READY';
    case NOT_EXIST = 'NOT_EXIST';
}

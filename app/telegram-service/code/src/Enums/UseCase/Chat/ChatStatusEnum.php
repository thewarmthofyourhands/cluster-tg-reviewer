<?php

declare(strict_types=1);

namespace App\Enums\UseCase\Chat;

enum ChatStatusEnum: string
{
    case READY = 'Ready';
    case NOT_EXIST = 'Not exist';
}

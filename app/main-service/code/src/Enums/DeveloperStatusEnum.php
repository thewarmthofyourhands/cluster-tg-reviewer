<?php

declare(strict_types=1);

namespace App\Enums;

enum DeveloperStatusEnum: string
{
    case READY = 'READY';
    case STOP = 'STOP';
}

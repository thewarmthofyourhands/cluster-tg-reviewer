<?php

declare(strict_types=1);

namespace App\Enums\Services\DeveloperService;

enum DeveloperStatusEnum: string
{
    case READY = 'READY';
    case STOP = 'STOP';
}

<?php

declare(strict_types=1);

namespace App\Enums\UseCase\Developers;

enum DeveloperStatusEnum: string
{
    case READY = 'Ready';
    case STOP = 'Stop';
}

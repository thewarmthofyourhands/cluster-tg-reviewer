<?php

declare(strict_types=1);

namespace App\Enums;

enum MessengerTypeEnum: string
{
    case TELEGRAM = 'TELEGRAM';
    case SLACK = 'SLACK';
}

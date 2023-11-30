<?php

declare(strict_types=1);

namespace App\Enums\Services\ProjectService;

enum GitServiceTypeEnum: string
{
    case GIT_HUB = 'GIT_HUB';
    case GIT_LAB = 'GIT_LAB';
    case BITBUCKET = 'BITBUCKET';
}

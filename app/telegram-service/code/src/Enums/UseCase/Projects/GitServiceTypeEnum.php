<?php

declare(strict_types=1);

namespace App\Enums\UseCase\Projects;

enum GitServiceTypeEnum: string
{
    case GIT_HUB = 'Github';
    case GIT_LAB = 'Gitlab';
    case BITBUCKET = 'Bitbucket';
}

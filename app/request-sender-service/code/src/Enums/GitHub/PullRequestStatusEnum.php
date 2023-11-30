<?php

declare(strict_types=1);

namespace App\Enums\GitHub;

enum PullRequestStatusEnum: string
{
    case OPEN = 'OPEN';
    case PENDING = 'PENDING';
    case REVIEWING = 'REVIEWING';
    case APPROVED = 'APPROVED';
    case CLOSED = 'CLOSED';
}

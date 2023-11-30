<?php

declare(strict_types=1);

namespace App\Enums\UseCase\PullRequests;

enum PullRequestStatusEnum: string
{
    case OPEN = 'Open';
    case PENDING = 'Pending review';
    case REVIEWING = 'Reviewing';
    case APPROVED = 'Approved';
    case CLOSED = 'Closed';
}

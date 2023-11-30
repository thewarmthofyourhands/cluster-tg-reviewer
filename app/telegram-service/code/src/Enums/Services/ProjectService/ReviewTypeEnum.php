<?php

declare(strict_types=1);

namespace App\Enums\Services\ProjectService;

enum ReviewTypeEnum: string
{
    case TEAM_LEAD_REVIEW = 'TEAM_LEAD_REVIEW';
    case CROSS_REVIEW = 'CROSS_REVIEW';
    case CROSS_REVIEW_WITHOUT_TEAM_LEAD = 'CROSS_REVIEW_WITHOUT_TEAM_LEAD';
}

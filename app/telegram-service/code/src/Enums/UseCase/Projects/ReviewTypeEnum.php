<?php

declare(strict_types=1);

namespace App\Enums\UseCase\Projects;

enum ReviewTypeEnum: string
{
    case TEAM_LEAD_REVIEW = 'Team lead review only';
    case CROSS_REVIEW = 'Cross review';
    case CROSS_REVIEW_WITHOUT_TEAM_LEAD = 'Cross review without team lead';
}

<?php

declare(strict_types=1);

namespace App\Enums;

enum ReviewTypeEnum: string
{
    case TEAM_LEAD_REVIEW = 'TEAM_LEAD_REVIEW';
    case CROSS_REVIEW = 'CROSS_REVIEW';
    case CROSS_REVIEW_WITHOUT_TEAM_LEAD = 'CROSS_REVIEW_WITHOUT_TEAM_LEAD';
//    case VOLITION_REVIEW = 'VOLITION_REVIEW';//Кто хочет
//    case APPOINT_REVIEW = 'APPOINT_REVIEW';//Кого назначит админ
}

<?php

declare(strict_types=1);

namespace App\Enums\Resources;

enum ApplicationResourcesEnum
{
    case SYNC_PULL_REQUESTS_PROCESS;
//./kafka-topics.sh --create --topic SYNC_PULL_REQUESTS_PROCESS --bootstrap-server localhost:9092 --partitions 10
    case REVIEW_STATUS_PROCESS;
//./kafka-topics.sh --create --topic REVIEW_STATUS_PROCESS --bootstrap-server localhost:9092 --partitions 10
    case CAPTURE_OPEN_PULL_REQUESTS_PROCESS;
//./kafka-topics.sh --create --topic CAPTURE_OPEN_PULL_REQUESTS_PROCESS --bootstrap-server localhost:9092 --partitions 10
}

<?php

declare(strict_types=1);

namespace App\Commands\Queue;

use App\Enums\Resources\ApplicationResourcesEnum;
use App\Queue\KafkaConsumerQueue;
use App\UseCase\GetReviewStatusHandler;
use Eva\Console\ArgvInput;

readonly class ReviewStatusQueueCommand
{
    public function __construct(
        private GetReviewStatusHandler $getReviewStatusHandler,
        private KafkaConsumerQueue $kafkaConsumerQueue,
    ) {}

    public function execute(ArgvInput $argvInput): void
    {
        $this->kafkaConsumerQueue->createSubscriber(
            ApplicationResourcesEnum::REVIEW_STATUS_PROCESS->name,
            'k8s-group',
        );

        while ($message = $this->kafkaConsumerQueue->consume()) {
            $projectIdList = json_decode($message->getBody(), true);
            $this->getReviewStatusHandler->handle($projectIdList);
        }
    }
}

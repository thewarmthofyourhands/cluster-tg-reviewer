<?php

declare(strict_types=1);

namespace App\Commands\Queue;

use App\Enums\Resources\ApplicationResourcesEnum;
use App\Queue\KafkaConsumerQueue;
use App\UseCase\CaptureOpenPullRequestsHandler;
use Eva\Console\ArgvInput;

readonly class CaptureOpenPullRequestQueueCommand
{
    public function __construct(
        private CaptureOpenPullRequestsHandler $captureOpenPullRequestsHandler,
        private KafkaConsumerQueue $kafkaConsumerQueue,
    ) {}

    public function execute(ArgvInput $argvInput): void
    {
        $this->kafkaConsumerQueue->createSubscriber(
            ApplicationResourcesEnum::CAPTURE_OPEN_PULL_REQUESTS_PROCESS->name,
            'k8s-group',
        );

        while($message = $this->kafkaConsumerQueue->consume()) {
            $projectIdList = json_decode($message->getBody(), true);
            $this->captureOpenPullRequestsHandler->handle($projectIdList);
        }
    }
}

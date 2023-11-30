<?php

declare(strict_types=1);

namespace App\Commands;

use App\Enums\Resources\ApplicationResourcesEnum;
use App\Locker\RedisLocker;
use App\Queue\KafkaProduceQueue;
use App\UseCase\GetProjectIdListHandler;
use App\UseCase\GetReviewStatusHandler;
use Eva\Console\ArgvInput;

readonly class ReviewStatusCommand
{
    public function __construct(
        private RedisLocker $redisLocker,
        private KafkaProduceQueue $kafkaProduceQueue,
        private GetProjectIdListHandler $getProjectIdListHandler,
    ) {}

    public function execute(ArgvInput $argvInput): void
    {
        if ($this->redisLocker->hasLock(ApplicationResourcesEnum::REVIEW_STATUS_PROCESS)) {
            return;
        }

        $this->redisLocker->setLock(ApplicationResourcesEnum::REVIEW_STATUS_PROCESS, 60);
        $projectIdList = $this->getProjectIdListHandler->handle();
        $projectIdListChunk = array_chunk($projectIdList, 100);
        $projectIdListChunk = array_map(
            static fn(array $projectIdList) => ['body' => json_encode($projectIdList)],
            $projectIdListChunk,
        );
        $this->kafkaProduceQueue->produce(
            ApplicationResourcesEnum::REVIEW_STATUS_PROCESS->name,
            random_int(0, 9),
            $projectIdListChunk
        );
    }
}

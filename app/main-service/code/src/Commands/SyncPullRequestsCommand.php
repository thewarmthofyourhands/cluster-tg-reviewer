<?php

declare(strict_types=1);

namespace App\Commands;

use App\Enums\Resources\ApplicationResourcesEnum;
use App\Locker\RedisLocker;
use App\Queue\KafkaProduceQueue;
use App\UseCase\GetProjectIdListHandler;
use Eva\Console\ArgvInput;

readonly class SyncPullRequestsCommand
{
    public function __construct(
        private RedisLocker $redisLocker,
        private KafkaProduceQueue $kafkaProduceQueue,
        private GetProjectIdListHandler $getProjectIdListHandler,
    ) {}

    public function execute(ArgvInput $argvInput): void
    {
        if ($this->redisLocker->hasLock(ApplicationResourcesEnum::SYNC_PULL_REQUESTS_PROCESS)) {
            return;
        }

        $this->redisLocker->setLock(ApplicationResourcesEnum::SYNC_PULL_REQUESTS_PROCESS, 100);
        $projectIdList = $this->getProjectIdListHandler->handle();
        $projectIdListChunk = array_chunk($projectIdList, 100);
        $projectIdListChunk = array_map(
            static fn(array $projectIdList) => ['body' => json_encode($projectIdList)],
            $projectIdListChunk,
        );
        $this->kafkaProduceQueue->produce(
            ApplicationResourcesEnum::SYNC_PULL_REQUESTS_PROCESS->name,
            random_int(0, 9),
            $projectIdListChunk
        );
    }
}

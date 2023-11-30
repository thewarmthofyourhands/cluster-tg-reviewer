<?php

declare(strict_types=1);

namespace App\Locker;

use App\Enums\Resources\ApplicationResourcesEnum;
use Predis\Client;

readonly class RedisLocker
{
    private Client $redis;

    public function __construct(string $connectionUrl)
    {
        $this->redis = new Client($connectionUrl);
    }

    public function setLock(ApplicationResourcesEnum $lockName, null|int $ttl = null): void
    {
        if (null === $ttl) {
            $this->redis->set($lockName->name, 1);
            return;
        }

        $this->redis->set($lockName->name, 1, 'EX', $ttl);
    }

    public function unsetLock(ApplicationResourcesEnum $lockName): void
    {
        $this->redis->set($lockName->name, 0);
    }

    public function hasLock(ApplicationResourcesEnum $lockName): bool
    {
        $lockValue = $this->redis->get($lockName->name);

        return (bool) $lockValue;
    }
}

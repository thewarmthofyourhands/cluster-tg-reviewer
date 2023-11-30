<?php

declare(strict_types=1);

namespace App\Repositories\Telegram;

use Eva\Database\ConnectionInterface;
use Eva\Database\ConnectionStoreInterface;

class TelegramTsRepository
{
    private readonly ConnectionInterface $connection;

    public function __construct(ConnectionStoreInterface $connectionStore)
    {
        $this->connection = $connectionStore->get();
    }

    public function edit(int $ts): void
    {
        $stmt = $this->connection->prepare(
            'update telegramTs set telegramTs.value = :ts where telegramTs.id = 1',
            ['ts' => $ts],
        );
        $stmt->execute();
        $stmt->closeCursor();
    }

    public function showWithLock(): null|array
    {
        $stmt = $this->connection->prepare('select * from telegramTs where telegramTs.id = 1 for update nowait');
        $stmt->execute();
        $ts = $stmt->fetch();
        $stmt->closeCursor();

        return $ts ?: null;
    }

    public function store(string $ts = '0'): void
    {
        $stmt = $this->connection->prepare('
            insert into telegramTs (id, value) values (:id, :ts)
        ', ['id' => 1, 'ts' => $ts]);
        $stmt->execute();
        $stmt->closeCursor();
    }
}

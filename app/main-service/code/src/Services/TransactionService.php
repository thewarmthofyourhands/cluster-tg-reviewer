<?php

declare(strict_types=1);

namespace App\Services;

use Eva\Database\ConnectionInterface;
use Eva\Database\ConnectionStoreInterface;

class TransactionService
{
    private readonly ConnectionInterface $connection;

    public function __construct(ConnectionStoreInterface $connectionStore)
    {
        $this->connection = $connectionStore->get();
    }

    public function beginTransaction(): void
    {
        $this->connection->beginTransaction();
    }

    public function commit(): void
    {
        $this->connection->commit();
    }

    public function rollback(): void
    {
        $this->connection->rollback();
    }
}

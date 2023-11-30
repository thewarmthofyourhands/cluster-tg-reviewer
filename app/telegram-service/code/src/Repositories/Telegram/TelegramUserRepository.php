<?php

declare(strict_types=1);

namespace App\Repositories\Telegram;

use App\Dto\Repositories\Telegram\TelegramUserRepository\TelegramUserDto;
use Eva\Database\ConnectionInterface;
use Eva\Database\ConnectionStoreInterface;
use PDO;

readonly class TelegramUserRepository
{
    private ConnectionInterface $connection;

    public function __construct(ConnectionStoreInterface $connectionStore)
    {
        $this->connection = $connectionStore->get();
    }

    public function addTelegramUser(int $telegramId, string $username): void
    {
        $stmt = $this->connection->prepare('
            insert ignore into telegramUser (telegramId, username) values (:telegramId, :username)
        ', ['telegramId' => $telegramId, 'username' => $username]);
        $stmt->execute();
        $stmt->closeCursor();
    }

    public function editData(int $telegramId, string $data): void
    {
        $stmt = $this->connection->prepare(
            'update telegramUser set telegramUser.data = :data where telegramUser.telegramId = :telegramId',
            [
                'telegramId' => $telegramId,
                'data' => $data,
            ],
        );
        $stmt->execute();
        $stmt->closeCursor();
    }

    public function findByTelegramId(int $telegramId): null|TelegramUserDto
    {
        $stmt = $this->connection->prepare(
            'select * from telegramUser where telegramUser.telegramId = :telegramId',
            ['telegramId' => $telegramId],
        );
        $stmt->execute();
        $telegramUserData = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        $stmt->closeCursor();

        if (null === $telegramUserData) {
            return null;
        }

        return new TelegramUserDto(
            ...$telegramUserData
        );
    }
}

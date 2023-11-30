<?php

declare(strict_types=1);

namespace App\Services\Telegram;

use App\Dto\Services\Telegram\TelegramUserService\TelegramUserDto;
use App\Repositories\Telegram\TelegramUserRepository;

readonly class TelegramUserService
{
    public function __construct(private TelegramUserRepository $telegramUserRepository) {}

    public function editData(int $telegramId, string $data): void
    {
        $this->telegramUserRepository->editData($telegramId, $data);
    }

    public function getByTelegramId(int $telegramId): TelegramUserDto
    {
        $dto = $this->telegramUserRepository->findByTelegramId($telegramId);

        if (null === $dto) {
            throw new \RuntimeException('Entity not exist');
        }

        return new TelegramUserDto(
            ...$dto->toArray(),
        );
    }

    public function addTelegramUser(int $telegramId, string $username): void
    {
        $this->telegramUserRepository->addTelegramUser($telegramId, $username);
    }
}

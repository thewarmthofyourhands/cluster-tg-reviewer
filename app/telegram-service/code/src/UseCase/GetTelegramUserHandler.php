<?php

declare(strict_types=1);

namespace App\UseCase;

use App\Dto\UseCase\TelegramUserDto;
use App\Services\Telegram\TelegramUserService;

readonly class GetTelegramUserHandler
{
    public function __construct(
        private TelegramUserService $telegramUserService,
    ) {}

    public function handle(int $telegramId, string $username): TelegramUserDto
    {
        $this->telegramUserService->addTelegramUser($telegramId, $username);
        $dto = $this->telegramUserService->getByTelegramId($telegramId);

        return new TelegramUserDto(...$dto->toArray());
    }
}

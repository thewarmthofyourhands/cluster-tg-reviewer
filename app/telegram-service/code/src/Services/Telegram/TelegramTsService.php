<?php

declare(strict_types=1);

namespace App\Services\Telegram;

use App\Repositories\Telegram\TelegramTsRepository;

readonly class TelegramTsService
{
    public function __construct(private TelegramTsRepository $telegramTsRepository) {}

    public function edit(int $ts): void
    {
        $this->telegramTsRepository->edit($ts);
    }

    public function show(): null|array
    {
        return $this->telegramTsRepository->showWithLock();
    }

    public function store(): void
    {
        $this->telegramTsRepository->store();
    }
}

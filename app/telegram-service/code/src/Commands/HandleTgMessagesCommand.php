<?php

declare(strict_types=1);

namespace App\Commands;

use App\Handler\TelegramUpdateHandler;
use Eva\Console\ArgvInput;

readonly class HandleTgMessagesCommand
{
    public function __construct(
        private TelegramUpdateHandler $telegramUpdateHandler,
    ) {}

    public function execute(ArgvInput $argvInput): void
    {
        $this->telegramUpdateHandler->handle();
        sleep(10);

    }
}

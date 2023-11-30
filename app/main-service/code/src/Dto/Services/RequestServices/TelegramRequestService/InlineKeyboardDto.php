<?php

declare(strict_types=1);

namespace App\Dto\Services\RequestServices\TelegramRequestService;

final readonly class InlineKeyboardDto
{
    public function __construct(
        private string $text,
        private string $callback_data,
    ) {}

    public function getText(): string
    {
        return $this->text;
    }

    public function getCallbackData(): string
    {
        return $this->callback_data;
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}

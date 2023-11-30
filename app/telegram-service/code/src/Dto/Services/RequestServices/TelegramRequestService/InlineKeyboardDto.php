<?php

declare(strict_types=1);

namespace App\Dto\Services\RequestServices\TelegramRequestService;

final readonly class InlineKeyboardDto
{
    public function __construct(
        private string $text,
        private string $callbackData,
    ) {}

    public function getText(): string
    {
        return $this->text;
    }

    public function getCallbackData(): string
    {
        return $this->callbackData;
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}

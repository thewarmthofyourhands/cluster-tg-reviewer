<?php

declare(strict_types=1);

namespace App\Dto\Services\RequestServices\TelegramRequestService;

final readonly class ReplyMarkupDto
{
    public function __construct(
        private array $inline_keyboard,
        private array $keyboard,
        private bool $one_time_keyboard,
        private bool $is_persistent,
        private bool $remove_keyboard,
    ) {}

    public function getInlineKeyboard(): array
    {
        return $this->inline_keyboard;
    }

    public function getKeyboard(): array
    {
        return $this->keyboard;
    }

    public function getOneTimeKeyboard(): bool
    {
        return $this->one_time_keyboard;
    }

    public function getIsPersistent(): bool
    {
        return $this->is_persistent;
    }

    public function getRemoveKeyboard(): bool
    {
        return $this->remove_keyboard;
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}

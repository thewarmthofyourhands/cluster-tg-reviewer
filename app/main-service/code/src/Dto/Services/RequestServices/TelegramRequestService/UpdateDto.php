<?php

declare(strict_types=1);

namespace App\Dto\Services\RequestServices\TelegramRequestService;

final readonly class UpdateDto
{
    public function __construct(
        private int $update_id,
        private null|array $message,
        private null|array $inline_query,
        private null|array $callback_query,
    ) {}

    public function getUpdateId(): int
    {
        return $this->update_id;
    }

    public function getMessage(): null|array
    {
        return $this->message;
    }

    public function getInlineQuery(): null|array
    {
        return $this->inline_query;
    }

    public function getCallbackQuery(): null|array
    {
        return $this->callback_query;
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}

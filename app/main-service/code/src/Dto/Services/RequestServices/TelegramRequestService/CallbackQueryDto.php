<?php

declare(strict_types=1);

namespace App\Dto\Services\RequestServices\TelegramRequestService;

final readonly class CallbackQueryDto
{
    public function __construct(
        private string $id,
        private array $from,
        private array $message,
        private null|string $data,
    ) {}

    public function getId(): string
    {
        return $this->id;
    }

    public function getFrom(): array
    {
        return $this->from;
    }

    public function getMessage(): array
    {
        return $this->message;
    }

    public function getData(): null|string
    {
        return $this->data;
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}

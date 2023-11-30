<?php

declare(strict_types=1);

namespace App\Dto\Infrastructure;

final readonly class TelegramActionDataDto
{
    public function __construct(
        private string $action,
        private null|array $data,
    ) {}

    public function getAction(): string
    {
        return $this->action;
    }

    public function getData(): null|array
    {
        return $this->data;
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}

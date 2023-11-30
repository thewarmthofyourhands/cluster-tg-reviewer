<?php

declare(strict_types=1);

namespace App\Rest;

use Eva\Http\Builder\JsonResponseMessageBuilder;

readonly class RestApiResponse
{
    public function __construct(
        private null|array $data = null,
        private int $code = 200,
        private string $message = 'ok',
    ) {}

    public function getData(): ?array
    {
        return $this->data;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function toArray(): array
    {
        return [
            'data' => $this->data,
            'code' => $this->code,
            'message' => $this->message,
        ];
    }
}

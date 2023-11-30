<?php

declare(strict_types=1);

namespace App\Services\RequestServices;

use App\Services\RequestServices\ResponseParser\FluentdResponseParser;
use Eva\Http\Builder\JsonRequestMessageBuilder;
use Eva\Http\ClientInterface;
use Eva\Http\ClientWrapper;
use Eva\Http\HttpMethodsEnum;
use Eva\Http\Parser\JsonResponseParser;

class FluentdRequestService extends ClientWrapper
{
    protected const DEFAULT_HEADERS = [
        'Content-Type' => 'application/json'
    ];

    public function __construct(
        private readonly string $tag,
        private readonly string $ip,
        private readonly int|string $port,
        ClientInterface $client,
    ) {
        parent::__construct($client, new JsonRequestMessageBuilder(), new FluentdResponseParser());
    }

    protected function getBaseUrl(): string
    {
        return "http://{$this->ip}:{$this->port}";
    }

    protected function completeBaseValues(): void
    {
        $this->httpRequestMessageBuilder
            ->addUrl($this->getBaseUrl() . '/')
            ->addHeaders(static::DEFAULT_HEADERS);
    }

    public function sendMessage(string $message, null|string $tag = null): void
    {
        $this->completeBaseValues();
        $request = $this->httpRequestMessageBuilder
            ->addMethod(HttpMethodsEnum::POST)
            ->addUrl($tag ?? $this->tag)
            ->addBody([
                'message' => $message,
            ])
            ->build();

        $this->request($request);
    }
}

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
        private readonly string $host,
        private readonly string $tag,
        ClientInterface $client,
    ) {
        parent::__construct($client, new JsonRequestMessageBuilder(), new FluentdResponseParser());
    }

    protected function getBaseUrl(): string
    {
        return "http://{$this->host}/api/services/fluentd";
    }

    protected function completeBaseValues(): void
    {
        $this->httpRequestMessageBuilder
            ->addUrl($this->getBaseUrl() . '/')
            ->addHeaders(static::DEFAULT_HEADERS);
    }

    public function sendMessage(string $message): void
    {
        $this->completeBaseValues();
        $request = $this->httpRequestMessageBuilder
            ->addMethod(HttpMethodsEnum::POST)
            ->addUrl('send-message')
            ->addBody([
                'tag' => $this->tag,
                'message' => $message
            ])
            ->build();

        $this->request($request);
    }
}

<?php

declare(strict_types=1);

namespace App\Services\RequestServices;

use App\Dto\Services\RequestServices\TelegramRequestService\SendMessageDto;
use App\Exceptions\Application\ApplicationErrorCodeEnum;
use App\Exceptions\Application\ApplicationException;
use App\Exceptions\RequestServices\TelegramRequestService\RequestException;
use App\Services\RequestServices\ResponseParser\TelegramResponseParser;
use Eva\Http\Builder\JsonRequestMessageBuilder;
use Eva\Http\ClientInterface;
use Eva\Http\ClientWrapper;
use Eva\Http\HttpMethodsEnum;
use Eva\Http\Message\ResponseInterface;

class TelegramRequestService extends ClientWrapper
{
    protected const DEFAULT_HEADERS = [
        'Content-Type' => 'application/json'
    ];

    public function __construct(
        private readonly string $token,
        private readonly string $host,
        ClientInterface $client,
    ) {
        parent::__construct($client, new JsonRequestMessageBuilder(), new TelegramResponseParser());
    }

    private function validateResponse(ResponseInterface $response): void
    {
        if (200 === $response->getStatusCode()) {
            return;
        }

        if (400 === $response->getStatusCode()) {
            $applicationCode = TelegramResponseParser::parseApplicationCode($response);

            if (40010 === $applicationCode) {
                throw new ApplicationException(ApplicationErrorCodeEnum::CHAT_DOES_NOT_EXIST);
            }
        }

        throw new RequestException($response->getBody(), $response->getStatusCode());
    }

    protected function getBaseUrl(): string
    {
        return "http://{$this->host}/api/services/telegram/";
    }

    protected function completeBaseValues(): void
    {
        $this->httpRequestMessageBuilder
            ->addUrl($this->getBaseUrl())
            ->addHeaders(['Authorization' => $this->token])
            ->addHeaders(static::DEFAULT_HEADERS);
    }

    public function getUpdates(null|int $ts = null): array
    {
        $this->completeBaseValues();
        $this->httpRequestMessageBuilder
            ->addMethod(HttpMethodsEnum::GET)
            ->addUrl('updates');

        if (null !== $ts) {
            $this->httpRequestMessageBuilder->addQuery(['ts' => $ts]);
        }

        $request = $this->httpRequestMessageBuilder->build();
        $response = $this->sendRequest($request);
        $this->validateResponse($response);

        return TelegramResponseParser::parseBody($response);
    }

    public function sendMessage(SendMessageDto $sendMessageDto): void
    {
        $this->completeBaseValues();
        $data = $sendMessageDto->toArray();
        $request = $this->httpRequestMessageBuilder
            ->addMethod(HttpMethodsEnum::POST)
            ->addUrl('send-message')
            ->addBody($data)
            ->build();

        $response = $this->sendRequest($request);
        $this->validateResponse($response);
    }
}

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
use JsonException;

class TelegramRequestService extends ClientWrapper
{
    protected const DEFAULT_HEADERS = [
        'Content-Type' => 'application/json'
    ];

    public function __construct(
        private readonly string $host,
        ClientInterface $client,
    ) {
        parent::__construct($client, new JsonRequestMessageBuilder(), new TelegramResponseParser());
    }

    /**
     * @throws ApplicationException
     * @throws RequestException
     * @throws JsonException
     */
    private function validateResponse(ResponseInterface $response): void
    {
        if (200 === $response->getStatusCode()) {
            return;
        }

        if (400 === $response->getStatusCode()) {
            $applicationCode = TelegramResponseParser::parseApplicationCode($response);

            if (40005 === $applicationCode) {
                throw new ApplicationException(ApplicationErrorCodeEnum::CHAT_DOES_NOT_EXIST);
            }
        }

        throw new RequestException($response->getBody(), $response->getStatusCode());
    }

    protected function getBaseUrl(): string
    {
        return "http://{$this->host}/api/";
    }

    protected function completeBaseValues(): void
    {
        $this->httpRequestMessageBuilder
            ->addUrl($this->getBaseUrl())
            ->addHeaders(static::DEFAULT_HEADERS);
    }

    /**
     * @throws ApplicationException
     * @throws RequestException
     * @throws JsonException
     */
    public function sendMessage(int $chatId, string $message): void
    {
        $this->completeBaseValues();
        $data = compact('chatId', 'message');
        $request = $this->httpRequestMessageBuilder
            ->addMethod(HttpMethodsEnum::POST)
            ->addUrl('send-message')
            ->addBody($data)
            ->build();

        $response = $this->sendRequest($request);
        $this->validateResponse($response);
    }
}

<?php

declare(strict_types=1);

namespace App\Services\RequestServices;

use App\Dto\Services\RequestServices\Telegram\SendMessageDto;
use App\Exceptions\Application\ApplicationErrorCodeEnum;
use App\Exceptions\Application\ApplicationException;
use App\Exceptions\Application\TelegramRequestServiceException;
use App\Services\RequestServices\ResponseParser\TelegramResponseParser;
use Eva\Http\Builder\JsonRequestMessageBuilder;
use Eva\Http\ClientInterface;
use Eva\Http\ClientWrapper;
use Eva\Http\HttpMethodsEnum;
use Eva\Http\Message\ResponseInterface;
use Eva\Http\Parser\JsonResponseParser;
use JsonException;

use Psr\Log\LoggerInterface;
use function Eva\Common\Functions\json_encode;

class TelegramRequestService extends ClientWrapper
{
    protected const BASE_URL = 'https://api.telegram.org/bot';
    protected const DEFAULT_HEADERS = [
        'Content-Type' => 'application/json'
    ];

    public function __construct(
        private readonly LoggerInterface $logger,
        ClientInterface $client,
    ) {
        parent::__construct($client, new JsonRequestMessageBuilder(), new JsonResponseParser());
    }

    protected function completeBaseValues(): void
    {
        $this->httpRequestMessageBuilder
            ->addUrl(static::BASE_URL)
            ->addHeaders(static::DEFAULT_HEADERS);
    }

    /**
     * @throws ApplicationException
     * @throws TelegramRequestServiceException
     * @throws JsonException
     */
    private function validateResponse(ResponseInterface $response): void
    {
        if (200 === $response->getStatusCode()) {
            return;
        }

        $this->logger->error(
            <<<EOL
            [status code] {$response->getStatusCode()}
            [body] {$response->getBody()}
            EOL,
        );
        $body = TelegramResponseParser::parseBody($response);

        if (isset($body['description']) && $body['description'] === 'Bad Request: chat not found') {
            throw new TelegramRequestServiceException(ApplicationErrorCodeEnum::TELEGRAM_SERVICE_CHAT_NOT_EXIST);
        }

        throw new ApplicationException(ApplicationErrorCodeEnum::UNEXPECTED_REQUEST_RESPONSE_STATUS);
    }

    public function getUpdates(string $token, null|int $ts = null): array
    {
        $this->completeBaseValues();
        $this->httpRequestMessageBuilder
            ->addMethod(HttpMethodsEnum::GET)
            ->addUrl($token . '/')
            ->addUrl('getUpdates')
            ->addQuery(['allowed_updates' => json_encode([
                'message',
                'callback_query',
            ])]);

        if (null !== $ts) {
            $this->httpRequestMessageBuilder->addQuery(['offset' => $ts]);
        }

        $request = $this->httpRequestMessageBuilder->build();
        $response = $this->sendRequest($request);
        $this->validateResponse($response);

        return TelegramResponseParser::parseBody($response);
    }

    public function sendMessage(string $token, SendMessageDto $sendMessageDto): void
    {
        $this->completeBaseValues();
        $data = $sendMessageDto->toArray();
        $request = $this->httpRequestMessageBuilder
            ->addMethod(HttpMethodsEnum::POST)
            ->addUrl($token . '/')
            ->addUrl('sendMessage')
            ->addBody($data)
            ->build();
        $response = $this->sendRequest($request);
        $this->validateResponse($response);
    }
}

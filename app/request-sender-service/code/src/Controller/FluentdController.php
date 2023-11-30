<?php

declare(strict_types=1);

namespace App\Controller;

use App\Rest\RestApiResponse;
use App\Rest\RestApiResponseMessageBuilder;
use App\UseCase\Fluentd\SendMessageHandler;
use App\Validation\Schema\Rest\Request\Fluentd\SendMessageSchema;
use App\Validation\Validator;
use Eva\Http\Message\RequestInterface;
use Eva\Http\Message\ResponseInterface;
use Eva\Http\Parser\JsonRequestParser;

readonly class FluentdController
{
    use ApiControllerTrait;

    public function __construct(
        private Validator $validator,
        private SendMessageHandler $sendMessageHandler,
    ) {}

    public function sendMessage(RequestInterface $request): ResponseInterface
    {
        $this->validateHttpMessageFormat($this->validator, $request);
        $body = JsonRequestParser::parseBody($request);
        $this->validator->validate($body, SendMessageSchema::SCHEMA);
        $this->sendMessageHandler->handle(...$body);

        return (new RestApiResponseMessageBuilder())
            ->addBody((new RestApiResponse())->toArray())
            ->build();
    }
}

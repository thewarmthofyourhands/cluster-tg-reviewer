<?php

declare(strict_types=1);

namespace App\Controller;

use App\Exceptions\Rest\IncorrectHttpMessageContentTypeException;
use App\Exceptions\Validation\ValidatorException;
use App\Rest\RestApiResponse;
use App\Rest\RestApiResponseMessageBuilder;
use App\UseCase\SendMessageToChatHandler;
use App\Validation\Schema\Rest\HttpContentTypeHeaderSchema;
use App\Validation\Schema\Rest\Request\SendMessageSchema;
use App\Validation\Validator;
use Eva\Http\Message\RequestInterface;
use Eva\Http\Message\ResponseInterface;
use Eva\Http\Parser\JsonRequestParser;
use JsonException;

readonly class SendMessageController
{
    public function __construct(
        private Validator $validator,
        private SendMessageToChatHandler $sendMessageToChatHandler,
    ) {}

    /**
     * @throws IncorrectHttpMessageContentTypeException
     * @throws JsonException
     */
    protected function validateHttpContentTypeHeader(Validator $validator, RequestInterface $request): void
    {
        try {
            $validator->validate($request->getHeaders(), HttpContentTypeHeaderSchema::SCHEMA);
        } catch (ValidatorException $validatorException) {
            throw new IncorrectHttpMessageContentTypeException();
        }
    }

    public function sendMessage(RequestInterface $request): ResponseInterface
    {
        $this->validateHttpContentTypeHeader($this->validator, $request);
        $parsedBody = JsonRequestParser::parseBody($request);
        $this->validator->validate($parsedBody, SendMessageSchema::SCHEMA);
        ['chatId' => $chatId, 'message' => $message] = $parsedBody;
        $this->sendMessageToChatHandler->handle((int) $chatId, $message);

        return (new RestApiResponseMessageBuilder())
            ->addBody((new RestApiResponse())->toArray())
            ->build();
    }
}

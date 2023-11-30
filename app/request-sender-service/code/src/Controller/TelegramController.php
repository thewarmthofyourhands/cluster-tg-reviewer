<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\UseCase\GitHub\GetPullRequestWithStatusListDto;
use App\Dto\UseCase\Telegram\SendMessageDto;
use App\Exceptions\Application\AuthenticateException;
use App\Exceptions\Rest\IncorrectHttpMessageContentTypeException;
use App\Exceptions\Validation\ValidatorException;
use App\Mappers\Dto\UseCase\GitHub\PullRequestDtoMapper;
use App\Rest\RestApiResponse;
use App\Rest\RestApiResponseMessageBuilder;
use App\UseCase\GitHub\CheckTokenHandler;
use App\UseCase\GitHub\GetPullRequestListHandler;
use App\UseCase\Telegram\GetUpdateListHandler;
use App\UseCase\Telegram\SendMessageHandler;
use App\Validation\Schema\Rest\Request\GitHub\GetPullRequestListSchema;
use App\Validation\Schema\Rest\Request\Telegram\GetUpdatesSchema;
use App\Validation\Schema\Rest\Request\Telegram\SendMessageSchema;
use App\Validation\Validator;
use Eva\Http\Message\RequestInterface;
use Eva\Http\Message\ResponseInterface;
use Eva\Http\Parser\JsonRequestParser;
use JsonException;

readonly class TelegramController
{
    use ApiControllerTrait;

    public function __construct(
        private Validator $validator,
        private GetUpdateListHandler $getUpdateListHandler,
        private SendMessageHandler $sendMessageHandler,
    ) {}

    /**
     * @throws AuthenticateException
     * @throws ValidatorException
     * @throws IncorrectHttpMessageContentTypeException
     * @throws JsonException
     */
    public function getUpdates(RequestInterface $request): ResponseInterface
    {
        $this->validateAuthHttpMessageFormat($this->validator, $request);
        $token = $request->getHeaders()['Authorization'];
        $query = JsonRequestParser::parseParams($request);
        $data = ['token' => $token];

        if ([] !== $query) {
            $data['ts'] = (int) $query['ts'];
        }

        $this->validator->validate($data, GetUpdatesSchema::SCHEMA);
        $updates = $this->getUpdateListHandler->handle(...$data);

        return (new RestApiResponseMessageBuilder())
            ->addBody((new RestApiResponse($updates))->toArray())
            ->build();
    }

    /**
     * @throws AuthenticateException
     * @throws ValidatorException
     * @throws IncorrectHttpMessageContentTypeException
     * @throws JsonException
     */
    public function sendMessage(RequestInterface $request): ResponseInterface
    {
        $this->validateAuthHttpMessageFormat($this->validator, $request);
        $token = $request->getHeaders()['Authorization'];
        $body = JsonRequestParser::parseBody($request);
        $data = ['token' => $token, ...$body];
        $this->validator->validate($data, SendMessageSchema::SCHEMA);
        $this->sendMessageHandler->handle(new SendMessageDto(
            ...$data,
        ));

        return (new RestApiResponseMessageBuilder())
            ->addBody((new RestApiResponse())->toArray())
            ->build();
    }

}

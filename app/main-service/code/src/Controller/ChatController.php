<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\UseCase\DeleteChatDto;
use App\Dto\UseCase\GetChatByProjectIdDto;
use App\Exceptions\Application\ApplicationErrorCodeEnum;
use App\Exceptions\Application\AuthenticateException;
use App\Exceptions\Rest\IncorrectHttpMessageContentTypeException;
use App\Exceptions\Validation\ValidatorException;
use App\Rest\RestApiResponse;
use App\Rest\RestApiResponseMessageBuilder;
use App\UseCase\AddChatHandler;
use App\UseCase\DeleteChatHandler;
use App\UseCase\GetChatByProjectIdHandler;
use App\Validation\Schema\Rest\Request\Chat\AddChatSchema;
use App\Validation\Schema\Rest\Request\Chat\ChatSchema;
use App\Validation\Schema\Rest\Request\Chat\DeleteChatSchema;
use App\Validation\Schema\Rest\Request\Chat\GetChatByProjectIdSchema;
use App\Validation\Validator;
use Eva\Http\Message\RequestInterface;
use Eva\Http\Message\ResponseInterface;
use Eva\Http\Parser\JsonRequestParser;
use JsonException;
use App\Mappers\Dto\UseCase\ChatDtoMapper;

readonly class ChatController
{
    use ApiControllerTrait;

    public function __construct(
        private Validator $validator,
        private AddChatHandler $addChatHandler,
        private GetChatByProjectIdHandler $getChatByProjectIdHandler,
        private DeleteChatHandler $deleteChatHandler,
    ) {}

    /**
     * @throws AuthenticateException
     * @throws JsonException
     * @throws ValidatorException
     * @throws IncorrectHttpMessageContentTypeException
     */
    public function store(RequestInterface $request, string $projectId): ResponseInterface
    {
        $this->validateAuthHttpMessageFormat($this->validator, $request);
        $adminId = (int) $request->getHeaders()['Authorization'];
        $data = JsonRequestParser::parseBody($request);
        $data['adminId'] = $adminId;
        $data['projectId'] = (int) $projectId;
        $this->validator->validate($data, AddChatSchema::SCHEMA);
        $this->addChatHandler->handle(ChatDtoMapper::convertDataToAddChatDto($data));

        return (new RestApiResponseMessageBuilder())
            ->addBody((new RestApiResponse())->toArray())
            ->setStatusCode(201)
            ->build();
    }

    /**
     * @throws AuthenticateException
     * @throws ValidatorException
     * @throws JsonException
     */
    public function show(RequestInterface $request, string $projectId): ResponseInterface
    {
        $this->validateAuthHttpMessageFormat($this->validator, $request);
        $adminId = (int) $request->getHeaders()['Authorization'];
        $data = compact('adminId', 'projectId');
        $data['projectId'] = (int) $projectId;
        $this->validator->validate($data, GetChatByProjectIdSchema::SCHEMA);
        $chatDto = $this->getChatByProjectIdHandler->handle(new GetChatByProjectIdDto(...$data));
        $chatData = null === $chatDto ? null : ChatDtoMapper::convertDtoToArray($chatDto);
        $this->validator->validate($chatData, ChatSchema::SCHEMA);

        return (new RestApiResponseMessageBuilder())
            ->addBody((new RestApiResponse($chatData))->toArray())
            ->build();
    }

    /**
     * @throws AuthenticateException
     * @throws JsonException
     * @throws ValidatorException
     */
    public function delete(RequestInterface $request, string $projectId): ResponseInterface
    {
        $this->validateAuthHttpMessageFormat($this->validator, $request);
        $adminId = (int) $request->getHeaders()['Authorization'];
        $data = compact('adminId', 'projectId');
        $data['projectId'] = (int) $projectId;
        $this->validator->validate($data, DeleteChatSchema::SCHEMA);
        $this->deleteChatHandler->handle(new DeleteChatDto(...$data));

        return (new RestApiResponseMessageBuilder())
            ->addBody((new RestApiResponse())->toArray())
            ->build();
    }
}

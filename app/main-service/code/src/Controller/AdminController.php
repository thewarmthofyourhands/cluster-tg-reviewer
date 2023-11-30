<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\UseCase\AddAdminDto;
use App\Dto\UseCase\AdminDto;
use App\Dto\UseCase\GetAdminByMessengerIdDto;
use App\Enums\MessengerTypeEnum;
use App\Exceptions\Rest\IncorrectHttpMessageContentTypeException;
use App\Exceptions\Validation\ValidatorException;
use App\Rest\RestApiResponse;
use App\Rest\RestApiResponseMessageBuilder;
use App\UseCase\AddAdminHandler;
use App\UseCase\GetAdminByMessengerIdHandler;
use App\Validation\Schema\Rest\Request\Admin\AddAdminSchema;
use App\Validation\Schema\Rest\Request\Admin\AdminSchema;
use App\Validation\Schema\Rest\Request\Admin\LoginSchema;
use App\Validation\Validator;
use Eva\Http\Message\RequestInterface;
use Eva\Http\Message\ResponseInterface;
use Eva\Http\Parser\JsonRequestParser;
use JsonException;
use App\Mappers\Dto\UseCase\AdminDtoMapper;

readonly class AdminController
{
    use ApiControllerTrait;

    public function __construct(
        private Validator $validator,
        private GetAdminByMessengerIdHandler $getAdminByMessengerIdHandler,
        private AddAdminHandler $addAdminHandler,
    ) {}

    /**
     * @throws ValidatorException
     * @throws JsonException
     * @throws IncorrectHttpMessageContentTypeException
     */
    public function login(RequestInterface $request): ResponseInterface
    {
        $this->validateHttpMessageFormat($this->validator, $request);
        $body = JsonRequestParser::parseBody($request);
        $this->validator->validate($body, LoginSchema::SCHEMA);
        $adminDto = $this->getAdminByMessengerIdHandler->handle(
            AdminDtoMapper::convertDataToGetAdminByMessengerIdDto($body)
        );
        $adminData = AdminDtoMapper::convertAdminDtoToArray($adminDto);
        $this->validator->validate($adminData, AdminSchema::SCHEMA);

        return (new RestApiResponseMessageBuilder())
            ->addBody((new RestApiResponse($adminData))->toArray())
            ->build();
    }
//
//    /**
//     * @throws ValidatorException
//     * @throws JsonException
//     */
//    public function getAdminById(RequestInterface $request, int $id): ResponseInterface
//    {
//
//        $messengerId = $request->getHeaders()['Authorization'] ?? null;
//
//        if (null === $messengerId) {
//            throw new AuthenticateException('Authorization header is required');
//        }
//
//        $this->validator->validate(compact('id'), GetAdminByIdSchema::SCHEMA);
//        $adminDto = $this->getAdminByIdHandler->handle(new GetAdminByIdDto(
//            $id,
//        ));
//        $adminData = $adminDto->toArray();
//        $adminData['messengerType'] = $adminDto->getMessengerType()->value;
//        $this->validator->validate($adminData, AdminSchema::SCHEMA);
//
//        return (new RestApiResponseMessageBuilder())
//            ->addBody((new RestApiResponse($adminData))->toArray())
//            ->build();
//    }

    /**
     * @throws ValidatorException
     * @throws JsonException
     * @throws IncorrectHttpMessageContentTypeException
     */
    public function store(RequestInterface $request): ResponseInterface
    {
        $this->validateHttpMessageFormat($this->validator, $request);
        $body = JsonRequestParser::parseBody($request);
        $this->validator->validate($body, AddAdminSchema::SCHEMA);
        $this->addAdminHandler->handle(AdminDtoMapper::convertDataToAddAdminDto($body));

        return (new RestApiResponseMessageBuilder())
            ->addBody((new RestApiResponse(null, 201))->toArray())
            ->setStatusCode(201)
            ->build();
    }
}

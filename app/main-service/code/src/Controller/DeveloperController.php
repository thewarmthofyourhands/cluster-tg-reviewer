<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\UseCase\DeleteDeveloperDto;
use App\Dto\UseCase\GetDeveloperByIdDto;
use App\Dto\UseCase\GetDeveloperListDto;
use App\Exceptions\Application\ApplicationErrorCodeEnum;
use App\Exceptions\Application\ApplicationException;
use App\Exceptions\Application\AuthenticateException;
use App\Exceptions\Validation\ValidatorException;
use App\Rest\RestApiResponse;
use App\Rest\RestApiResponseMessageBuilder;
use App\UseCase\AddDeveloperHandler;
use App\UseCase\ChangeDeveloperStatusHandler;
use App\UseCase\DeleteDeveloperHandler;
use App\UseCase\GetDeveloperByIdHandler;
use App\UseCase\GetDeveloperListHandler;
use App\Validation\Schema\Rest\Request\Developer\AddDeveloperSchema;
use App\Validation\Schema\Rest\Request\Developer\ChangeDeveloperStatusSchema;
use App\Validation\Schema\Rest\Request\Developer\DeleteDeveloperSchema;
use App\Validation\Schema\Rest\Request\Developer\DeveloperListSchema;
use App\Validation\Schema\Rest\Request\Developer\DeveloperSchema;
use App\Validation\Schema\Rest\Request\Developer\GetDeveloperByIdSchema;
use App\Validation\Schema\Rest\Request\Developer\GetDeveloperListSchema;
use App\Validation\Validator;
use Eva\Http\Message\RequestInterface;
use Eva\Http\Message\ResponseInterface;
use Eva\Http\Parser\JsonRequestParser;
use JsonException;
use App\Mappers\Dto\UseCase\DeveloperDtoMapper;

readonly class DeveloperController
{
    use ApiControllerTrait;

    public function __construct(
        private Validator $validator,
        private AddDeveloperHandler $addDeveloperHandler,
        private GetDeveloperListHandler $getDeveloperListHandler,
        private GetDeveloperByIdHandler $getDeveloperByIdHandler,
        private ChangeDeveloperStatusHandler $changeDeveloperStatusHandler,
        private DeleteDeveloperHandler $deleteDeveloperHandler,
    ) {}

    public function store(RequestInterface $request, string $projectId): ResponseInterface
    {
        $this->validateAuthHttpMessageFormat($this->validator, $request);
        $adminId = (int) $request->getHeaders()['Authorization'];
        $data = [
            ...JsonRequestParser::parseBody($request),
            ...compact('adminId', 'projectId'),
        ];
        $data['projectId'] = (int) $projectId;
        $this->validator->validate($data, AddDeveloperSchema::SCHEMA);
        $this->addDeveloperHandler->handle(DeveloperDtoMapper::convertDataToAddDeveloperDto($data));

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
    public function index(RequestInterface $request, string $projectId): ResponseInterface
    {
        $this->validateAuthHttpMessageFormat($this->validator, $request);
        $adminId = (int) $request->getHeaders()['Authorization'];
        $data = compact('adminId', 'projectId');
        $data['projectId'] = (int) $projectId;
        $this->validator->validate($data, GetDeveloperListSchema::SCHEMA);
        $developerDtoList = $this->getDeveloperListHandler->handle(new GetDeveloperListDto(...$data));
        $developerList = DeveloperDtoMapper::convertDtoListToArray($developerDtoList);
        $this->validator->validate($developerList, DeveloperListSchema::SCHEMA);

        return (new RestApiResponseMessageBuilder())
            ->addBody((new RestApiResponse($developerList))->toArray())
            ->setStatusCode(200)
            ->build();
    }

    /**
     * @throws AuthenticateException
     * @throws ValidatorException
     * @throws JsonException
     * @throws ApplicationException
     */
    public function show(RequestInterface $request, string $projectId, string $id): ResponseInterface
    {
        $this->validateAuthHttpMessageFormat($this->validator, $request);
        $adminId = (int) $request->getHeaders()['Authorization'];
        $data = compact('adminId');
        $data['projectId'] = (int) $projectId;
        $data['id'] = (int) $id;
        $this->validator->validate($data, GetDeveloperByIdSchema::SCHEMA);
        $developerDto = $this->getDeveloperByIdHandler->handle(new GetDeveloperByIdDto(...$data));
        $developer = DeveloperDtoMapper::convertDtoToArray($developerDto);
        $this->validator->validate($developer, DeveloperSchema::SCHEMA);

        return (new RestApiResponseMessageBuilder())
            ->addBody((new RestApiResponse($developer))->toArray())
            ->setStatusCode(200)
            ->build();
    }

    /**
     * @throws AuthenticateException
     * @throws ValidatorException
     * @throws JsonException
     * @throws ApplicationException
     */
    public function editStatus(RequestInterface $request, string $projectId, string $id): ResponseInterface
    {
        $this->validateAuthHttpMessageFormat($this->validator, $request);
        $adminId = (int) $request->getHeaders()['Authorization'];
        $data = [
            ...JsonRequestParser::parseBody($request),
            ...compact('adminId'),
        ];
        $data['projectId'] = (int) $projectId;
        $data['id'] = (int) $id;
        $this->validator->validate($data, ChangeDeveloperStatusSchema::SCHEMA);
        $this->changeDeveloperStatusHandler->handle(DeveloperDtoMapper::convertDataToChangeDeveloperStatusDto($data));

        return (new RestApiResponseMessageBuilder())
            ->addBody((new RestApiResponse())->toArray())
            ->setStatusCode(200)
            ->build();
    }

    /**
     * @throws AuthenticateException
     * @throws ValidatorException
     * @throws JsonException
     * @throws ApplicationException
     */
    public function delete(RequestInterface $request, string $projectId, string $id): ResponseInterface
    {
        $this->validateAuthHttpMessageFormat($this->validator, $request);
        $adminId = (int) $request->getHeaders()['Authorization'];
        $data = compact('adminId');
        $data['projectId'] = (int) $projectId;
        $data['id'] = (int) $id;
        $this->validator->validate($data, DeleteDeveloperSchema::SCHEMA);
        $this->deleteDeveloperHandler->handle(new DeleteDeveloperDto(...$data));

        return (new RestApiResponseMessageBuilder())
            ->addBody((new RestApiResponse())->toArray())
            ->setStatusCode(200)
            ->build();
    }
}

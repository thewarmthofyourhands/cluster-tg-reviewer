<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\UseCase\AddCredentialDto;
use App\Dto\UseCase\DeleteCredentialDto;
use App\Dto\UseCase\GetCredentialByProjectIdDto;
use App\Exceptions\Application\ApplicationErrorCodeEnum;
use App\Exceptions\Application\AuthenticateException;
use App\Exceptions\Validation\ValidatorException;
use App\Rest\RestApiResponse;
use App\Rest\RestApiResponseMessageBuilder;
use App\UseCase\AddCredentialHandler;
use App\UseCase\DeleteCredentialHandler;
use App\UseCase\GetCredentialByProjectIdHandler;
use App\Validation\Schema\Rest\Request\Credentials\AddCredentialSchema;
use App\Validation\Schema\Rest\Request\Credentials\CredentialSchema;
use App\Validation\Schema\Rest\Request\Credentials\DeleteCredentialSchema;
use App\Validation\Schema\Rest\Request\Credentials\GetCredentialByProjectIdSchema;
use App\Validation\Validator;
use DateTime;
use Eva\Http\Message\RequestInterface;
use Eva\Http\Message\ResponseInterface;
use Eva\Http\Parser\JsonRequestParser;
use JsonException;

readonly class CredentialsController
{
    use ApiControllerTrait;

    public function __construct(
        private Validator $validator,
        private AddCredentialHandler $addCredentialHandler,
        private GetCredentialByProjectIdHandler $getCredentialByProjectIdHandler,
        private DeleteCredentialHandler $deleteCredentialHandler,
    ) {}

    /**
     * @throws AuthenticateException
     * @throws ValidatorException
     * @throws JsonException
     */
    public function store(RequestInterface $request, string $projectId): ResponseInterface
    {
        $this->validateAuthHttpMessageFormat($this->validator, $request);
        $adminId = (int) $request->getHeaders()['Authorization'];
        $data = JsonRequestParser::parseBody($request);
        $data['adminId'] = $adminId;
        $data['projectId'] = (int) $projectId;
        $this->validator->validate($data, AddCredentialSchema::SCHEMA);
        $this->addCredentialHandler->handle(new AddCredentialDto(...$data));

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
        $this->validator->validate($data, GetCredentialByProjectIdSchema::SCHEMA);
        $credentialDto = $this->getCredentialByProjectIdHandler->handle(new GetCredentialByProjectIdDto(...$data));
        $credentialData = $credentialDto?->toArray();
        $this->validator->validate($credentialData, CredentialSchema::SCHEMA);

        return (new RestApiResponseMessageBuilder())
            ->addBody((new RestApiResponse($credentialData))->toArray())
            ->setStatusCode(200)
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
        $this->validator->validate($data, DeleteCredentialSchema::SCHEMA);
        $this->deleteCredentialHandler->handle(new DeleteCredentialDto(...$data));

        return (new RestApiResponseMessageBuilder())
            ->addBody((new RestApiResponse())->toArray())
            ->setStatusCode(200)
            ->build();
    }
}

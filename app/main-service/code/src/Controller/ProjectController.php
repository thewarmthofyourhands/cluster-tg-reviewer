<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\UseCase\DeleteProjectDto;
use App\Dto\UseCase\GetProjectDto;
use App\Dto\UseCase\GetProjectListDto;
use App\Exceptions\Application\ApplicationException;
use App\Exceptions\Application\AuthenticateException;
use App\Exceptions\Validation\ValidatorException;
use App\Rest\RestApiResponse;
use App\Rest\RestApiResponseMessageBuilder;
use App\UseCase\AddProjectHandler;
use App\UseCase\ChangeProjectReviewTypeHandler;
use App\UseCase\DeleteProjectHandler;
use App\UseCase\GetProjectHandler;
use App\UseCase\GetProjectListHandler;
use App\Validation\Schema\Rest\Request\Project\AddProjectSchema;
use App\Validation\Schema\Rest\Request\Project\ChangeProjectReviewTypeSchema;
use App\Validation\Schema\Rest\Request\Project\DeleteProjectSchema;
use App\Validation\Schema\Rest\Request\Project\GetProjectListSchema;
use App\Validation\Schema\Rest\Request\Project\GetProjectSchema;
use App\Validation\Schema\Rest\Request\Project\ProjectListSchema;
use App\Validation\Schema\Rest\Request\Project\ProjectSchema;
use App\Validation\Validator;
use Eva\Http\Message\RequestInterface;
use Eva\Http\Message\ResponseInterface;
use Eva\Http\Parser\JsonRequestParser;
use JsonException;
use App\Mappers\Dto\UseCase\ProjectDtoMapper;

readonly class ProjectController
{
    use ApiControllerTrait;

    public function __construct(
        private Validator $validator,
        private AddProjectHandler $addProjectHandler,
        private GetProjectListHandler $getProjectListHandler,
        private GetProjectHandler $getProjectHandler,
        private ChangeProjectReviewTypeHandler $changeProjectReviewTypeHandler,
        private DeleteProjectHandler $deleteProjectHandler,
    ) {}

    /**
     * @throws AuthenticateException
     * @throws JsonException
     * @throws ValidatorException
     */
    public function store(RequestInterface $request): ResponseInterface
    {
        $this->validateAuthHttpMessageFormat($this->validator, $request);
        $adminId = (int) $request->getHeaders()['Authorization'];
        $data = JsonRequestParser::parseBody($request);
        $data['adminId'] = $adminId;
        $this->validator->validate($data, AddProjectSchema::SCHEMA);
        $this->addProjectHandler->handle(ProjectDtoMapper::convertDataToAddProjectDto($data));

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
    public function index(RequestInterface $request): ResponseInterface
    {
        $this->validateAuthHttpMessageFormat($this->validator, $request);
        $adminId = (int) $request->getHeaders()['Authorization'];
        $this->validator->validate(compact('adminId'), GetProjectListSchema::SCHEMA);
        $projectDtoList = $this->getProjectListHandler->handle(new GetProjectListDto(
            $adminId,
        ));
        $projectList = ProjectDtoMapper::convertDtoListToArray($projectDtoList);
        $this->validator->validate($projectList, ProjectListSchema::SCHEMA);

        return (new RestApiResponseMessageBuilder())
            ->addBody((new RestApiResponse($projectList))->toArray())
            ->build();
    }

    /**
     * @throws AuthenticateException
     * @throws ValidatorException
     * @throws JsonException
     * @throws ApplicationException
     */
    public function show(RequestInterface $request, string $id): ResponseInterface
    {
        $this->validateAuthHttpMessageFormat($this->validator, $request);
        $adminId = (int) $request->getHeaders()['Authorization'];
        $data = compact('adminId', 'id');
        $data['id'] = (int) $id;
        $this->validator->validate($data, GetProjectSchema::SCHEMA);
        $projectDto = $this->getProjectHandler->handle(new GetProjectDto(
            ...$data
        ));
        $projectData = ProjectDtoMapper::convertDtoToArray($projectDto);
        $this->validator->validate($projectData, ProjectSchema::SCHEMA);

        return (new RestApiResponseMessageBuilder())
            ->addBody((new RestApiResponse($projectData))->toArray())
            ->build();
    }

    /**
     * @throws AuthenticateException
     * @throws JsonException
     * @throws ValidatorException
     */
    public function editReviewType(RequestInterface $request, string $id): ResponseInterface
    {
        $this->validateAuthHttpMessageFormat($this->validator, $request);
        $adminId = (int) $request->getHeaders()['Authorization'];
        $body = JsonRequestParser::parseBody($request);
        $data = compact('adminId', 'id');
        $data['id'] = (int) $id;
        $data = [...$data, ...$body];
        $this->validator->validate($data, ChangeProjectReviewTypeSchema::SCHEMA);
        $this->changeProjectReviewTypeHandler->handle(ProjectDtoMapper::convertDataToChangeProjectReviewTypeDto($data));

        return (new RestApiResponseMessageBuilder())
            ->addBody((new RestApiResponse())->toArray())
            ->build();
    }

    /**
     * @throws AuthenticateException
     * @throws JsonException
     * @throws ValidatorException
     */
    public function delete(RequestInterface $request, string $id): ResponseInterface
    {
        $this->validateAuthHttpMessageFormat($this->validator, $request);
        $adminId = (int) $request->getHeaders()['Authorization'];
        $data = compact('adminId', 'id');
        $data['id'] = (int) $data['id'];
        $this->validator->validate($data, DeleteProjectSchema::SCHEMA);
        $this->deleteProjectHandler->handle(new DeleteProjectDto(...$data));

        return (new RestApiResponseMessageBuilder())
            ->addBody((new RestApiResponse())->toArray())
            ->build();
    }
}

<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\UseCase\BindDeveloperToPullRequestDto;
use App\Dto\UseCase\GetAllPullRequestListDto;
use App\Exceptions\Application\ApplicationErrorCodeEnum;
use App\Exceptions\Application\AuthenticateException;
use App\Exceptions\Validation\ValidatorException;
use App\Mappers\Dto\UseCase\PullRequestDtoMapper;
use App\Rest\RestApiResponse;
use App\Rest\RestApiResponseMessageBuilder;
use App\UseCase\BindDeveloperToPullRequestHandler;
use App\UseCase\GetAllPullRequestListHandler;
use App\Validation\Schema\Rest\Request\PullRequest\BindDeveloperToPullRequestSchema;
use App\Validation\Schema\Rest\Request\PullRequest\GetAllPullRequestListSchema;
use App\Validation\Schema\Rest\Request\PullRequest\PullRequestListSchema;
use App\Validation\Validator;
use Eva\Http\Message\RequestInterface;
use Eva\Http\Message\ResponseInterface;
use Eva\Http\Parser\JsonRequestParser;
use JsonException;

readonly class PullRequestController
{
    public function __construct(
        private Validator $validator,
        private GetAllPullRequestListHandler $getAllPullRequestListHandler,
        private BindDeveloperToPullRequestHandler $bindDeveloperToPullRequestHandler,
    ) {}

    /**
     * @throws AuthenticateException
     * @throws ValidatorException
     * @throws JsonException
     */
    public function index(RequestInterface $request, string $projectId): ResponseInterface
    {
        if (false === isset($request->getHeaders()['Authorization'])) {
            throw new AuthenticateException(ApplicationErrorCodeEnum::REQUIRED_AUTHORIZATION_HEADER);
        }

        $adminId = (int) trim($request->getHeaders()['Authorization']);
        $data = compact('adminId');
        $data['projectId'] = (int) $projectId;
        $this->validator->validate($data, GetAllPullRequestListSchema::SCHEMA);
        $pullRequestDtoList = $this->getAllPullRequestListHandler->handle(new GetAllPullRequestListDto(...$data));
        $pullRequestList = PullRequestDtoMapper::convertDtoListToArray($pullRequestDtoList);
        $this->validator->validate($pullRequestList, PullRequestListSchema::SCHEMA);

        return (new RestApiResponseMessageBuilder())
            ->addBody((new RestApiResponse($pullRequestList))->toArray())
            ->setStatusCode(200)
            ->build();
    }

    /**
     * @throws AuthenticateException
     * @throws ValidatorException
     * @throws JsonException
     */
    public function bindDeveloper(RequestInterface $request, string $projectId, string $id): ResponseInterface
    {
        if (false === isset($request->getHeaders()['Authorization'])) {
            throw new AuthenticateException(ApplicationErrorCodeEnum::REQUIRED_AUTHORIZATION_HEADER);
        }

        $adminId = (int) trim($request->getHeaders()['Authorization']);
        $data = compact('adminId');
        $data['projectId'] = (int) $projectId;
        $data['id'] = (int) $id;
        $body = JsonRequestParser::parseBody($request);
        $data = [...$body, ...$data];
        $this->validator->validate($data, BindDeveloperToPullRequestSchema::SCHEMA);
        $this->bindDeveloperToPullRequestHandler->handle(new BindDeveloperToPullRequestDto(...$data));

        return (new RestApiResponseMessageBuilder())
            ->addBody((new RestApiResponse())->toArray())
            ->setStatusCode(200)
            ->build();
    }
}

<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\UseCase\GitHub\CheckTokenDto;
use App\Dto\UseCase\GitHub\GetPullRequestWithStatusListDto;
use App\Exceptions\Application\AuthenticateException;
use App\Exceptions\Rest\IncorrectHttpMessageContentTypeException;
use App\Exceptions\Validation\ValidatorException;
use App\Mappers\Dto\UseCase\GitHub\PullRequestDtoMapper;
use App\Rest\RestApiResponse;
use App\Rest\RestApiResponseMessageBuilder;
use App\UseCase\GitHub\CheckTokenHandler;
use App\UseCase\GitHub\GetPullRequestListHandler;
use App\Validation\Schema\Rest\Request\GitHub\CheckTokenSchema;
use App\Validation\Schema\Rest\Request\GitHub\GetPullRequestListSchema;
use App\Validation\Validator;
use Eva\Http\Message\RequestInterface;
use Eva\Http\Message\ResponseInterface;
use Eva\Http\Parser\JsonRequestParser;
use JsonException;

readonly class GitHubController
{
    use ApiControllerTrait;

    public function __construct(
        private Validator $validator,
        private GetPullRequestListHandler $getPullRequestListHandler,
        private CheckTokenHandler $checkTokenHandler,
    ) {}

    /**
     * @throws AuthenticateException
     * @throws ValidatorException
     * @throws IncorrectHttpMessageContentTypeException
     * @throws JsonException
     */
    public function getPullRequests(RequestInterface $request): ResponseInterface
    {
        $this->validateAuthHttpMessageFormat($this->validator, $request);
        $token = $request->getHeaders()['Authorization'];
        $query = JsonRequestParser::parseParams($request);
        $data = ['token' => $token, ...$query];
        $this->validator->validate($data, GetPullRequestListSchema::SCHEMA);
        $pullRequestDtoList = $this->getPullRequestListHandler->handle(new GetPullRequestWithStatusListDto(
            ...$data,
        ));
        $pullRequestList = PullRequestDtoMapper::convertDtoListToArray($pullRequestDtoList);

        return (new RestApiResponseMessageBuilder())
            ->addBody((new RestApiResponse($pullRequestList))->toArray())
            ->build();
    }

    /**
     * @throws AuthenticateException
     * @throws IncorrectHttpMessageContentTypeException
     * @throws ValidatorException
     * @throws JsonException
     */
    public function checkToken(RequestInterface $request): ResponseInterface
    {
        $this->validateAuthHttpMessageFormat($this->validator, $request);
        $token = $request->getHeaders()['Authorization'];
        $parsedBody = JsonRequestParser::parseBody($request);
        $data = ['token' => $token, ...$parsedBody];
        $this->validator->validate($data, CheckTokenSchema::SCHEMA);
        $isCorrect = $this->checkTokenHandler->handle(new CheckTokenDto(...$data));

        return (new RestApiResponseMessageBuilder())
            ->addBody((new RestApiResponse(compact('isCorrect')))->toArray())
            ->build();
    }
}

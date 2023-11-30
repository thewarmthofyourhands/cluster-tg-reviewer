<?php

declare(strict_types=1);

namespace App\Services\RequestServices;

use App\Enums\Services\ProjectService\ReviewTypeEnum;
use App\Exceptions\RequestServices\MainServiceRequestService\RequestException;
use App\Services\RequestServices\ResponseParser\MainServiceParser;
use Eva\Http\Builder\JsonRequestMessageBuilder;
use Eva\Http\ClientInterface;
use Eva\Http\ClientWrapper;
use Eva\Http\HttpMethodsEnum;
use Eva\Http\Message\ResponseInterface;

class MainServiceRequestService extends ClientWrapper
{
    protected const DEFAULT_HEADERS = [
        'Content-Type' => 'application/json'
    ];

    public function __construct(
        private readonly string $host,
        ClientInterface $client,
    ) {
        parent::__construct($client, new JsonRequestMessageBuilder(), new MainServiceParser());
    }

    private function validateResponse(ResponseInterface $response): void
    {
        if (200 === $response->getStatusCode() || 201 === $response->getStatusCode()) {
            return;
        }

        throw new RequestException($response->getBody(), $response->getStatusCode());
    }

    protected function getBaseUrl(): string
    {
        return "http://{$this->host}/api/";
    }


    protected function completeBaseValues(): void
    {
        $this->httpRequestMessageBuilder
            ->addUrl($this->getBaseUrl())
            ->addHeaders(static::DEFAULT_HEADERS);
    }

    protected function setToken(int $adminId): void
    {
        $this->httpRequestMessageBuilder
            ->addHeaders(['Authorization' => (string) $adminId]);
    }

    //ADMINS
    public function addAdmin(array $data): void
    {
        $this->completeBaseValues();
        $request = $this->httpRequestMessageBuilder
            ->addMethod(HttpMethodsEnum::POST)
            ->addUrl('admins')
            ->addBody($data)
            ->build();
        $response = $this->sendRequest($request);
        $this->validateResponse($response);
    }

    public function login(int $messengerId, string $messengerType): array
    {
        $this->completeBaseValues();
        $request = $this->httpRequestMessageBuilder
            ->addMethod(HttpMethodsEnum::POST)
            ->addUrl('login')
            ->addBody(compact('messengerId', 'messengerType'))
            ->build();
        $response = $this->sendRequest($request);
        $this->validateResponse($response);

        return MainServiceParser::parseBody($response);
    }

    //PROJECTS
    public function addProject(array $data, int $adminId): void
    {
        $this->completeBaseValues();
        $this->setToken($adminId);
        $request = $this->httpRequestMessageBuilder
            ->addMethod(HttpMethodsEnum::POST)
            ->addUrl('projects')
            ->addBody($data)
            ->build();
        $response = $this->sendRequest($request);
        $this->validateResponse($response);
    }

    public function getProjectList(int $adminId): array
    {
        $this->completeBaseValues();
        $this->setToken($adminId);
        $request = $this->httpRequestMessageBuilder
            ->addMethod(HttpMethodsEnum::GET)
            ->addUrl('projects')
            ->build();
        $response = $this->sendRequest($request);
        $this->validateResponse($response);

        return MainServiceParser::parseBody($response);
    }

    public function getProjectById(int $id, int $adminId): array
    {
        $this->completeBaseValues();
        $this->setToken($adminId);
        $request = $this->httpRequestMessageBuilder
            ->addMethod(HttpMethodsEnum::GET)
            ->addUrl('projects/' . $id)
            ->build();
        $response = $this->sendRequest($request);
        $this->validateResponse($response);

        return MainServiceParser::parseBody($response);
    }

    public function editProjectReviewType(int $id, ReviewTypeEnum $reviewTypeEnum, int $adminId): void
    {
        $reviewType = $reviewTypeEnum->value;
        $this->completeBaseValues();
        $this->setToken($adminId);
        $request = $this->httpRequestMessageBuilder
            ->addMethod(HttpMethodsEnum::PATCH)
            ->addUrl('projects/' . $id . '/review-type')
            ->addBody(compact('reviewType'))
            ->build();
        $response = $this->sendRequest($request);
        $this->validateResponse($response);
    }

    public function deleteProject(int $id, int $adminId): void
    {
        $this->completeBaseValues();
        $this->setToken($adminId);
        $request = $this->httpRequestMessageBuilder
            ->addMethod(HttpMethodsEnum::DELETE)
            ->addUrl('projects/'. $id)
            ->build();
        $response = $this->sendRequest($request);
        $this->validateResponse($response);
    }

    //CHAT
    public function addChat(int $projectId, array $data, int $adminId): void
    {
        $this->completeBaseValues();
        $this->setToken($adminId);
        $request = $this->httpRequestMessageBuilder
            ->addMethod(HttpMethodsEnum::POST)
            ->addUrl(sprintf('projects/%s/chat', $projectId))
            ->addBody($data)
            ->build();
        $response = $this->sendRequest($request);
        $this->validateResponse($response);
    }

    public function getChat(int $projectId, int $adminId): null|array
    {
        $this->completeBaseValues();
        $this->setToken($adminId);
        $request = $this->httpRequestMessageBuilder
            ->addMethod(HttpMethodsEnum::GET)
            ->addUrl(sprintf('projects/%s/chat', $projectId))
            ->build();
        $response = $this->sendRequest($request);
        $this->validateResponse($response);

        return MainServiceParser::parseBody($response);
    }

    public function deleteChat(int $projectId, int $adminId): void
    {
        $this->completeBaseValues();
        $this->setToken($adminId);
        $request = $this->httpRequestMessageBuilder
            ->addMethod(HttpMethodsEnum::DELETE)
            ->addUrl(sprintf('projects/%s/chat', $projectId))
            ->build();
        $response = $this->sendRequest($request);
        $this->validateResponse($response);
    }

    //CREDENTIALS
    public function addCredentials(int $projectId, array $data, int $adminId): void
    {
        $this->completeBaseValues();
        $this->setToken($adminId);
        $request = $this->httpRequestMessageBuilder
            ->addMethod(HttpMethodsEnum::POST)
            ->addUrl(sprintf('projects/%s/credentials', $projectId))
            ->addBody($data)
            ->build();
        $response = $this->sendRequest($request);
        $this->validateResponse($response);
    }

    public function getCredentials(int $projectId, int $adminId): null|array
    {
        $this->completeBaseValues();
        $this->setToken($adminId);
        $request = $this->httpRequestMessageBuilder
            ->addMethod(HttpMethodsEnum::GET)
            ->addUrl(sprintf('projects/%s/credentials', $projectId))
            ->build();
        $response = $this->sendRequest($request);
        $this->validateResponse($response);

        return MainServiceParser::parseBody($response);
    }

    public function deleteCredentials(int $projectId, int $adminId): void
    {
        $this->completeBaseValues();
        $this->setToken($adminId);
        $request = $this->httpRequestMessageBuilder
            ->addMethod(HttpMethodsEnum::DELETE)
            ->addUrl(sprintf('projects/%s/credentials', $projectId))
            ->build();
        $response = $this->sendRequest($request);
        $this->validateResponse($response);
    }

    //DEVELOPERS
    public function addDeveloper(int $projectId, array $data, int $adminId): void
    {
        $this->completeBaseValues();
        $this->setToken($adminId);
        $request = $this->httpRequestMessageBuilder
            ->addMethod(HttpMethodsEnum::POST)
            ->addUrl(sprintf('projects/%s/developers', $projectId))
            ->addBody($data)
            ->build();
        $response = $this->sendRequest($request);
        $this->validateResponse($response);
    }

    public function getDeveloperList(int $projectId, int $adminId): array
    {
        $this->completeBaseValues();
        $this->setToken($adminId);
        $request = $this->httpRequestMessageBuilder
            ->addMethod(HttpMethodsEnum::GET)
            ->addUrl(sprintf('projects/%s/developers', $projectId))
            ->build();
        $response = $this->sendRequest($request);
        $this->validateResponse($response);

        return MainServiceParser::parseBody($response);
    }

    public function getDeveloperById(int $projectId, int $developerId, int $adminId): array
    {
        $this->completeBaseValues();
        $this->setToken($adminId);
        $request = $this->httpRequestMessageBuilder
            ->addMethod(HttpMethodsEnum::GET)
            ->addUrl(sprintf('projects/%s/developers/%s', $projectId, $developerId))
            ->build();
        $response = $this->sendRequest($request);
        $this->validateResponse($response);

        return MainServiceParser::parseBody($response);
    }

    public function editDeveloperStatus(int $projectId, int $developerId, string $status, int $adminId): void
    {
        $this->completeBaseValues();
        $this->setToken($adminId);
        $request = $this->httpRequestMessageBuilder
            ->addMethod(HttpMethodsEnum::PATCH)
            ->addUrl(sprintf('projects/%s/developers/%s/status', $projectId, $developerId))
            ->addBody(compact('status'))
            ->build();
        $response = $this->sendRequest($request);
        $this->validateResponse($response);
    }

    public function deleteDeveloper(int $projectId, int $developerId, int $adminId): void
    {
        $this->completeBaseValues();
        $this->setToken($adminId);
        $request = $this->httpRequestMessageBuilder
            ->addMethod(HttpMethodsEnum::DELETE)
            ->addUrl(sprintf('projects/%s/developers/%s', $projectId, $developerId))
            ->build();
        $response = $this->sendRequest($request);
        $this->validateResponse($response);
    }

    //PULL REQUESTS
    public function getPullRequestList(int $projectId, int $adminId): array
    {
        $this->completeBaseValues();
        $this->setToken($adminId);
        $request = $this->httpRequestMessageBuilder
            ->addMethod(HttpMethodsEnum::GET)
            ->addUrl(sprintf('projects/%s/pull-requests', $projectId))
            ->build();
        $response = $this->sendRequest($request);
        $this->validateResponse($response);

        return MainServiceParser::parseBody($response);
    }

    public function bindDeveloperToPullRequest(int $projectId, int $pullRequestId, int $developerId, int $adminId): void
    {
        $this->completeBaseValues();
        $this->setToken($adminId);
        $request = $this->httpRequestMessageBuilder
            ->addMethod(HttpMethodsEnum::PATCH)
            ->addUrl(sprintf('projects/%s/pull-requests/%s/developer', $projectId, $pullRequestId))
            ->addBody(compact('developerId'))
            ->build();
        $response = $this->sendRequest($request);
        $this->validateResponse($response);
    }
}

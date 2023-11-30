<?php

declare(strict_types=1);

namespace App\Services\RequestServices;

use App\Exceptions\RequestServices\GithubRequestService\RequestException;
use App\Services\RequestServices\ResponseParser\GithubResponseParser;
use App\Services\RequestServices\ResponseParser\TelegramResponseParser;
use Eva\Http\Builder\JsonRequestMessageBuilder;
use Eva\Http\ClientInterface;
use Eva\Http\ClientWrapper;
use Eva\Http\HttpMethodsEnum;
use Eva\Http\Message\ResponseInterface;
use Eva\Http\Parser\JsonResponseParser;

class GithubRequestService extends ClientWrapper
{
    protected const DEFAULT_HEADERS = [
        'Content-Type' => 'application/json'
    ];

    public function __construct(
        private readonly string $host,
        ClientInterface $client,
    ) {
        parent::__construct($client, new JsonRequestMessageBuilder(), new JsonResponseParser());
    }

    private function validateResponse(ResponseInterface $response): void
    {
        if (200 === $response->getStatusCode()) {
            return;
        }

        throw new RequestException($response->getBody(), $response->getStatusCode());
    }

    protected function getBaseUrl(): string
    {
        return "http://{$this->host}/api/services/github/";
    }

    protected function completeBaseValues(): void
    {
        $this->httpRequestMessageBuilder
            ->addUrl($this->getBaseUrl())
            ->addHeaders(static::DEFAULT_HEADERS);
    }

    public function getPullRequestList(string $token, string $repositoryFullName): array
    {
        $this->completeBaseValues();
        $request = $this->httpRequestMessageBuilder
            ->addUrl('pull-requests')
            ->addQuery([
                'repositoryFullName' => $repositoryFullName
            ])
            ->addHeaders(['Authorization' => $token])
            ->addMethod(HttpMethodsEnum::GET)
            ->build();
        $response = $this->sendRequest($request);
        $this->validateResponse($response);

        return GithubResponseParser::parseBody($response);
    }

    public function checkToken(string $token, string $repositoryFullName): array
    {
        $this->completeBaseValues();
        $request = $this->httpRequestMessageBuilder
            ->addUrl('token/check')
            ->addBody([
                'repositoryFullName' => $repositoryFullName
            ])
            ->addHeaders(['Authorization' => $token])
            ->addMethod(HttpMethodsEnum::POST)
            ->build();
        $response = $this->sendRequest($request);
        $this->validateResponse($response);

        return GithubResponseParser::parseBody($response);
    }
}

<?php

declare(strict_types=1);

namespace App\Services\RequestServices;

use App\Enums\GitHub\PullRequestStatusEnum;
use App\Exceptions\Application\ApplicationErrorCodeEnum;
use App\Exceptions\Application\ApplicationException;
use App\Services\RequestServices\ResponseParser\GithubResponseParser;
use Eva\Http\Builder\JsonRequestMessageBuilder;
use Eva\Http\Client;
use Eva\Http\ClientInterface;
use Eva\Http\ClientWrapper;
use Eva\Http\HttpMethodsEnum;
use Eva\Http\Message\ResponseInterface;
use Eva\Http\Parser\JsonResponseParser;
use Psr\Log\LoggerInterface;
use function Eva\Common\Functions\json_decode;

class GithubRequestService extends ClientWrapper
{
    protected const BASE_URL = 'https://api.github.com';
    protected const DEFAULT_HEADERS = [
        'Content-Type' => 'application/json',
        'Accept' => 'application/vnd.github.full+json',
        'User-Agent' => 'reviewer',
        'X-GitHub-Api-Version' => '2022-11-28',
    ];

    public function __construct(
        private readonly LoggerInterface $logger,
        ClientInterface $client = null,
    ) {
        parent::__construct($client ?? new Client(), new JsonRequestMessageBuilder(), new GithubResponseParser());
    }

    protected function completeBaseValues(): void
    {
        $this->httpRequestMessageBuilder
            ->addUrl(static::BASE_URL)
            ->addHeaders(static::DEFAULT_HEADERS);
    }

    private function validateResponse(ResponseInterface $response): void
    {
        if (200 === $response->getStatusCode()) {
            return;
        }

        $this->logger->error(
            <<<EOL
            [Http code] {$response->getStatusCode()}
            [Body] {$response->getBody()}
            EOL,
        );

        throw new ApplicationException(ApplicationErrorCodeEnum::UNEXPECTED_REQUEST_RESPONSE_STATUS);
    }

    private function getStatusForPullRequest(string $fineGrantedToken, string $repFullName, int $number, string $state): PullRequestStatusEnum
    {
        $status = PullRequestStatusEnum::OPEN;

        if ($state !== 'open') {
            return PullRequestStatusEnum::CLOSED;
        }

        $reviewStatusList = $this->getLastPageReviewInfoList($fineGrantedToken, $repFullName, $number);

        if ($reviewStatusList !== []) {
            $reviewStatus = end($reviewStatusList);

            if ($reviewStatus['state'] === 'APPROVED') {
                $status = PullRequestStatusEnum::APPROVED;
            }

            if ($reviewStatus['state'] === 'CHANGES_REQUESTED' || $reviewStatus['state'] === 'COMMENTED') {
                $status = PullRequestStatusEnum::REVIEWING;
            }
        }

        $data = $this->getRequestedReviewerList($fineGrantedToken, $repFullName, $number);

        if (count($data['users']) > 0 || count($data['teams']) > 0) {
            $status = PullRequestStatusEnum::PENDING;
        }

        return $status;
    }

    public function getPullRequestWithStatusList(string $fineGrantedToken, string $repositoryFullName): array
    {
        $pullRequestList = $this->getPullRequestList($fineGrantedToken, $repositoryFullName);
        $pullRequestWithStatusList = [];

        foreach ($pullRequestList as $pullRequest) {
            $state = $pullRequest['state'];
            $number = $pullRequest['number'];
            $title = $pullRequest['title'];
            $branch = $pullRequest['head']['ref'];
            $repositoryName = $pullRequest['head']['repo']['name'];
            $repositoryFullName = $pullRequest['head']['repo']['full_name'];
            $status = $this->getStatusForPullRequest($fineGrantedToken, $repositoryFullName, $number, $state);
            $pullRequestWithStatus = compact(
                'branch',
                'title',
                'number',
                'repositoryName',
                'repositoryFullName',
                'status',
            );
            $pullRequestWithStatusList[] = $pullRequestWithStatus;
        }

        return $pullRequestWithStatusList;
    }

    private function getPullRequestList(
        string $fineGrantedToken,
        string $repositoryFullName,
        int $page = 1,
    ): array {
        $this->completeBaseValues();
        $request = $this->httpRequestMessageBuilder
            ->addHeaders([
                'Authorization' => 'Bearer '. $fineGrantedToken,
            ])
            ->addMethod(HttpMethodsEnum::GET)
            ->addUrl("/repos/$repositoryFullName/pulls?state=all&per_page=100&page=$page")
            ->build();
        $response = $this->sendRequest($request);
        $this->validateResponse($response);
        $body = GithubResponseParser::parseBody($response);
        $linkData = GithubResponseParser::parseLinkHeader($response);

        if (null === $linkData) {
            return $body;
        }

        if (array_key_exists('last', $linkData)) {
            $otherPagesPullRequestList = $this->getPullRequestList(
                $fineGrantedToken,
                $repositoryFullName,
                $page + 1,
            );
            array_push($body, ...$otherPagesPullRequestList);
        }

        return $body;
    }

    public function testGetPullRequestList(string $fineGrantedToken, string $repFullName, int $page = 1): int
    {
        $this->completeBaseValues();
        $request = $this->httpRequestMessageBuilder
            ->addHeaders([
                'Authorization' => 'Bearer '. $fineGrantedToken,
            ])
            ->addMethod(HttpMethodsEnum::GET)
            ->addUrl("/repos/$repFullName/pulls?state=all&per_page=1&page=$page")
            ->build();
        $response = $this->sendRequest($request);

        try {
            $this->validateResponse($response);
        } catch (ApplicationException $applicationException) {
            return $response->getStatusCode();
        }

        return $response->getStatusCode();
    }

    private function getRequestedReviewerList(
        string $fineGrantedToken,
        string $repositoryFullName,
        int $pullNumber,
    ): array {
        $this->completeBaseValues();
        $request = $this->httpRequestMessageBuilder
            ->addHeaders([
                'Authorization' => 'Bearer '. $fineGrantedToken,
            ])
            ->addMethod(HttpMethodsEnum::GET)
            ->addUrl("/repos/$repositoryFullName/pulls/$pullNumber/requested_reviewers")
            ->build();
        $response = $this->sendRequest($request);
        $this->validateResponse($response);

        return GithubResponseParser::parseBody($response);
    }

    private function getLastPageReviewInfoList(
        string $fineGrantedToken,
        string $repositoryFullName,
        int $pullRequestNumber,
        int $page = 1,
    ): array {
        $this->completeBaseValues();
        $request = $this->httpRequestMessageBuilder
            ->addHeaders([
                'Authorization' => 'Bearer '. $fineGrantedToken,
            ])
            ->addMethod(HttpMethodsEnum::GET)
            ->addUrl("/repos/$repositoryFullName/pulls/$pullRequestNumber/reviews?per_page=1&page=$page")
            ->build();
        $response = $this->sendRequest($request);
        $this->validateResponse($response);
        $body = GithubResponseParser::parseBody($response);
        $linkData = GithubResponseParser::parseLinkHeader($response);

        if (null === $linkData) {
            return $body;
        }

        if (array_key_exists('last', $linkData)) {
            $request = $this->httpRequestMessageBuilder
                ->addHeaders(static::DEFAULT_HEADERS)
                ->addHeaders([
                    'Authorization' => 'Bearer '. $fineGrantedToken,
                ])
                ->addMethod(HttpMethodsEnum::GET)
                ->addUrl($linkData['last'])
                ->build();
            $response = $this->sendRequest($request);
            $this->validateResponse($response);

            return GithubResponseParser::parseBody($response);
        }

        return $body;
    }
}

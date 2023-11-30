<?php

declare(strict_types=1);

namespace App\UseCase\GitHub;

use App\Dto\UseCase\GitHub\GetPullRequestWithStatusListDto;
use App\Dto\UseCase\GitHub\PullRequestWithStatusDto;
use App\Services\RequestServices\GithubRequestService;

readonly class GetPullRequestListHandler
{
    public function __construct(
        private GithubRequestService $githubRequestService,
    ) {}

    public function handle(GetPullRequestWithStatusListDto $dto): array
    {
        $list = $this->githubRequestService->getPullRequestWithStatusList(
            $dto->getToken(),
            $dto->getRepositoryFullName(),
        );

        return array_map(
            static fn(array $data) => new PullRequestWithStatusDto(...$data),
            $list,
        );
    }
}

<?php

declare(strict_types=1);

namespace App\UseCase\GitHub;

use App\Dto\UseCase\GitHub\CheckTokenDto;
use App\Services\RequestServices\GithubRequestService;

readonly class CheckTokenHandler
{
    public function __construct(
        private GithubRequestService $githubRequestService,
    ) {}

    public function handle(CheckTokenDto $dto): bool
    {
        return 200 === $this->githubRequestService->testGetPullRequestList(
            $dto->getToken(),
            $dto->getRepositoryFullName(),
        );
    }
}

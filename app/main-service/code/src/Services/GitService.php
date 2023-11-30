<?php

declare(strict_types=1);

namespace App\Services;

use App\Dto\Services\CredentialService\CredentialDto;
use App\Dto\Services\GitService\GetPullRequestListDto;
use App\Dto\Services\GitService\RemotePullRequestDto;
use App\Dto\Services\ProjectService\ProjectDto;
use App\Enums\PullRequestStatusEnum;
use App\Interfaces\Services\GitServiceInterface;
use App\Services\RequestServices\GithubRequestService;

readonly class GitService implements GitServiceInterface
{
    public function __construct(
        private GithubRequestService $githubRequestService,
    ) {}

    public function getPullRequestList(GetPullRequestListDto $dto): array
    {
        $pullRequestList = $this->githubRequestService->getPullRequestList($dto->getToken(), $dto->getGitRepositoryUrl());

        return array_map(static function(array $pullRequest) {
            return new RemotePullRequestDto(
                $pullRequest['branch'],
                $pullRequest['title'],
                $pullRequest['number'],
                $pullRequest['repositoryName'],
                $pullRequest['repositoryFullName'],
                PullRequestStatusEnum::from($pullRequest['status']),
            );
        }, $pullRequestList);
    }

    public function credentialTest(CredentialDto $dto, ProjectDto $projectDto): bool
    {
        $token = $dto->getToken();
        $data = $this->githubRequestService->checkToken($token, $projectDto->getGitRepositoryUrl());

        return (bool) $data['isCorrect'];
    }
}

<?php

declare(strict_types=1);

namespace App\UseCase;

use App\Dto\Services\ChatService\GetChatByProjectIdDto;
use App\Dto\Services\DeveloperService\DeveloperWithPendingPullRequestCountDto;
use App\Dto\Services\DeveloperService\GetDeveloperWithPendingPullRequestCountListDto;
use App\Dto\Services\ProjectService\GetProjectDto;
use App\Dto\Services\ProjectService\ProjectDto;
use App\Dto\Services\PullRequestService\ChangeDeveloperIdPullRequestDto;
use App\Dto\Services\PullRequestService\GetOpenReviewWithoutDeveloperPullRequestListDto;
use App\Dto\Services\PullRequestService\PullRequestDto;
use App\Enums\ChatStatusEnum;
use App\Enums\MessengerTypeEnum;
use App\Enums\ProjectStatusEnum;
use App\Exceptions\Application\ApplicationErrorCodeEnum;
use App\Exceptions\Application\ApplicationException;
use App\Interfaces\Services\ChatServiceInterface;
use App\Interfaces\Services\DeveloperServiceInterface;
use App\Interfaces\Services\ProjectServiceInterface;
use App\Interfaces\Services\PullRequestServiceInterface;
use App\Services\RequestServices\TelegramRequestService;

readonly class CaptureOpenPullRequestsHandler
{
    public function __construct(
        private PullRequestServiceInterface $pullRequestService,
        private ProjectServiceInterface $projectService,
        private DeveloperServiceInterface $developerService,
        private ChatServiceInterface $chatService,
        private TelegramRequestService $telegramRequestService,
    ) {}

    private function getChangeDeveloperForPullRequestMessage(
        DeveloperWithPendingPullRequestCountDto $developerWithPendingPullRequestCountDto,
        ProjectDto $projectDto,
        PullRequestDto $pullRequestDto,
    ): string {
        $url = 'https://github.com/'.$projectDto->getGitRepositoryUrl().'/pull/'.$pullRequestDto->getPullRequestNumber();

        return <<<EOL
        Project: {$projectDto->getName()}
        Branch: {$pullRequestDto->getBranch()}
        Title: {$pullRequestDto->getTitle()}
        Link: {$url}
        
        Developer @{$developerWithPendingPullRequestCountDto->getNickname()} has been appointed to this pull request
        EOL;
    }

    public function handle(array $projectIdList): void
    {
        foreach ($projectIdList as $projectId) {
            $projectDto = $this->projectService->getProject(new GetProjectDto($projectId));

            if ($projectDto->getProjectStatus() !== ProjectStatusEnum::READY) {
                continue;
            }

            $chatDto = $this->chatService->getChatByProjectId(new GetChatByProjectIdDto($projectDto->getId()));
            $listToCapture = $this->pullRequestService->getOpenReviewWithoutDeveloperPullRequestList(
                new GetOpenReviewWithoutDeveloperPullRequestListDto($projectDto->getId()),
            );

            foreach ($listToCapture as $openPullRequest) {
                assert($openPullRequest instanceof PullRequestDto);
                $developerWithPullRequestCountList = $this->developerService->getDeveloperWithPendingPullRequestCountList(
                    new GetDeveloperWithPendingPullRequestCountListDto(
                        $projectDto->getId(),
                    ),
                );
                $moreFreeDeveloper = $this->pullRequestService->findMostlyFreeDeveloper(
                    $developerWithPullRequestCountList,
                    $projectDto->getReviewType(),
                );

                if (null === $moreFreeDeveloper) {
                    continue;
                }

                $this->pullRequestService->changeDeveloperIdPullRequest(new ChangeDeveloperIdPullRequestDto(
                    $openPullRequest->getId(),
                    $projectDto->getId(),
                    $moreFreeDeveloper->getId(),
                ));

                if ($chatDto->getMessengerType() === MessengerTypeEnum::TELEGRAM) {
                    try {
                        $this->telegramRequestService->sendMessage(
                            $chatDto->getMessengerId(),
                            $this->getChangeDeveloperForPullRequestMessage($moreFreeDeveloper, $projectDto, $openPullRequest),
                        );
                    } catch (ApplicationException $exception) {
                        if (ApplicationErrorCodeEnum::CHAT_DOES_NOT_EXIST->value === $exception->getCode()) {
                            $this->chatService->changeChatStatus($projectDto->getId(), ChatStatusEnum::NOT_EXIST);
                            continue;
                        }

                        throw $exception;
                    }
                }
            }
        }
    }
}

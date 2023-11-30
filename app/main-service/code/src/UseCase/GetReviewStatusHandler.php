<?php

declare(strict_types=1);

namespace App\UseCase;

use App\Dto\Services\ChatService\GetChatByProjectIdDto;
use App\Dto\Services\DeveloperService\GetDeveloperByIdDto;
use App\Dto\Services\ProjectService\GetProjectDto;
use App\Dto\UseCase\ReviewStatusDto;
use App\Enums\ChatStatusEnum;
use App\Enums\MessengerTypeEnum;
use App\Enums\ProjectStatusEnum;
use App\Exceptions\Application\ApplicationErrorCodeEnum;
use App\Exceptions\Application\ApplicationException;
use App\Interfaces\Services\ProjectServiceInterface;
use App\Interfaces\Services\PullRequestServiceInterface;
use App\Interfaces\Services\DeveloperServiceInterface;
use App\Interfaces\Services\ChatServiceInterface;
use App\Services\RequestServices\TelegramRequestService;

readonly class GetReviewStatusHandler
{
    public function __construct(
        private PullRequestServiceInterface $pullRequestService,
        private ProjectServiceInterface $projectService,
        private DeveloperServiceInterface $developerService,
        private ChatServiceInterface $chatService,
        private TelegramRequestService $telegramRequestService,
    ) {}

    private function getStatusMessage(array $reviewStatusDtoList): string
    {
        if ($reviewStatusDtoList === []) {
            return <<<EOL
            There are currently no active pull requests
            EOL;
        }
        $statusMessage = <<<EOL
        Review status:
        
        
        EOL;

        foreach ($reviewStatusDtoList as $reviewStatusDto) {
            assert($reviewStatusDto instanceof ReviewStatusDto);
            $statusMessage .= <<<EOL
            Project: {$reviewStatusDto->getProjectName()}
            Branch: {$reviewStatusDto->getBranch()}
            Title: {$reviewStatusDto->getTitle()}
            Link: {$reviewStatusDto->getUrl()}
            Developer: @{$reviewStatusDto->getDeveloperNickname()}
            Past hours without review: {$reviewStatusDto->getPastHours()} h
            
            
            EOL;
        }

        return $statusMessage;
    }

    public function handle(array $projectIdList)
    {
        foreach ($projectIdList as $projectId) {
            $projectDto = $this->projectService->getProject(new GetProjectDto($projectId));
            $pullRequestList = $this->pullRequestService->getPendingReviewPullRequestList(new \App\Dto\Services\PullRequestService\GetPendingReviewPullRequestListDto(
                $projectDto->getId(),
            ));

            if ($projectDto->getProjectStatus() !== ProjectStatusEnum::READY) {
                continue;
            }

            $chatDto = $this->chatService->getChatByProjectId(new GetChatByProjectIdDto($projectDto->getId()));
            $reviewStatusDtoList = [];

            foreach ($pullRequestList as $pullRequest) {
                assert($pullRequest instanceof \App\Dto\Services\PullRequestService\PullRequestDto);
                if ($pullRequest->getDeveloperId() === null) {
                    continue;
                }
                $developerDto = $this->developerService->getDeveloperById(new GetDeveloperByIdDto(
                    $projectDto->getId(),
                    $pullRequest->getDeveloperId(),
                ));

                if ($developerDto === null || null === $pullRequest->getLastPendingDate()) {
                    continue;
                }

                $url = sprintf(
                    'https://github.com/%s/pull/%s',
                    $projectDto->getGitRepositoryUrl(),
                    $pullRequest->getPullRequestNumber(),
                );
                $data = [
                    'projectName' => $projectDto->getName(),
                    'developerNickname' => $developerDto->getNickname(),
                    'title' => $pullRequest->getTitle(),
                    'branch' => $pullRequest->getBranch(),
                    'url' => $url,
                    'pastHours' => (string) floor((time() - strtotime($pullRequest->getLastPendingDate()))/60/60),
                ];
                $reviewStatusDtoList[] = new ReviewStatusDto(...$data);
            }

            if ($chatDto->getMessengerType() === MessengerTypeEnum::TELEGRAM) {
                try {
                    $this->telegramRequestService->sendMessage(
                        $chatDto->getMessengerId(),
                        $this->getStatusMessage($reviewStatusDtoList),
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

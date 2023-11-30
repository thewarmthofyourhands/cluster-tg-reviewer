<?php

declare(strict_types=1);

namespace App\Actions\Projects;

use App\Actions\AbstractAction;
use App\Dto\Infrastructure\InputMessageTelegramDto;
use App\Enums\UseCase\Projects\GitServiceTypeEnum;
use App\Enums\UseCase\Projects\ReviewTypeEnum;
use App\Exceptions\Application\ApplicationErrorCodeEnum;
use App\Exceptions\Application\ApplicationException;
use App\Mappers\Dto\UseCase\Projects\AddProjectDtoMapper;
use App\Services\Telegram\TelegramBotService;
use App\Services\Telegram\TelegramUserService;
use App\UseCase\Projects\AddProjectHandler;
use App\Actions\DefaultAction;
use App\UseCase\Projects\GetProjectNameListHandler;

readonly class AddProjectAction extends AbstractAction
{
    public function __construct(
        TelegramBotService $telegramBotService,
        TelegramUserService $telegramUserService,
        private AddProjectHandler $addProjectHandler,
        private DefaultAction $defaultAction,
        private GetProjectNameListHandler $getProjectNameListHandler,
    ) {
        parent::__construct($telegramBotService, $telegramUserService);
    }

    public function __invoke(InputMessageTelegramDto $dto): void
    {
        $this->setTelegramUserActionData($dto->getTelegramUser()->getTelegramId(), 'addProject.setName');
        $this->sendMessage(
            $dto->getTelegramUser()->getTelegramId(),
            <<<EOL
            Please, type your project name:
            EOL,
        );
    }

    public function setName(InputMessageTelegramDto $dto): void
    {
        $name = $dto->getText();
        $projectNameList = $this->getProjectNameListHandler->handle($dto->getTelegramUser()->getTelegramId());
        $projectNameList = array_map(static fn(string $projectName) => mb_strtolower($projectName), $projectNameList);

        if (in_array(mb_strtolower($name), $projectNameList, true)) {
            throw new ApplicationException(ApplicationErrorCodeEnum::PROJECT_NAME_ALREADY_EXIST);
        }

        $keyboard = $this->createOneTimeKeyboard([
            GitServiceTypeEnum::GIT_HUB->value,
//            GitServiceTypeEnum::GIT_LAB->value,
//            GitServiceTypeEnum::BITBUCKET->value,
        ]);
        $this->setTelegramUserActionData(
            $dto->getTelegramUser()->getTelegramId(),
            'addProject.setGitType',
            compact('name'),
        );
        $this->sendMessage(
            $dto->getTelegramUser()->getTelegramId(),
            <<<EOL
            Please, choose git service for project.
            At the moment, only GitHub is available.
            In the future there will be integration with gitlab and bitbucket.
            EOL,
            $keyboard,
        );
    }

    public function setGitType(InputMessageTelegramDto $dto): void
    {
        $gitType = $dto->getText();

        if (null === GitServiceTypeEnum::tryFrom($gitType)) {
            throw new ApplicationException(ApplicationErrorCodeEnum::ENTITY_NOT_FOUND);
        }

        $data = $dto->getData()->getData();
        $data['gitType'] = $gitType;
        $this->setTelegramUserActionData(
            $dto->getTelegramUser()->getTelegramId(),
            'addProject.setGitRepositoryUrl',
            $data,
        );
        $this->sendMessage(
            $dto->getTelegramUser()->getTelegramId(),
            <<<EOL
            Please, type your git repository url.
            Example for input: https://github.com/myorganizationname/repository
            EOL,
        );
    }

    public function setGitRepositoryUrl(InputMessageTelegramDto $dto): void
    {
        $gitRepositoryUrl = trim($dto->getText());
        $pregMatch = preg_match('/^https:\/\/.+\/([a-zA-Z-_]+\/[a-zA-Z-_]+)/', $gitRepositoryUrl, $matches);

        if (1 === $pregMatch) {
            $gitRepositoryUrl = $matches[1];
        }

        if (1 !== preg_match('/^[A-Za-z0-9-_]{1,50}\/[A-Za-z0-9-_]{1,50}$/', $gitRepositoryUrl)) {
            throw new ApplicationException(ApplicationErrorCodeEnum::INVALID_GIR_REPOSITORY_URL);
        }

        $data = $dto->getData()->getData();
        $data['gitRepositoryUrl'] = $gitRepositoryUrl;
        $this->setTelegramUserActionData(
            $dto->getTelegramUser()->getTelegramId(),
            'addProject.setReviewType',
            $data,
        );
        $keyboard = $this->createOneTimeKeyboard([
            ReviewTypeEnum::TEAM_LEAD_REVIEW->value,
            ReviewTypeEnum::CROSS_REVIEW->value,
            ReviewTypeEnum::CROSS_REVIEW_WITHOUT_TEAM_LEAD->value,
        ]);
        $this->sendMessage(
            $dto->getTelegramUser()->getTelegramId(),
            <<<EOL
            Please, choose review type for project.
            Team lead review only - all pull requests fall exclusively to the team lead.
            Cross review - pull requests are distributed evenly among all developers on the project.
            Cross review without team lead - pull requests are distributed evenly among all developers on the project without the participation of the team leader.
            
            In the future, it will be possible to implement review code at the request of the developer, who himself chooses the pull request that he will accept. And a review code that will assign team leads to specific developers
            EOL,
            $keyboard,
        );
    }

    public function setReviewType(InputMessageTelegramDto $dto): void
    {
        $reviewType = $dto->getText();

        if (null === ReviewTypeEnum::tryFrom($reviewType)) {
            throw new ApplicationException(ApplicationErrorCodeEnum::ENTITY_NOT_FOUND);
        }

        $data = $dto->getData()->getData();
        $data['reviewType'] = $reviewType;
        $data['telegramUserId'] = $dto->getTelegramUser()->getTelegramId();
        $this->addProjectHandler->handle(
            AddProjectDtoMapper::convertArrayToDto($data),
        );
        $this->resetTelegramUserActionData($dto->getTelegramUser()->getTelegramId());
        $this->sendMessage(
            $dto->getTelegramUser()->getTelegramId(),
            <<<EOL
            Successfully added!
            EOL,
        );
        $this->defaultAction->getProjectList($dto);
    }
}
